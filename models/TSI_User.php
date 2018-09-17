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

namespace TSI_Client;

/**
 * TSI-User: v1.1.1
 * Class TSI_User
 * @package TSI_Client
 */
class TSI_User implements TSI_User_Interface {
    private $id = 0;
    private $username = "";
    private $role_id = 4;
    public $init_pw = "";
    private $first_name = "";
    private $last_name = "";
    private $query_name = "Console";
    private $email = "";
    private $language = "de_DE";
    private $servers = [];
    private $icon_pkg = '';
    private $reg_date = [
        'date' => '0000-00-00 00:00:00.000000',
        'timezone_type' => 3,
        'timezone' => 'Europe/Berlin'];
    private $reseller_id = 0;
    private $maxslots = 0;
    private $active = false;

    /**
     * @param int $user_id
     */
    public function setUserID(int $user_id) {
        $this->id = $user_id;
    }

    /**
     * @return int
     */
    public function getUserID() {
        return (int)$this->id;
    }

    /**
     * @param int $reseller_id
     */
    public function setResellerID(int $reseller_id) {
        $this->reseller_id = $reseller_id;
    }

    /**
     * @return int
     */
    public function getResellerID() {
        return (int)$this->reseller_id;
    }

    /**
     * @param int $role_id
     */
    public function setRoleID(int $role_id) {
        $this->role_id = $role_id;
    }

    /**
     * @return int
     */
    public function getRoleID() {
        return (int)$this->role_id;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username) {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUsername() {
        return strval($this->username);
    }

    /**
     * @param string $first_name
     */
    public function setFirstName(string $first_name) {
        $this->first_name = $first_name;
    }

    /**
     * @return string
     */
    public function getFirstName() {
        return strval($this->first_name);
    }

    /**
     * @param string $last_name
     */
    public function setLastName(string $last_name) {
        $this->last_name = $last_name;
    }

    /**
     * @return string
     */
    public function getLastName() {
        return strval($this->last_name);
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email) {
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
    public function getEmail() {
        return strval($this->email);
    }

    /**
     * @param string $query_name
     */
    public function setQueryNickname(string $query_name) {
        $this->query_name = $query_name;
    }

    /**
     * @return string
     */
    public function getQueryNickname() {
        return strval($this->query_name);
    }

    /**
     * @param string $lang
     */
    public function setLanguage(string $lang) {
        $this->language = $lang;
    }

    /**
     * @return string
     */
    public function getLanguage() {
        return strval($this->language);
    }

    /**
     * @param array $servers
     */
    public function setFixedVMs(array $servers) {
        $this->servers = $servers;
    }

    /**
     * @return array
     */
    public function getFixedVMs() {
        return (array)$this->servers;
    }

    /**
     * @param int $slots
     */
    public function setMaxSlotsVMs(int $slots) {
        $this->maxslots = $slots;
    }

    /**
     * @return int
     */
    public function getMaxSlotsVMs() {
        return (int)$this->maxslots;
    }

    /**
     * @return string
     */
    public function getRegDate() {
        return strval($this->reg_date['date']);
    }

    /**
     * @return string
     */
    public function getRegDateTimezone() {
        return strval($this->reg_date['timezone']);
    }

    /**
     * @return int
     */
    public function getRegDateTimezoneType() {
        return (int)$this->reg_date['timezone_type'];
    }

    /**
     * @param array $reg_date
     */
    public function setRegDateArray(array $reg_date) {
        $this->reg_date = $reg_date;
    }

    /**
     * @return int
     */
    public function getActive() {
        return (bool)$this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active) {
        $this->active = $active;
    }

    /**
     * @param string $icon_pkg
     */
    public function setIcon(string $icon_pkg) {
        $this->icon_pkg = $icon_pkg;
    }

    /**
     * @return string
     */
    public function getIcon() {
        return strval($this->icon_pkg);
    }

    /**
     * @param string $password
     */
    public function setInitPassword(string $password) {
        $this->init_pw = $password;
    }

    /**
     * @param int $instance
     * @param int $vserver_id
     */
    public function setServer(int $instance, int $vserver_id) {
        $this->servers[(int)$instance][(int)$vserver_id] = (int)$vserver_id;
    }

    /**
     * @param int $instance
     * @param int $vserver_id
     * @return mixed
     */
    public function getServer(int $instance, int $vserver_id) {
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
    public function getVMsByInstance(int $instance) {
        if(array_key_exists($instance,$this->servers))
            return (array)$this->servers[(int)$instance];

        return [];
    }
}