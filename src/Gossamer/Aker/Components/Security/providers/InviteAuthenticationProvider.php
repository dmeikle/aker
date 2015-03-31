<?php

/*
 *  This file is part of the Quantum Unit Solutions development package.
 * 
 *  (c) Quantum Unit Solutions <http://github.com/dmeikle/>
 * 
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace core\components\security\providers;

use Gossamer\Aker\Components\Security\Core\AuthenticationProviderInterface;
use Gossamer\Aker\Components\Security\Core\ClientInterface;
use core\datasources\DatasourceAware;
use Gossamer\Aker\Components\Security\Core\Client;
use core\components\security\exceptions\ClientCredentialsNotFoundException;
use components\contacts\models\ContactInviteModel;

/**
 * Performs the work in determining if an invited person was actually invited.
 * Invitees are created by Contacts that want to add people to the system for
 * without administrators needing to do the work in adding them.
 * eg: I want to add my assistant to my account...
 *
 * @author Dave Meikle
 */
class InviteAuthenticationProvider extends DatasourceAware implements AuthenticationProviderInterface, HttpAwareInterface{
    
    protected $logger = null;
    
    protected $httpRequest = null;
    
    protected $httpResponse = null;
    
    /**
     * 
     * @param type $credential
     * @return Client
     * 
     * @throws ClientCredentialsNotFoundException
     */
    public function loadClientByCredentials($credential) {
     
        $model = new ContactInviteModel($this->httpRequest, $this->httpResponse, $this->logger);
      
        $result = $this->datasource->query('get', $model, 'get', array('username' => $credential));
     
        if($result && array_key_exists('ContactInvite', $result)) {
            $client = current($result['ContactInvite']);
           
            $client['ipAddress'] = $this->getClientIp();
            
            return new Client($client);
        }
       
        throw new ClientCredentialsNotFoundException('no user found with credential ' . $credential);
    }

    /**
     * 
     * @param ClientInterface $client
     * 
     * @return ClientInterface
     */
    public function refreshClient(ClientInterface $client) {
        return $this->loadClientByCredentials($client->getCredentials());
    }

    /**
     * 
     * @param type $class
     * 
     * @return boolean
     */
    public function supportsClass($class) {
        return $class === 'Gossamer\Aker\Components\Security\Core\Client';
    }

    /**
     * 
     * @param ClientInterface $client
     * 
     * @return array
     */
    public function getRoles(ClientInterface $client) {
        //added this during dev but not complete
        return $client->getRoles();
    }

    /**
     * 
     * @return string
     */
    protected function getClientIp() {
 
        //Just get the headers if we can or else use the SERVER global
        if ( function_exists( 'apache_request_headers' ) ) { 
                $headers = apache_request_headers(); 
        } else { 
                $headers = $_SERVER; 
        }

        //Get the forwarded IP if it exists
        if ( array_key_exists( 'X-Forwarded-For', $headers ) && filter_var( $headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) { 
                $the_ip = $headers['X-Forwarded-For'];
        } elseif ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $headers ) && filter_var( $headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 )) { 
                $the_ip = $headers['HTTP_X_FORWARDED_FOR']; 
        } else {			
                $the_ip = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ); 
        }

        return $the_ip; 
    }

    public function setHttpRequest(\core\http\HTTPRequest $request) {
        $this->httpRequest = $request;
    }

    public function setHttpResponse(\core\http\HTTPResponse $response) {
        $this->httpResponse = $response;
    }

    public function setLogger(\Monolog\Logger $logger) {
        $this->logger = $logger;
    }

}
