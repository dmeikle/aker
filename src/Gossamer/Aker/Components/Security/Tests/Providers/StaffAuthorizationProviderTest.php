<?php

/*
 *  This file is part of the Quantum Unit Solutions development package.
 * 
 *  (c) Quantum Unit Solutions <http://github.com/dmeikle/>
 * 
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Gossamer\Aker\Components\Security\tests\providers;

use Gossamer\Aker\Components\Providers\StaffAuthorizationProvider;
use tests\BaseTest;
use Gossamer\Aker\Components\Core\Client;

/**
 * Description of StaffAuthorizationProviderTest
 *
 * @author Dave Meikle
 */
class StaffAuthorizationProviderTest extends BaseTest{
    
    public function  __construct() {
        parent::__construct();   
        if(!defined('__YML_KEY')) {            
            define('__YML_KEY', 'admin_staff_list');    
        }
    }
    
    public function testLoadAccess() {  
        $sap = new StaffAuthorizationProvider();
        $config = $sap->loadAccess();
        //print_r($config);
    }
    
    public function testIsAuthorized() {   
        $sap = new StaffAuthorizationProvider();
        $sap->setClient($this->getClient());
        $this->assertTrue($sap->isAuthorized());
    }
    
    private function getClient() {
        $client = new Client();
        $client->setRoles(array('IS_STAFF', 'IS_ADMINISTRATOR'));
        
        return $client;
    }
}
