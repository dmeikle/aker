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

use core\http\HTTPRequest;
use core\http\HTTPResponse;
use Monolog\Logger;

/**
 * HttpAwareInterface
 *
 * @author Dave Meikle
 */
interface HttpAwareInterface {
    
    public function setHttpRequest(HTTPRequest $request);
    public function setHttpResponse(HTTPResponse $response);
    public function setLogger(Logger $logger);
}
