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

use Gossamer\Aker\Components\Security\Core\AuthenticationProviderInterface;
use Gossamer\Aker\Components\Security\Core\ClientInterface;
use core\datasources\DatasourceAware;
use Gossamer\Aker\Components\Security\Core\Client;
use core\components\security\exceptions\ClientCredentialsNotFoundException;

/**
 * Authenticates all admin staff logging in
 *
 * @author Dave Meikle
 */
class StaffAuthenticationProvider extends UserAuthenticationProvider implements AuthenticationProviderInterface {

    /**
     * 
     * @param type $credential
     * @return Client
     * 
     * @throws ClientCredentialsNotFoundException
     */
    public function loadClientByCredentials($credential) {

        $result = $this->datasource->query(sprintf("select * from StaffAuthorizations where username = '%s' limit 1", $credential));

        if ($result) {
            $client = current($result);
            $client['ipAddress'] = $this->getClientIp();

            return new Client($client);
        }

        throw new ClientCredentialsNotFoundException('no user found with credential ' . $credential);
    }

    /**
     * 
     * @param ClientInterface $client
     * 
     * @return array
     */
    public function getRoles(ClientInterface $client) {
        $result = $this->datasource->query("select role from AccessRoles where Staff_id = '%d'", $client->getId());

        return $result;
    }

}
