<?php

/*
 *  This file is part of the Quantum Unit Solutions development package.
 * 
 *  (c) Quantum Unit Solutions <http://github.com/dmeikle/>
 * 
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Gossamer\Aker\Components\Core;

/**
 * Used for embedding into a form to mitigate XSS attacks
 *
 * @author Dave Meikle
 */
class FormToken implements FormTokenInterface {

    protected $ipAddress;
    protected $tokenString;
    protected $tokenTimestamp;
    protected $credentials;
    protected $clientId;

    /**
     * 
     * @param \Gossamer\Aker\Components\Core\Client $client
     */
    public function __construct(Client $client) {
        $this->tokenTimestamp = time();
        $this->setCredentials($client->getCredentials());
        $this->setIPAddress($client->getIpAddress());
        $this->setClientId($client->getId());
    }

    /**
     * accessor 
     * 
     * @param string
     */
    public function setIPAddress($ipAddress) {
        $this->ipAddress = $ipAddress;
    }

    /**
     * accessor 
     * 
     * @param string
     */
    public function setTokenString($token) {
        $this->token = $token;
    }

    /**
     * accessor 
     * 
     * @return string
     */
    public function getTimestamp() {
        return $this->tokenTimestamp;
    }

    /**
     * accessor 
     * 
     * @return string
     */
    public function toString() {
        return $this->ipAddress . '|' . $this->credentials . '|' . $this->clientId;
    }

    /**
     * accessor 
     * 
     * @param string
     */
    public function setCredentials($credentials) {
        $this->credentials = $credentials;
    }

    /**
     * accessor 
     * 
     * @param int
     */
    public function setClientId($id) {
        $this->clientId = $id;
    }

    /**
     * accessor 
     * 
     * @return encrypted string
     */
    public function generateTokenString() {
        $this->tokenString = crypt($this->toString());

        return $this->tokenString;
    }

    /**
     * accessor 
     * 
     * @return string
     */
    public function getTokenString() {
        return $this->tokenString;
    }

}
