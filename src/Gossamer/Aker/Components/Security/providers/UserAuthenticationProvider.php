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

/**
 * Authentictes Users trying to access the system
 *
 * @author Dave Meikle
 */
class UserAuthenticationProvider extends DatasourceAware implements AuthenticationProviderInterface {

    /**
     * load the client info
     * 
     * @param type $credential
     * 
     * @return Client
     * 
     * @throws ClientCredentialsNotFoundException
     */
    public function loadClientByCredentials($credential) {

        $result = $this->datasource->query(sprintf("select * from UserAuthentications where username = '%s' limit 1", $credential));

        if ($result) {
            $client = current($result);
            $client['ipAddress'] = $this->getClientIp();

            return new Client($client);
        }

        throw new ClientCredentialsNotFoundException('no user found with credential ' . $credential);
    }

    /**
     * 
     * @param ClientInterface $client
     * @return type
     */
    public function refreshClient(ClientInterface $client) {
        return $this->loadClientByCredentials($client->getCredentials());
    }

    /**
     * 
     * @param type $class
     * @return type
     */
    public function supportsClass($class) {
        return $class === 'Gossamer\Aker\Components\Security\Core\Client';
    }

    /**
     * 
     * @param ClientInterface $client
     * @return type
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
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
        } else {
            $headers = $_SERVER;
        }

        //Get the forwarded IP if it exists
        if (array_key_exists('X-Forwarded-For', $headers) && filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $the_ip = $headers['X-Forwarded-For'];
        } elseif (array_key_exists('HTTP_X_FORWARDED_FOR', $headers) && filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $the_ip = $headers['HTTP_X_FORWARDED_FOR'];
        } else {
            $the_ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        }

        return $the_ip;
    }

}
