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

use Gossamer\Aker\Components\Security\Core\AuthorizationProviderInterface;
use libraries\utils\YAMLParser;
use Gossamer\Aker\Components\Security\Core\ClientInterface;
use core\datasources\DatasourceAware;

/**
 * Checks authorization level for staff accessing various pages. access is
 * configured in the navigation-access.yml file. It's a fail-safe in case
 * someone tries to access a URL that DOES exist but is not presented to them 
 * in their menu because they don't have the access rights.
 *
 * @author Dave Meikle
 */
class StaffAuthorizationProvider extends DatasourceAware implements AuthorizationProviderInterface {

    protected $client = null;

    /**
     * 
     * @return boolean
     */
    public function isAuthorized() {
        $config = $this->loadAccess();

        if (is_null($config)) {
            return true; // this is not a monitored area so let it pass
        }

        $roles = $config['roles'];
        $authorized = array_intersect($roles, $this->client->getRoles());

        return (is_array($authorized) && count($authorized) > 0);
    }

    public function setClient(ClientInterface $client) {
        $this->client = $client;
    }

    /**
     * 
     * @return array|null
     */
    public function loadAccess() {
        $loader = new YAMLParser();
        $loader->setFilePath(__SITE_PATH . '/app/config/navigation-access.yml');

        $config = $loader->loadConfig();

        $this->loadComponentConfigurations($config);

        if (array_key_exists(__YML_KEY, $config)) {
            return $config[__YML_KEY];
        }

        return null;
    }

    /**
     * load the configuration file
     * 
     * @param array $config
     */
    private function loadComponentConfigurations(array &$config) {
        //first load the component list
        $list = $this->getDirectoryList();
        $loader = new YAMLParser();

        foreach ($list as $folderPath) {
            $loader->setFilePath($folderPath . '/config/navigation-access.yml');
            $componentConfig = $loader->loadConfig();

            if (!is_null($componentConfig) && is_array($componentConfig)) {
                $config = array_merge($config, $componentConfig);
            }
        }
    }

    /**
     * checks all component directories for configuration files
     * 
     * @return string
     */
    private function getDirectoryList() {

        $retval = array();
        if ($handle = opendir(__SITE_PATH . '/src/components')) {
            $blacklist = array('.', '..', 'somedir', 'somefile.php');
            while (false !== ($file = readdir($handle))) {
                if (!in_array($file, $blacklist)) {
                    $retval[] = __SITE_PATH . '/src/components/' . $file;
                }
            }
            closedir($handle);
        }
        if ($handle = opendir(__SITE_PATH . '/src/framework/core/components')) {
            $blacklist = array('.', '..', 'somedir', 'somefile.php');
            while (false !== ($file = readdir($handle))) {
                if (!in_array($file, $blacklist)) {
                    $retval[] = __SITE_PATH . '/src/framework/core/components/' . $file;
                }
            }
            closedir($handle);
        }

        return $retval;
    }

}
