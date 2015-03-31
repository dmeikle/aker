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

use Gossamer\Aker\Components\Security\Core\Client;

/**
 * TokenInterface
 *
 * @author Dave Meikle
 */
interface TokenInterface {

    public function toString();

    public function getRoles();

    public function getClient();

    public function setClient(Client $client);

    public function getIdentity();

    public function isAuthenticated();

    public function setAuthenticated($isAuthenticated);

    public function setAttribute($name, mixed $value);

    public function getAttributes();

    public function setAttributes(array $attributes);

    public function eraseCredentials();
}
