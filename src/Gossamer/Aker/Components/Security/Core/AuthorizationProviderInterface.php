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

use Gossamer\Aker\Components\Security\Core\ClientInterface;

/**
 * AuthorizationProviderInterface
 * 
 * @author Dave Meikle
 */
interface AuthorizationProviderInterface {

    public function setClient(ClientInterface $client);

    public function isAuthorized();
}
