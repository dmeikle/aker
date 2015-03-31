<?php

/*
 *  This file is part of the Quantum Unit Solutions development package.
 * 
 *  (c) Quantum Unit Solutions <http://github.com/dmeikle/>
 * 
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace core\components\security\http\firewall;

use Gossamer\Aker\Components\Security\Core\SecurityContextInterface;
use Gossamer\Aker\Components\Security\Core\AuthenticationManager;

/**
 * FirewallInterface
 *
 * @author Dave Meikle
 */
interface AuthorizationInterface {

    public function __construct(SecurityContextInterface $context, AuthenticationManager $manager);
}
