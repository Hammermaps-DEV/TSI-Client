<?php
/**
 * The MIT License
 * Copyright (c) 2018-2020 Lucas Brucksch (lbrucksch@hammermaps.de)
 * https://www.hammermaps.de | https://www.teamspeak-interface.de/
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace TSI_Client\Models;

/**
 * TSI-User: v1.1.1
 * Class TSI_User
 * @package TSI_Client
 */
class TSI_User implements TSI_User_Interface {
    /**
     * @var TSI_Client
     */
    public $client = null;

    /**
     * @var bool
     * @internal
     */
    private $multi = false;

    /**
     * @var int
     * @internal
     */
    private $id = 0;

    /**
     * @var string
     * @internal
     */
    private $username = "";

    /**
     * @var int
     * @internal
     */
    private $role_id = 4;

    /**
     * @var string
     */
    public $init_pw = "";

    /**
     * @var string
     * @internal
     */
    private $first_name = "";

    /**
     * @var string
     * @internal
     */
    private $last_name = "";

    /**
     * @var string
     * @internal
     */
    private $query_name = "Console";

    /**
     * @var string
     * @internal
     */
    private $email = "";

    /**
     * @var string
     * @internal
     */
    private $language = "de_DE";

    /**
     * @var array
     * @internal
     */
    private $servers = [];

    /**
     * @var string
     * @internal
     */
    private $icon_pkg = '';

    /**
     * @var array
     * @internal
     */
    private $reg_date = [
        'date' => '0000-00-00 00:00:00.000000',
        'timezone_type' => 3,
        'timezone' => 'Europe/Berlin'];

    /**
     * @var int
     * @internal
     */
    private $reseller_id = 0;

    /**
     * @var int
     * @internal
     */
    private $maxslots = 0;

    /**
     * @var bool
     * @internal
     */
    private $active = false;

    /* ################################## MULTI ######################################### */
    /**
     * @var array
     */
    private $multi_instances = [];

    /**
     * @var array
     */
    private $multi_vservers = [];

    /**
     * TSI_User constructor.
     * @param bool $multi
     */
    function __construct(bool $multi = false) {
        $this->multi = $multi;
    }

    /**
     * @param int $user_id
     */
    public function setUserID(int $user_id): void {
        $this->id = $user_id;
    }

    /**
     * @return int
     */
    public function getUserID(): int {
        return (int)$this->id;
    }

    /**
     * @param int $reseller_id
     */
    public function setResellerID(int $reseller_id): void {
        $this->reseller_id = $reseller_id;
    }

    /**
     * @return int
     */
    public function getResellerID(): int {
        return (int)$this->reseller_id;
    }

    /**
     * @param int $role_id
     */
    public function setRoleID(int $role_id): void {
        $this->role_id = $role_id;
    }

    /**
     * @return int
     */
    public function getRoleID(): int {
        return (int)$this->role_id;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUsername(): string {
        return strval($this->username);
    }

    /**
     * @param string $first_name
     */
    public function setFirstName(string $first_name): void {
        $this->first_name = $first_name;
    }

    /**
     * @return string
     */
    public function getFirstName(): string {
        return strval($this->first_name);
    }

    /**
     * @param string $last_name
     */
    public function setLastName(string $last_name): void {
        $this->last_name = $last_name;
    }

    /**
     * @return string
     */
    public function getLastName(): string {
        return strval($this->last_name);
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void {
        $email = trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            trigger_error(__CLASS__.' => setEmail(): "'.$email.'" is not a valid email address!', E_USER_WARNING);
            return;
        }

        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail(): string {
        return strval($this->email);
    }

    /**
     * @param string $query_name
     */
    public function setQueryNickname(string $query_name): void {
        $this->query_name = $query_name;
    }

    /**
     * @return string
     */
    public function getQueryNickname(): string {
        return strval($this->query_name);
    }

    /**
     * @param string $lang
     */
    public function setLanguage(string $lang): void {
        $this->language = $lang;
    }

    /**
     * @return string
     */
    public function getLanguage(): string {
        return strval($this->language);
    }

    /**
     * @param array $servers
     */
    public function setFixedVMs(array $servers): void {
        $this->servers = $servers;

        if($this->multi && count($servers) >= 2) {
            $this->readServerMulti();
        }
    }

    /**
     * @return array
     */
    public function getFixedVMs(): array {
        return (array)$this->servers;
    }

    /**
     * @param int $slots
     */
    public function setMaxSlotsVMs(int $slots): void {
        $this->maxslots = $slots;
    }

    /**
     * @return int
     */
    public function getMaxSlotsVMs(): int {
        return (int)$this->maxslots;
    }

    /**
     * @return string
     */
    public function getRegDate(): string {
        return strval($this->reg_date['date']);
    }

    /**
     * @return string
     */
    public function getRegDateTimezone(): string {
        return strval($this->reg_date['timezone']);
    }

    /**
     * @return int
     */
    public function getRegDateTimezoneType(): int {
        return (int)$this->reg_date['timezone_type'];
    }

    /**
     * @param array $reg_date
     */
    public function setRegDateArray(array $reg_date): void {
        $this->reg_date = $reg_date;
    }

    /**
     * @return int
     */
    public function getActive(): bool {
        return (bool)$this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void {
        $this->active = $active;
    }

    /**
     * @param string $icon_pkg
     */
    public function setIcon(string $icon_pkg): void {
        $this->icon_pkg = $icon_pkg;
    }

    /**
     * @return string
     */
    public function getIcon(): string {
        return strval($this->icon_pkg);
    }

    /**
     * @param string $password
     */
    public function setInitPassword(string $password): void {
        $this->init_pw = $password;
    }

    /**
     * @param int $instance
     * @param int $vserver_id
     */
    public function setServer(int $instance, int $vserver_id): void {
        $this->servers[(int)$instance][(int)$vserver_id] = (int)$vserver_id;
    }

    /**
     * @param int $instance
     * @param int $vserver_id
     * @return mixed
     */
    public function getServer(int $instance, int $vserver_id): bool {
        if(array_key_exists($instance,$this->servers)) {
            foreach ($this->servers[(int)$instance] as $sid) {
                if($vserver_id == $sid) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param int $instance
     * @return mixed
     */
    public function getVMsByInstance(int $instance): array {
        if(array_key_exists($instance,$this->servers))
            return (array)$this->servers[(int)$instance];

        return [];
    }

    /***************************** MULTI '''''''''''''''''''''''''''''*/

    /**
     * @return array
     */
    public function getServersMulti(): array {
        if(!$this->multi)
            return [];

        return [
            'instances' => $this->multi_instances,
            'vservers' => $this->multi_vservers
        ];
    }

    /**
     * Read for Multi Calls
     */
    private function readServerMulti(): void {
        if(!$this->multi)
            return;
		
        //Get all Servers
        $vserver_ids = []; $i=0;
        if(count($this->servers >= 2)) {
            foreach ($this->servers as $instance => $server) {
                $this->client->insertCall('instanceGet',['id'=>(int)$instance]); //set the call
                foreach ($server as $vserver_id) {
                    $this->client->insertCall('vServerGet',['id'=>$instance,'sid'=>$vserver_id]);
                    $vserver_ids[$i]['vserver_id'] = (int)$vserver_id;
                    $vserver_ids[$i]['instance'] = (int)$instance;
                    $i++;
                }
            }

            $this->client->Exec(); //execute
            foreach ($this->client->getResponseGroup('instanceGet') as $hash => $instance_data) {
                if($instance_data['valid'] &&
                    array_key_exists('response',$instance_data) &&
                    count($instance_data['response'])) {
                    $instance = new TSI_Instance();
                    $instance->setID((int)$instance_data['response']['id']);
                    $instance->setIP(strval($instance_data['response']['server_ip']));
                    $instance->setLastPermImport(strval($instance_data['response']['last_perm_import']));
                    $instance->setQueryPort((int)$instance_data['response']['query_port']);
                    $instance->setServerAdmin(html_entity_decode($instance_data['response']['serveradmin']));
                    $this->multi_instances[] = $instance;
                    unset($instance);
                }
            }

            $i=0;
            foreach ($this->client->getResponseGroup('vServerGet') as $hash => $vserver_data) {
                if($vserver_data['valid'] &&
                    array_key_exists('response',$vserver_data) &&
                    count($vserver_data['response'])) {
                    $properties = new TSI_Properties();
                    $properties->setName(html_entity_decode($vserver_data['response']['name']));
                    $properties->setMaxClients((int)$vserver_data['response']['maxclients']);

                    $vserver = new TSI_VServer();
                    $vserver->setProperties($properties);
                    $vserver->setServerID($vserver_ids[$i]['vserver_id']);
                    $vserver->setInstanceID($vserver_ids[$i]['instance']);
                    $vserver->setUID(html_entity_decode($vserver_data['response']['unique_id']));
                    $vserver->setOnline(strval($vserver_data['response']['status']));
                    $vserver->setPlatform(html_entity_decode($vserver_data['response']['platform']));
                    $vserver->setVersion(html_entity_decode($vserver_data['response']['version']));
                    $vserver->setClientsOnline((int)$vserver_data['response']['clientsonline']);
                    $vserver->setChannelOnline((int)$vserver_data['response']['channelsonline']);
                    $vserver->setCreatedTime((int)$vserver_data['response']['created']);
                    $vserver->setUptime((int)$vserver_data['response']['uptime']);
                    $this->multi_vservers[] = $vserver;
                    unset($vserver);
                } $i++;
            }
        }
    }
}