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
 * Class TSI_Instance
 * @package TSI_Client\Models
 */
class TSI_Resellers implements TSI_Resellers_Interface {
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
     * @var array
     */
    private $instances = [];

    /**
     * @var string
     * @internal
     */
    private $icon_pkg = '';
	
	    /**
     * @var bool
     * @internal
     */
	private $allowed_own_instances = false;

    /**
     * @var array
     * @internal
     */
    private $reg_date = [
        'date' => '0000-00-00 00:00:00.000000',
        'timezone_type' => 3,
        'timezone' => 'Europe/Berlin'];

    /**
     * @var array
     * @internal
     */
    private $limits = [
        'max_slots_per_virtualservers' => 32,
        'max_web_users' => 2,
        'max_virtualservers' => 2
    ];

    /**
     * @var bool
     * @internal
     */
    private $active = false;

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
    }

    /**
     * @return array
     */
    public function getFixedVMs(): array {
        return (array)$this->servers;
    }

    /**
     * @return array
     */
    public function getFixedInstances(): array {
        return (array)$this->instances;
    }

    /**
     * @param array $instances
     */
    public function setFixedInstances(array $instances) {
        $this->instances = $instances;
    }

    /**
     * @param int $instance
     */
    public function setFixedInstance(int $instance): void {
        $this->instances[] = (int)$instance;
    }

    /**
     * @param int $slots
     */
    public function setMaxSlotsVMs(int $slots): void {
        $this->limits['max_slots_per_virtualservers'] = $slots;
    }

    /**
     * @return int
     */
    public function getMaxSlotsVMs(): int {
        return (int)$this->limits['max_slots_per_virtualservers'];
    }

    /**
     * @param int $users
     */
    public function setMaxWebUsers(int $users): void {
        $this->limits['max_web_users'] = $users;
    }

    /**
     * @return int
     */
    public function getMaxWebUsers(): int {
        return (int)$this->limits['max_web_users'];
    }

    /**
     * @param int $servers
     */
    public function setMaxVirtualServers(int $servers): void {
        $this->limits['max_virtualservers'] = $servers;
    }

    /**
     * @return int
     */
    public function getMaxVirtualServers(): int {
        return (int)$this->limits['max_virtualservers'];
    }

    /**
     * @param array $limits
     */
    public function setLimits(array $limits): void {
        $this->limits = $limits;
    }

    /**
     * @return array
     */
    public function getLimits(): array {
        return (array)$this->limits;
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
     * @return int
     */
    public function getAllowedOwnInstances(): bool {
        return (bool)$this->allowed_own_instances;
    }

    /**
     * @param bool $active
     */
    public function setAllowedOwnInstances(bool $active): void {
        $this->allowed_own_instances = $active;
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
}