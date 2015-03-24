<?php

/*
 *  This file is part of the Quantum Unit Solutions development package.
 * 
 *  (c) Quantum Unit Solutions <http://github.com/dmeikle/>
 * 
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Gossamer\Aker\Authorizations;

use core\components\security\core\SecurityContextInterface;

/**
 * AuthenticationManagerInterface
 *
 * @author Dave Meikle
 */
interface AuthenticationManagerInterface {

    public function authenticate(SecurityContextInterface $context); //(TokenInterface $token);    

    public function generateEmptyToken();

    public function getClient();
}
