<?php

/*
 *  This file is part of the Quantum Unit Solutions development package.
 * 
 *  (c) Quantum Unit Solutions <http://github.com/dmeikle/>
 * 
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Gossamer\Aker\Components\Security\Core;

use Gossamer\Aker\Components\Security\Core\AuthenticationManagerInterface;
use Monolog\Logger;
use core\services\ServiceInterface;
use Gossamer\Aker\Components\Security\Core\SecurityToken;
use core\components\security\exceptions\ArgumentNotPassedException;
use core\components\security\exceptions\ClientCredentialsNotFoundException;
use libraries\utils\Container;

/**
 * manager for authenticating users. Behaviors can be changed by swapping
 * in different providers.
 *
 * @author Dave Meikle
 */
class AuthenticationManager implements AuthenticationManagerInterface, ServiceInterface {

    protected $logger = null;
    protected $userAuthenticationProvider = null;
    protected $container = null;
    protected $node = null;

    /**
     * 
     * @param Logger $logger
     */
    public function __construct(Logger $logger) {
        $this->logger = $logger;
    }

    /**
     * accessor 
     * 
     * @param Container $container
     */
    public function setContainer(Container $container) {
        $this->container = $container;
    }

    /**
     * authenticates a user based on their context
     * 
     * @param \Gossamer\Aker\Components\Security\Core\SecurityContextInterface $context
     * 
     * @throws ClientCredentialsNotFoundException
     */
    public function authenticate(SecurityContextInterface $context) {

        $token = $this->generateEmptyToken();

        try {
            $this->userAuthenticationProvider->loadClientByCredentials($token->getClient()->getCredentials());
        } catch (ClientCredentialsNotFoundException $e) {

            $this->logger->addAlert('Client not found ' . $e->getMessage());
            throw $e;
        }

        //validate the client, if good then add to the context
        if (true) {
            $context->setToken($token);
        }
    }

    /**
     * placeholder function since we need the ServiceInterface
     */
    public function execute() {
        
    }

    /**
     * accessor for passing in array of params
     * 
     * @param array $params
     * 
     * @throws ArgumentNotPassedException
     */
    public function setParameters(array $params) {

        if (!array_key_exists('user_authentication_provider', $params)) {
            throw new ArgumentNotPassedException('user_authentication_provider not specified in config');
        }

        $this->userAuthenticationProvider = $params['user_authentication_provider'];
    }

    /**
     * generates a default token
     * 
     * @return SecurityToken
     */
    public function generateEmptyToken() {

        $token = unserialize(getSession('_security_secured_area'));

        if (!$token) {
            return $this->generateNewToken();
        }

        return $token;
    }

    /**
     * generates a new token based on current client
     * 
     * @return SecurityToken
     */
    public function generateNewToken() {
        $client = $this->getClient();
        $token = new SecurityToken($client, __YML_KEY, $client->getRoles());

        return $token;
    }

    /**
     * 
     * @return \Gossamer\Aker\Components\Security\Core\Client
     */
    public function getClient() {
        $client = new Client();
        $client->setIpAddress($_SERVER['REMOTE_ADDR']);
        $client->setRoles(array('ROLE_ANONYMOUS_USER'));
        $client->setCredentials($this->getClientHeaderCredentials());

        return $client;
    }

    /**
     * retrieves a list of credentials (IS_ADMINISTRATOR|IS_ANONYMOUS...)
     * 
     * @return array(credentials)|null
     */
    protected function getClientHeaderCredentials() {
        $headers = getallheaders();
        if (array_key_exists('credentials', $headers)) {
            return $headers['credentials'];
        }

        return null;
    }

}
