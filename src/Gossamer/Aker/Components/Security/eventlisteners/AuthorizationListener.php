<?php

/*
 *  This file is part of the Quantum Unit Solutions development package.
 * 
 *  (c) Quantum Unit Solutions <http://github.com/dmeikle/>
 * 
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Gossamer\Aker\Components\Security\EventListeners;

use core\eventlisteners\AbstractListener;
use core\components\security\exceptions\TokenExpiredException;
use core\components\security\exceptions\TokenMissingException;

/**
 * checks to make sure a token exists on a submitted form
 *
 * @author Dave Meikle
 */
class AuthorizationListener extends AbstractListener {

    /**
     * 
     * @param type $params
     * 
     * @throws TokenExpiredException
     */
    public function on_token_expired($params) {
        $this->logger->addError('Token expired on submitted form - throwing Exception');
        throw new TokenExpiredException();
    }

    /**
     * 
     * @param type $params
     * 
     * @throws TokenMissingException
     */
    public function on_token_missing($params) {
        $this->logger->addError('Token missing from submitted form - throwing Exception');
        throw new TokenMissingException();
    }

}
