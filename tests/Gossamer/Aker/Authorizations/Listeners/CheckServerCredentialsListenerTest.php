<?php

/*
 *  This file is part of the Quantum Unit Solutions development package.
 * 
 *  (c) Quantum Unit Solutions <http://github.com/dmeikle/>
 * 
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace tests\Gossamer\Aker\Authorizations\Listeners;

use Gossamer\Aker\Authorizations\Listeners\CheckServerCredentialsListener;
use Gossamer\Horus\EventListeners\Event;
use Gossamer\Horus\Core\Request;

/**
 * EventDispatcherTest
 *
 * @author Dave Meikle
 */
class CheckServerCredentialsListenerTest extends \tests\BaseTest {
    
    public function testOnEntryPoint() {

        $listener = new CheckServerCredentialsListener($this->getLogger());
        $request = new Request();
        $params = array(
            'headers' => array(
                'serverAuth' => '12345'
            ),
            'request' => $request
        );
        
        $event = new Event('entry_point', $params);
        try{
            $listener->on_entry_point($event);
        }catch(\Exception $e) {
            $this->fail('no error should have occurred');
        }
    }



    public function testOnEntryPointInvalidId() {

        $listener = new CheckServerCredentialsListener($this->getLogger());
        $request = new Request();
        $params = array(
            'headers' => array(
                //'serverAuth' => '12345'
            ),
            'request' => $request
        );
        
        $event = new Event('entry_point', $params);
        try{
            $listener->on_entry_point($event);
            $this->fail('invalidserverID should have thrown error');
        }catch(\Exception $e) {
            $this->assertTrue($e instanceof \Gossamer\Aker\Exceptions\InvalidServerIDException);
        }
    }
    
}
//listeners:
//        
//        - { 'event': 'request_start', 'listener': 'components\staff\listeners\LoadEmergencyContactsListener', 'datasource': 'datasource1' }
//        - { 'event': 'request_start', 'listener': 'core\eventlisteners\LoadListListener', 'datasource': 'datasource1', 'class': 'components\geography\models\ProvinceModel', 'cacheKey': 'Provinces' }
    