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

use Gossamer\Aker\Components\Security\Core\SecurityToken;

/**
 * this class is deprecated
 *
 * @author Dave Meikle
 */
class UsernamePasswordToken extends SecurityToken
{
    private $password = null;
    
    public function __construct($client, $password, $ymlKey, array $roles = array()) {
        parent::__construct($client, $ymlKey, $roles);
        $this->password = $password;
    }
    
    public function setPassword($password) {
        $this->password = $password;
    }
    
    public function getPassword() {
        return $this->password;
    }
}
