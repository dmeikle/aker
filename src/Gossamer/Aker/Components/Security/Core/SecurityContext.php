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
 * stores the token for the current user
 *
 * @author Dave Meikle
 */
class SecurityContext implements SecurityContextInterface {

    private $token = null;

    /**
     * accessor
     * 
     * @return \Gossamer\Aker\Components\Core\TokenInterface
     */
    public function getToken() {
        return $this->token;
    }

    final public function isGranted(mixed $attributes, mixed $object = null) {
        
    }

    /**
     * accessor
     * 
     * @param \Gossamer\Aker\Components\Core\TokenInterface $token
     */
    public function setToken(TokenInterface $token) {
        $this->token = $token;
    }

}
