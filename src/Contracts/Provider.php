<?php

namespace WeismannWeb\HostingServices\Contracts;

interface ServerManager
{

    /**
     * @return bool
     * @throws Server_Exception
     */
    public function testConnection();

    /**
     * @param Server_Account
     * @return bool
     * @throws Server_Exception
     */
    public function createAccount(Server_Account $a);

    /**
     * @param Server_Account
     * @return Server_Account
     * @throws Server_Exception
     */
    public function synchronizeAccount(Server_Account $a);
    
    /**
     * @param Server_Account
     * @return bool
     * @throws Server_Exception
     */
    public function suspendAccount(Server_Account $a);

    /**
     * @param Server_Account
     * @return bool
     * @throws Server_Exception
     */
    public function unsuspendAccount(Server_Account $a);

    /**
     * @param Server_Account
     * @return bool
     * @throws Server_Exception
     */
    public function cancelAccount(Server_Account $a);

    /**
     * @param Server_Account
     * @return bool
     * @throws Server_Exception
     */
    public function changeAccountPassword(Server_Account $a, $new_password);

    /**
     * @param Server_Account
     * @return bool
     * @throws Server_Exception
     */
    public function changeAccountUsername(Server_Account $a, $new_username);

    /**
     * @param Server_Account
     * @param string $new_domain
     * @return bool
     * @throws Server_Exception
     */
    public function changeAccountDomain(Server_Account $a, $new_domain);

    /**
     * @param Server_Account
     * @param string - new ip
     * @return bool
     * @throws Server_Exception
     */
    public function changeAccountIp(Server_Account $a, $new_ip);
    
    /**
     * @param Server_Account
     * @param Server_Package - new package
     * @return bool
     * @throws Server_Exception
     */
    public function changeAccountPackage(Server_Account $a, Server_Package $p);
}
