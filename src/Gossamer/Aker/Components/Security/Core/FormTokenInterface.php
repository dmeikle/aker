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
 * FormTokenInterface
 *
 * @author Dave Meikle
 */
interface FormTokenInterface {

    public function setIPAddress($ipAddress);

    public function setTokenString($token);

    public function toString();

    public function getTimestamp();

    public function setCredentials($credentials);

    public function setClientId($id);

    public function generateTokenString();

    public function getTokenString();
}
