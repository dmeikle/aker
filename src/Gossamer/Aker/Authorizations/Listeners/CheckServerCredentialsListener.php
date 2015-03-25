<?php
namespace Gossamer\Aker\Authorizations\Listeners;

use Gossamer\Aker\Exceptions\InvalidServerIDException;
use Gossamer\Aker\Exceptions\UnauthorizedAccessException;
use Gossamer\Aker\Entities\ServerAuthenticationToken;
use Gossamer\Aker\Commands\GetCommand;
use Gossamer\Caching\CacheManager;
use Gossamer\Pesedget\Database\EntityManager;
use Gossamer\Horus\EventListeners\AbstractListener;
use Gossamer\Horus\EventListeners\Event;

class CheckServerCredentialsListener extends AbstractListener
{
    
    public function on_entry_point(Event $event) {
        
        $params = $event->getParams();
        $this->logger->addDebug('entry point for CheckServerCredentials');
        $headers = $params['headers'];
        $this->request = $params['request'];
        
       // file_put_contents('/var/www/db-repo/logs/save-test.log', print_r($headers, true) . "\r\n", FILE_APPEND); 
        if (!array_key_exists('serverAuth', $headers) || strlen($headers['serverAuth']) < 1) {
            $this->logger->addError('CheckServerCredentialsListener::on_entry_point expects serverAuth header');
            throw new InvalidServerIDException('server identification missing from Headers');
        }
        if(!$this->checkServer($headers['serverAuth'], $_SERVER['REMOTE_ADDR'])) {
            $this->logger->addError('CheckServerCredentialsListener::on_entry_point has mismatched serverAuth header');
            throw new UnauthorizedAccessException($params['httpRequest']->getAttribute('ipAddress') . ' is not authorized');
        }
    }

    
    protected function checkServer($authToken, $ipAddress){
       
        $cachedToken = $this->retrieveFromCache($authToken, $ipAddress);
        if($cachedToken == true) {
            
            return true;
        }
       
       
        //we didn't find it in cache - do a lookup
        $token = new ServerAuthenticationToken();
        
        $cmd = new GetCommand($token, $this->request, EntityManager::getInstance()->getCredentials());
       
        // file_put_contents('/var/www/db-repo/logs/save-test.log', print_r(array('token' => $authToken, 'ipAddress' =>$ipAddress), true) . "\r\n", FILE_APPEND);
        $results = $cmd->execute(array('token' => $authToken, 'ipAddress' =>$ipAddress), null);
        
        if(is_null($results) || count($results) == 0){
            throw new UnauthorizedAccessException("Server not found", 1);
        }
     
        $tokenResult = current($results);
        //check to see if the token is expired - only used if we have a licensing agreement that expires
        // if($result['expirationTime'] < time()) {
            // throw new UnauthorizedAccessException();
        // }
        
        if($tokenResult['id'] > 0) {
            $this->saveToCache($tokenResult, $ipAddress);
        
            return true;
        }
        
        return false;
    }
    
    
    protected function saveToCache($token, $ipAddress) {
        $this->logger->addDebug('CheckServerCredentials - save to cache');
        $cacheManager = new CacheManager($this->logger);
        $ipAddress = str_replace('.', '_', $ipAddress);
        
        $cacheManager->saveToCache('ServerAuthenticationTokens_' . $ipAddress, $token);
    }
    
    public function retrieveFromCache($authToken, $ipAddress) {
        $this->logger->addDebug('CheckServerCredentials - retrieve from cache');
        $cacheManager = new CacheManager($this->logger);
        $ipAddress = str_replace('.', '_', $ipAddress);
        
        $token = $cacheManager->retrieveFromCache('ServerAuthenticationTokens_' . $ipAddress);
        unset($cacheManager);
        if(!is_array($token) || !array_key_exists('token', $token)) {
            return false;
        }
        if($token['token'] == $authToken) {
        $this->logger->addDebug('CheckServerCredentials - token found');
            return true;
        }
        $this->logger->addDebug('CheckServerCredentials - no token found');
        
        return false;
    }
}
