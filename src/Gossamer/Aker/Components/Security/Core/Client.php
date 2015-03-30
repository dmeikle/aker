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

use Gossamer\Aker\Components\Core\ClientInterface;

/**
 * Client class used for storing user context data
 *
 * @author Dave Meikle
 */
class Client implements ClientInterface {

    protected $id;
    protected $password = null;
    protected $roles = array();
    protected $credentials = 'anonymous';
    protected $ipAddress = null;
    protected $status = null;

    /**
     * 
     * @param array $params
     */
    public function __construct(array $params = array()) {

        if (count($params) > 0) {
            $this->password = $params['password'];
            $this->status = $params['status'];
            $this->roles = explode('|', $params['roles']);
            $this->credentials = (array_key_exists('credentials', $params)) ? $params['credentials'] : $params['username'];
            $this->ipAddress = (array_key_exists('ipAddress', $params)) ? $params['ipAddress'] : '';
            $this->id = (array_key_exists('Staff_id', $params)) ? $params['Staff_id'] : '';

            if (array_key_exists('Contacts_id', $params)) {
                $this->id = $params['Contacts_id'];
            } elseif (array_key_exists('User_id', $params)) {
                $this->id = $params['User_id'];
            }
        }
    }

    /**
     * accessor 
     * 
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * accessor 
     * 
     * @param array $roles
     */
    public function setRoles(array $roles) {
        $this->roles = $roles;
    }

    /**
     * accessor 
     * 
     * @param string $credentials
     */
    public function setCredentials($credentials) {
        $this->credentials = $credentials;
    }

    /**
     * accessor 
     * 
     * @param string $ipAddress
     */
    public function setIpAddress($ipAddress) {
        $this->ipAddress = $ipAddress;
    }

    /**
     * accessor 
     * 
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * accessor 
     * 
     * @return array
     */
    public function getRoles() {
        return $this->roles;
    }

    /**
     * accessor 
     * 
     * @return string
     */
    public function getCredentials() {
        return $this->credentials;
    }

    /**
     * accessor 
     * 
     * @return string
     */
    public function getIpAddress() {
        return $this->ipAddress;
    }

    /**
     * accessor 
     * 
     * @return string
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * accessor 
     * 
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * accessor 
     * 
     * @return int
     */
    public function getId() {
        return $this->id;
    }

}
