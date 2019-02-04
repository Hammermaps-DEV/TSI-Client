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

use TSI_Client\Models;

if (!defined('TSI_DIR')) {
    define('TSI_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);
}

require_once TSI_DIR . 'TSI_Client_Base_Interface.php';
require_once TSI_DIR . 'TSI_Client_Base.php';
require_once TSI_DIR . 'TSI_Client_Interface.php';

class TSI_Client extends TSI_Client_Base implements TSI_Client_Interface {

    /**
     * TSI_Client constructor.
     * @param string $server_url
     * @param string $client_key
     * @param string $secret_key
     */
     function __construct(string $server_url = '', string $client_key = '', string $secret_key = '') {
         parent::__construct($server_url, $client_key, $secret_key);
		 
		 //Autoload
		$this->autoload('TSI_Instance','Models');
		$this->autoload('TSI_Properties','Models');
		$this->autoload('TSI_Resellers','Models');
		$this->autoload('TSI_Role','Models');
		$this->autoload('TSI_User','Models');
		$this->autoload('TSI_VServer','Models');
     }

    /**
     * TSI_Client deconstruct.
     */
     function __destruct() {
         parent::__destruct();
     }

    /**
     * Shows the version of Teamspeak Interface
     * @param int $cache
     * @return array|bool
     * @throws \Exception
     */
    public function getTSIVersion(int $cache = 30) {
        if($cache >= 1 && ($cache_data = $this->getCache('getTSIVersion'))) {
            return $cache_data;
        }

        if(!$this->checkAPI(!$cache)) {
            return false;
        }

        if(array_key_exists('tsi_main',$this->version)) {
            $data = [
                'name' => strval($this->version['tsi_main']['name']),
                'version' => strval($this->version['tsi_main']['version']),
                'last_update' => strval($this->version['tsi_main']['last_update']['date'])
            ];

            if ($cache >= 1) {
                $this->setCache('getTSIVersion', $data, $cache);
            }

            return $data;
        }

        return false;
    }

    /**
     * Shows the version of Server-API
     * @param int $cache
     * @return array|bool
     * @throws \Exception
     */
    public function getAPIVersion(int $cache = 30) {
        if($cache >= 1 && ($cache_data = $this->getCache('getAPIVersion'))) {
            return $cache_data;
        }

        if(!$this->checkAPI(!$cache)) {
            return false;
        }

        if(array_key_exists('modul_ai',$this->version)) {
            $data = [
                'name' => strval($this->version['modul_ai']['name']),
                'version' => strval($this->version['modul_ai']['version']),
                'last_update' => strval($this->version['modul_ai']['last_update']['date'])
            ];


            if ($cache >= 1) {
                $this->setCache('getAPIVersion', $data, $cache);
            }

            return $data;
        }

        return false;
    }

    /**
     * Shows the versions of the TSI-Extensions
     * @param string $addon_name
     * @param int $cache
     * @return array|bool
     * @throws \Exception
     */
    public function getAddonVersion(string $addon_name,int $cache = 30) {
        $cache_tag = 'getAddonVersion_'.$addon_name;
        if($cache >= 1 && ($cache_data = $this->getCache($cache_tag))) {
            return $cache_data;
        }

        if(!$this->checkAPI(!$cache) || $addon_name == 'tsi_main') {
            return false;
        }

        if(array_key_exists($addon_name,$this->version)) {
            $data = [
                'name' => strval($this->version[$addon_name]['name']),
                'version' => strval($this->version[$addon_name]['version']),
                'last_update' => strval($this->version[$addon_name]['last_update']['date'])
            ];

            if($cache >= 1) {
                $this->setCache($cache_tag,$data,$cache);
            }

            return $data;
        }

        return false;
    }

    /**
     * Returns a list of all addons
     * @param int $cache
     * @return array|bool
     * @throws \Exception
     */
    public function getAddons(int $cache = 30) {
        if($cache >= 1 && ($cache_data = $this->getCache('getAddons'))) {
            return $cache_data;
        }

        if(!$this->checkAPI(!$cache)) {
            return false;
        }

        $addons = (array)$this->version;
        unset($addons['tsi_main']); //Remove TSI

        if($cache >= 1) {
            $this->setCache('getAddons',$addons,$cache);
        }

        return $addons;
    }

    /**
     * Returns a list of TSI users
     * @return array|bool
     * @throws \Exception
     */
    public function getTSIUsers() {
        if(!$this->checkAPI()) {
            return false;
        }

        var_dump($this->version['modul_ai']['version']);
        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => getTSIUsers(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        $this->insertCall('usersGet'); //set the call
        $this->Exec(); //execute

        $return = [];
        $users = $this->getResponse();
        if(!$users) {
            trigger_error(__CLASS__.' => getTSIUsers(): Unknown answer!', E_USER_WARNING);
            return false;
        }

		$this->autoload('TSI_User','Models');
        foreach ($users as $key => $data) {
            if(count($data) <= 9) {
                trigger_error(__CLASS__.' => getTSIUsers(): response is empty or has invalid result!', E_USER_WARNING);
                return false;
            }

            $user->setUserID((int)$data['id']);
            $user->setResellerID((int)$data['reseller_id']);
            $user->setRoleID((int)$data['group_id']);
            $user->setUsername(html_entity_decode($data['username']));
            $user->setFirstName(html_entity_decode($data['first_name']));
            $user->setLastName(html_entity_decode($data['last_name']));
            $user->setEmail(html_entity_decode($data['email']));
            $user->setQueryNickname(html_entity_decode($data['query_nickname']));
            $user->setLanguage(html_entity_decode($data['lang']));
            $user->setFixedVMs($data['fixed_virtual_servers']);
            $user->setMaxSlotsVMs((int)$data['max_slots_per_virtualservers']);
            $user->setRegDateArray($data['reg_date']);
            $user->setActive(($data['active'] ? true : false));
            $user->setIcon(html_entity_decode($data['icon_pkg']));
            unset($data);

            $return[$key] = $user;
        }

        return $return;
    }

    /**
     * Give a TSI User Profile
     * @param int $user_id
     * @return Models\TSI_User|bool
     * @throws \Exception
     */
    public function getTSIUser(int $user_id) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => getTSIUser(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        if(!$user_id) {
            trigger_error(__CLASS__.' => getTSIUser(): User ID must be set!', E_USER_WARNING);
            return false;
        }

        $this->insertCall('userGet',['id'=>$user_id]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => getTSIUser(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        if(count($data) <= 9) {
            trigger_error(__CLASS__.' => getTSIUser(): response is empty or has invalid result!', E_USER_WARNING);
            return false;
        }

        $user = new Models\TSI_User(true);
        $user->setUserID((int)$data['id']);
        $user->setResellerID((int)$data['reseller_id']);
        $user->setRoleID((int)$data['group_id']);
        $user->setUsername(html_entity_decode($data['username']));
        $user->setFirstName(html_entity_decode($data['first_name']));
        $user->setLastName(html_entity_decode($data['last_name']));
        $user->setEmail(html_entity_decode($data['email']));
        $user->setQueryNickname(html_entity_decode($data['query_nickname']));
        $user->setLanguage(html_entity_decode($data['lang']));
        $user->setFixedVMs($data['fixed_virtual_servers']);
        $user->setMaxSlotsVMs((int)$data['max_slots_per_virtualservers']);
        $user->setRegDateArray($data['reg_date']);
        $user->setActive(($data['active'] ? true : false));
        $user->setIcon(html_entity_decode($data['icon_pkg']));
        unset($data);

        return $user;
    }

    /**
     * Find a TSI user by username
     * @param string $username
     * @return Models\TSI_User|bool
     * @throws \Exception
     */
    public function getTSIUserByUsername(string $username) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => getTSIUserByUsername(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        $username = utf8_encode(trim($username));
        $this->insertCall('userFindByUsername',['username'=>utf8_encode($username)]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => getTSIUserByUsername(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        if(count($data) <= 9) {
            trigger_error(__CLASS__.' => getTSIUserByUsername(): response is empty or has invalid result!', E_USER_WARNING);
            return false;
        }

        $user = new Models\TSI_User();
        $user->setUserID((int)$data['id']);
        $user->setResellerID((int)$data['reseller_id']);
        $user->setRoleID((int)$data['group_id']);
        $user->setUsername(html_entity_decode($data['username']));
        $user->setFirstName(html_entity_decode($data['first_name']));
        $user->setLastName(html_entity_decode($data['last_name']));
        $user->setEmail(html_entity_decode($data['email']));
        $user->setQueryNickname(html_entity_decode($data['query_nickname']));
        $user->setLanguage(html_entity_decode($data['lang']));
        $user->setFixedVMs($data['fixed_virtual_servers']);
        $user->setMaxSlotsVMs((int)$data['max_slots_per_virtualservers']);
        $user->setRegDateArray($data['reg_date']);
        $user->setActive(($data['active'] ? true : false));
        $user->setIcon(html_entity_decode($data['icon_pkg']));
        unset($data);

        return $user;
    }

    /**
     * Find TSI users based on the email address
     * @param string $email
     * @return Models\TSI_User|bool
     * @throws \Exception
     */
    public function getTSIUserByEMail(string $email) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => getTSIUserByEMail(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        $email = trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            trigger_error(__CLASS__.' => getTSIUserByEMail(): "'.$email.'" is not a valid email address!', E_USER_WARNING);
            return false;
        }

        $this->insertCall('userFindByEmail',['email'=>utf8_encode($email)]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => getTSIUserByEMail(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        if(count($data) <= 13) {
            trigger_error(__CLASS__.' => getTSIUserByEMail(): response is empty or has invalid result!', E_USER_WARNING);
            return false;
        }

        $user = new Models\TSI_User();
        $user->setUserID((int)$data['id']);
        $user->setResellerID((int)$data['reseller_id']);
        $user->setRoleID((int)$data['group_id']);
        $user->setUsername(html_entity_decode($data['username']));
        $user->setFirstName(html_entity_decode($data['first_name']));
        $user->setLastName(html_entity_decode($data['last_name']));
        $user->setEmail(html_entity_decode($data['email']));
        $user->setQueryNickname(html_entity_decode($data['query_nickname']));
        $user->setLanguage(html_entity_decode($data['lang']));
        $user->setFixedVMs($data['fixed_virtual_servers']);
        $user->setMaxSlotsVMs((int)$data['max_slots_per_virtualservers']);
        $user->setRegDateArray($data['reg_date']);
        $user->setActive(($data['active'] ? true : false));
        $user->setIcon(html_entity_decode($data['icon_pkg']));
        unset($data);

        return $user;
    }

    /**
     * @param Models\TSI_User $user
     * @return array|bool
     * @throws \Exception
     */
    public function addTSIUser(Models\TSI_User $user) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => addTSIUser(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        if(empty($user->init_pw)) {
            trigger_error(__CLASS__.' => addTSIUser(): Init-Password must be set!', E_USER_WARNING);
            return false;
        }

        if(empty($user->getUsername())) {
            trigger_error(__CLASS__.' => addTSIUser(): Username must be set!', E_USER_WARNING);
            return false;
        }

        if(empty($user->getFirstName())) {
            trigger_error(__CLASS__.' => addTSIUser(): FirstName must be set!', E_USER_WARNING);
            return false;
        }

        if(empty($user->getLastName())) {
            trigger_error(__CLASS__.' => addTSIUser(): LastName must be set!', E_USER_WARNING);
            return false;
        }

        if(empty($user->getEmail())) {
            trigger_error(__CLASS__.' => addTSIUser(): E-Mail must be set!', E_USER_WARNING);
            return false;
        }

        if(empty($user->getRoleID())) {
            trigger_error(__CLASS__.' => addTSIUser(): Role-ID must be set!', E_USER_WARNING);
            return false;
        }

        $data = [];
        $data['username'] = $user->getUsername();
        $data['group_id'] = $user->getRoleID();
        $data['init_pw'] = $user->init_pw;
        $data['first_name'] = $user->getFirstName();
        $data['last_name'] = $user->getLastName();
        $data['email'] = $user->getEmail();
        $data['lang'] = $user->getLanguage();
        $data['fixed_virtual_servers'] = $user->getFixedVMs();

        $this->insertCall('userAdd',['data'=>$data]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => addTSIUser(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        return $data;
    }

    /**
     * Edit a TSI user
     * @param Models\TSI_User $user
     * @return array|bool
     * @throws \Exception
     */
    public function editTSIUser(Models\TSI_User $user) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => editTSIUser(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        if(!$user->getUserID()) {
            trigger_error(__CLASS__.' => editTSIUser(): User-ID must be set!', E_USER_WARNING);
            return false;
        }

        if(empty($user->getUsername())) {
            trigger_error(__CLASS__.' => editTSIUser(): Username must be set!', E_USER_WARNING);
            return false;
        }

        if(empty($user->getFirstName())) {
            trigger_error(__CLASS__.' => editTSIUser(): FirstName must be set!', E_USER_WARNING);
            return false;
        }

        if(empty($user->getLastName())) {
            trigger_error(__CLASS__.' => editTSIUser(): LastName must be set!', E_USER_WARNING);
            return false;
        }

        if(empty($user->getEmail())) {
            trigger_error(__CLASS__.' => editTSIUser(): E-Mail must be set!', E_USER_WARNING);
            return false;
        }

        if(empty($user->getRoleID())) {
            trigger_error(__CLASS__.' => editTSIUser(): Role-ID must be set!', E_USER_WARNING);
            return false;
        }

        $data = [];
        $data['username'] = $user->getUsername();
        $data['role_id'] = $user->getRoleID();
        $data['init_pw'] = $user->init_pw;
        $data['first_name'] = $user->getFirstName();
        $data['last_name'] = $user->getLastName();
        $data['email'] = $user->getEmail();
        $data['lang'] = $user->getLanguage();
        $data['fixed_virtual_servers'] = $user->getFixedVMs();

        $this->insertCall('userModify',['id'=>$user->getUserID(), 'data'=>$data]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => editTSIUser(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        return $data;
    }

    /**
     * Delete TSI users based on user-id
     * @param Models\TSI_User $user
     * @return array|bool
     * @throws \Exception
     */
    public function deleteTSIUser(Models\TSI_User $user) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => deleteTSIUser(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        if(!$user->getUserID()) {
            trigger_error(__CLASS__.' => deleteTSIUser(): User-ID must be set!', E_USER_WARNING);
            return false;
        }

        $this->insertCall('userDel',['id'=>$user->getUserID()]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => deleteTSIUser(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        return $data;
    }

    /**
     * Get TSI role list
     * @return array|bool
     * @throws \Exception
     */
    public function getTSIRolesList() {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => getTSIRolesList(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        $this->insertCall('rolesGet'); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => getTSIRolesList(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        $roles = [];
        foreach ($data as $key => $perm) {
            $role = new Models\TSI_Role();
            $role->setID((int)$perm['id']);
            $role->setName(html_entity_decode($perm['name']));
            $role->setLevel((int)$perm['level']);
            $role->setIcon(html_entity_decode($perm['icon']));
            $role->setPermissions($perm['virtualserver_permissions']);
            $role->setModifys($perm['virtualserver_modify']);
            $role->setChannelModifys($perm['virtualserver_channel_modify']);
            $role->setTSIPermissions($perm['tsi_permissions']);
            $roles[$key] = $role;
        }

        return $roles;
    }

    /**
     * Get TSI role by id
     * @param int $role_id
     * @return Models\TSI_Role|bool
     * @throws \Exception
     */
    public function getTSIRole(int $role_id) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => getTSIRole(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        if(!$role_id) {
            trigger_error(__CLASS__.' => getTSIRole(): Role ID must be set!', E_USER_WARNING);
            return false;
        }

        $this->insertCall('roleGet',['id'=>$role_id]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => getTSIRole(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        $role = new Models\TSI_Role();
        $role->setID((int)$data['id']);
        $role->setName(html_entity_decode($data['name']));
        $role->setLevel((int)$data['level']);
        $role->setIcon(html_entity_decode($data['icon']));
        $role->setPermissions($data['virtualserver_permissions']);
        $role->setModifys($data['virtualserver_modify']);
        $role->setChannelModifys($data['virtualserver_channel_modify']);
        $role->setTSIPermissions($data['tsi_permissions']);

        return $role;
    }

    /**
     * Find TSI role by name
     * @param string $name
     * @return Models\TSI_Role|bool
     * @throws \Exception
     */
    public function getTSIRoleByName(string $name) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => getTSIRoleByName(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        if(empty($name)) {
            trigger_error(__CLASS__.' => getTSIRoleByName(): Role Name must be set!', E_USER_WARNING);
            return false;
        }

        $this->insertCall('roleFindByName',['name'=>$name]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => getTSIRoleByName(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        $role = new Models\TSI_Role();
        $role->setID((int)$data['id']);
        $role->setName(html_entity_decode($data['name']));
        $role->setLevel((int)$data['level']);
        $role->setIcon(html_entity_decode($data['icon']));
        $role->setPermissions($data['virtualserver_permissions']);
        $role->setModifys($data['virtualserver_modify']);
        $role->setChannelModifys($data['virtualserver_channel_modify']);
        $role->setTSIPermissions($data['tsi_permissions']);

        return $role;
    }

    /**
     * Delete TSI role
     * @param Models\TSI_Role $role
     * @return array|bool
     * @throws \Exception
     */
    public function deleteTSIRole(Models\TSI_Role $role) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => deleteTSIRole(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        if(!$role->getID()) {
            trigger_error(__CLASS__.' => deleteTSIRole(): Role ID must be set!', E_USER_WARNING);
            return false;
        }

        $this->insertCall('roleDel',['id'=>$role->getID()]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => deleteTSIRole(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        return $data;
    }

    /**
     * Get TS instance list
     * @return array|bool
     * @throws \Exception
     */
    public function getTSInstanceList()
    {
        if (!$this->checkAPI()) {
            return false;
        }

        if (version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__ . ' => getTSInstanceList(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        $this->insertCall('instancesGet'); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if (!$data) {
            trigger_error(__CLASS__ . ' => getTSInstanceList(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        $instances = [];
        foreach ($data as $key => $instance_data) {
            $instance = new Models\TSI_Instance();
            $instance->setID((int)$instance_data['id']);
            $instance->setIP(strval($instance_data['server_ip']));
            $instance->setLastPermImport(strval($instance_data['last_perm_import']));
            $instance->setQueryPort((int)$instance_data['query_port']);
            $instance->setServerAdmin(html_entity_decode($instance_data['serveradmin']));
            $instances[$key] = $instance;
        }

        return $instances;
    }

    /**
     * Get TS instance data
     * @param int $instance_id
     * @return Models\TSI_Instance|bool
     * @throws \Exception
     */
    public function getTSInstance(int $instance_id) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => getTSInstance(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        if(!$instance_id) {
            trigger_error(__CLASS__.' => getTSInstance(): Instance ID must be set!', E_USER_WARNING);
            return false;
        }

        $this->insertCall('instanceGet',['id'=>$instance_id]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => getTSInstance(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        $instance = new Models\TSI_Instance();
        $instance->setID((int)$data['id']);
        $instance->setIP(strval($data['server_ip']));
        $instance->setLastPermImport(strval($data['last_perm_import']));
        $instance->setQueryPort((int)$data['query_port']);
        $instance->setServerAdmin(html_entity_decode($data['serveradmin']));
        return $instance;
    }

    /**
     * Get TS instance data by IP
     * @param string $ip
     * @return Models\TSI_Instance|bool
     * @throws \Exception
     */
    public function getTSInstanceByIP(string $ip) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => getTSInstanceByIP(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        if(!filter_var($ip, FILTER_VALIDATE_IP)) {
            trigger_error(__CLASS__.' => getTSInstanceByIP(): A valid IP must be set!', E_USER_WARNING);
            return false;
        }

        $this->insertCall('instanceFindByIp',['ip'=>$ip]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => getTSInstanceByIP(): Unknown answer!', E_USER_WARNING);
            return false;
        }
		
        $instance = new Models\TSI_Instance();
        $instance->setID((int)$data['id']);
        $instance->setIP(strval($data['server_ip']));
        $instance->setLastPermImport(strval($data['last_perm_import']));
        $instance->setQueryPort((int)$data['query_port']);
        $instance->setServerAdmin(html_entity_decode($data['serveradmin']));
        return $instance;
    }

    /**
     * Delete TS instance
     * @param Models\TSI_Instance $instance
     * @return array|bool
     * @throws \Exception
     */
    public function deleteTSInstance(Models\TSI_Instance $instance) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => deleteTSInstance(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        if(!$instance->getID()) {
            trigger_error(__CLASS__.' => deleteTSInstance(): Instance ID must be set!', E_USER_WARNING);
            return false;
        }

        $this->insertCall('instanceDel',['id'=>$instance->getID()]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => deleteTSInstance(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        return $data;
    }

    /**
     * Get list of virtual servers of an instance
     * @param int $instance_id
     * @return array|bool
     * @throws \Exception
     */
    public function getTSVServerList(int $instance_id) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => getTSVServerList(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        if(!$instance_id) {
            trigger_error(__CLASS__.' => getTSVServerList(): Instance ID must be set!', E_USER_WARNING);
            return false;
        }

        $this->insertCall('instanceGetServerList',['id'=>$instance_id]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => getTSVServerList(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        $servers = [];
        foreach ($data as $id => $srv) {
            $properties = new Models\TSI_Properties();
            $properties->setName(html_entity_decode($srv['name']));
            $properties->setMaxClients((int)$srv['maxclients']);

            $vserver = new Models\TSI_VServer();
            $vserver->setProperties($properties);
            $vserver->setServerID($id);
            $vserver->setInstanceID($instance_id);
            $vserver->setUID(html_entity_decode($srv['unique_id']));
            $vserver->setOnline(strval($srv['status']));
            $vserver->setPlatform(html_entity_decode($srv['platform']));
            $vserver->setVersion(html_entity_decode($srv['version']));
            $vserver->setClientsOnline((int)$srv['clientsonline']);
            $vserver->setChannelOnline((int)$srv['channelsonline']);
            $vserver->setCreatedTime((int)$srv['created']);
            $vserver->setUptime((int)$srv['uptime']);
            $servers[$id] = $vserver;
        }

        return $servers;
    }

    /**
     * Retrieve data from a virtual server
     * @param int $instance_id
     * @param int $vserver_id
     * @return Models\TSI_VServer|bool
     * @throws \Exception
     */
    public function getTSVServer(int $instance_id, int $vserver_id) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => getTSVServer(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        if(!$instance_id) {
            trigger_error(__CLASS__.' => getTSVServer(): Instance ID must be set!', E_USER_WARNING);
            return false;
        }

        if(!$vserver_id) {
            trigger_error(__CLASS__.' => getTSVServer(): Virtual-Server-ID must be set!', E_USER_WARNING);
            return false;
        }

        $this->insertCall('vServerGet',[
        'id'=>$instance_id,
            'sid'=>$vserver_id]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => getTSVServer(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        $properties = new Models\TSI_Properties();
        $properties->setName(html_entity_decode($data['name']));
        $properties->setMaxClients((int)$data['maxclients']);

        $vserver = new Models\TSI_VServer();
        $vserver->setProperties($properties);
        $vserver->setServerID($vserver_id);
        $vserver->setInstanceID($instance_id);
        $vserver->setUID(html_entity_decode($data['unique_id']));
        $vserver->setOnline(strval($data['status']));
        $vserver->setPlatform(html_entity_decode($data['platform']));
        $vserver->setVersion(html_entity_decode($data['version']));
        $vserver->setClientsOnline((int)$data['clientsonline']);
        $vserver->setChannelOnline((int)$data['channelsonline']);
        $vserver->setCreatedTime((int)$data['created']);
        $vserver->setUptime((int)$data['uptime']);
        return $vserver;
    }

    /**
     * Create a virtual server
     * @param Models\TSI_VServer $vserver
     * @return array|bool
     * @throws \Exception
     */
    public function addTSVServer(Models\TSI_VServer $vserver) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => addTSVServer(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        if(!$vserver->getInstanceID()) {
            trigger_error(__CLASS__.' => addTSVServer(): Instance ID must be set!', E_USER_WARNING);
            return false;
        }

        if(empty($vserver->getProperties()->getName())) {
            trigger_error(__CLASS__.' => addTSVServer(): Properties:ServerName must be set!', E_USER_WARNING);
            return false;
        }

        if(empty($vserver->getProperties()->getMaxClients())) {
            trigger_error(__CLASS__.' => addTSVServer(): Properties:MaxClients must be set!', E_USER_WARNING);
            return false;
        }

        $options = [];
        $options['id'] = $vserver->getInstanceID();

        $options['properties'] = $vserver->getProperties()->virtualserver_options;
        $this->insertCall('vServerCreate',$options); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => addTSVServer(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        return $data;
    }

    /**
     * Change virtual server
     * @param Models\TSI_VServer $vserver
     * @return array|bool
     * @throws \Exception
     */
    public function editTSVServer(Models\TSI_VServer $vserver) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => editTSVServer(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        if(!$vserver->getInstanceID()) {
            trigger_error(__CLASS__.' => editTSVServer(): Instance ID must be set!', E_USER_WARNING);
            return false;
        }

        if(!$vserver->getServerID()) {
            trigger_error(__CLASS__.' => editTSVServer(): Virtual-Server-ID must be set!', E_USER_WARNING);
            return false;
        }

        if(empty($vserver->getProperties()->getName())) {
            trigger_error(__CLASS__.' => editTSVServer(): Properties:ServerName must be set!', E_USER_WARNING);
            return false;
        }

        if(empty($vserver->getProperties()->getMaxClients())) {
            trigger_error(__CLASS__.' => editTSVServer(): Properties:MaxClients must be set!', E_USER_WARNING);
            return false;
        }

        $options = [];
        $options['id'] = $vserver->getInstanceID();
        $options['sid'] = $vserver->getServerID();

        $options['properties'] = $vserver->getProperties()->virtualserver_options;
        $this->insertCall('vServerModify',$options); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => editTSVServer(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        return $data;
    }

    /**
     * Delete virtual server
     * @param Models\TSI_VServer $vserver
     * @return array|bool
     * @throws \Exception
     */
    public function deleteTSVServer(Models\TSI_VServer $vserver) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => deleteTSVServer(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        if(!$vserver->getInstanceID()) {
            trigger_error(__CLASS__.' => deleteTSVServer(): Instance ID must be set!', E_USER_WARNING);
            return false;
        }

        if(!$vserver->getServerID()) {
            trigger_error(__CLASS__.' => deleteTSVServer(): Virtual-Server-ID must be set!', E_USER_WARNING);
            return false;
        }

        $this->insertCall('vServerDelete',['id'=>$vserver->getInstanceID(), 'sid'=>$vserver->getServerID()]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => deleteTSVServer(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        return $data;
    }

    /**
     * Start virtual server
     * @param Models\TSI_VServer $vserver
     * @return array|bool
     * @throws \Exception
     */
    public function startTSVServer(Models\TSI_VServer $vserver) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => startTSVServer(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        if(!$vserver->getInstanceID()) {
            trigger_error(__CLASS__.' => startTSVServer(): Instance ID must be set!', E_USER_WARNING);
            return false;
        }

        if(!$vserver->getServerID()) {
            trigger_error(__CLASS__.' => startTSVServer(): Virtual-Server-ID must be set!', E_USER_WARNING);
            return false;
        }

        $this->insertCall('vServerStart',['id'=>$vserver->getInstanceID(), 'sid'=>$vserver->getServerID()]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => startTSVServer(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        return $data;
    }

    /**
     * Stop virtual server
     * @param Models\TSI_VServer $vserver
     * @return array|bool
     * @throws \Exception
     */
    public function stopTSVServer(Models\TSI_VServer $vserver) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => stopTSVServer(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        if(!$vserver->getInstanceID()) {
            trigger_error(__CLASS__.' => stopTSVServer(): Instance ID must be set!', E_USER_WARNING);
            return false;
        }

        if(!$vserver->getServerID()) {
            trigger_error(__CLASS__.' => stopTSVServer(): Virtual-Server-ID must be set!', E_USER_WARNING);
            return false;
        }

        $this->insertCall('vServerStop',['id'=>$vserver->getInstanceID(), 'sid'=>$vserver->getServerID()]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => stopTSVServer(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        return $data;
    }

    /**
     * Call Teamspeak Viewer
     * @param int $instance_id
     * @param int $vserver_id
     * @param bool $show_flags
     * @param string $style
     * @param int $cache
     * @return array|bool
     * @throws \Exception
     */
    public function getTSViewer(int $instance_id, int $vserver_id,bool $show_flags = true, string $style = 'old',int $cache = 30) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => getTSViewer(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        if(!$instance_id) {
            trigger_error(__CLASS__.' => getTSViewer(): Instance ID must be set!', E_USER_WARNING);
            return false;
        }

        if(!$vserver_id) {
            trigger_error(__CLASS__.' => getTSViewer(): Virtual-Server-ID must be set!', E_USER_WARNING);
            return false;
        }

        $this->insertCall('vServerViewGet',[
            'id'=>$instance_id,
            'sid'=>$vserver_id,
            'flags' => $show_flags,
            'style' => $style
        ]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => getTSViewer(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        return $data;
    }

    /**
     * Cronjobs abarbeiten
     * @return array|bool
     * @throws \Exception
     */
    public function runTSICron() {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => runTSICron(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        $this->insertCall('runCron'); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => runTSICron(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        return $data;
    }

    /**
     * Check if Simple Bot is active
     * @param int $instance_id
     * @param int $vserver_id
     * @return array|bool
     * @throws \Exception
     */
    public function isTSIBotRun(int $instance_id, int $vserver_id) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => isTSIBotRun(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        $addon_versions = $this->getAddonVersion('modul_bt');
        if(!$addon_versions || version_compare($addon_versions['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => isTSIBotRun(): Requires version "1.0.20" of the Simple Bots!', E_USER_WARNING);
            return false;
        }

        if(!$instance_id) {
            trigger_error(__CLASS__.' => isTSIBotRun(): Instance ID must be set!', E_USER_WARNING);
            return false;
        }

        if(!$vserver_id) {
            trigger_error(__CLASS__.' => isTSIBotRun(): Virtual-Server-ID must be set!', E_USER_WARNING);
            return false;
        }

        $this->insertCall('simpleBotIsRunning',[
            'id'=>$instance_id,
            'sid'=>$vserver_id,
        ]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => isTSIBotRun(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        return $data;
    }

    /**
     * Start Simple Bot
     * @param int $instance_id
     * @param int $vserver_id
     * @param string $language
     * @return array|bool
     * @throws \Exception
     */
    public function startTSIBot(int $instance_id, int $vserver_id, string $language = 'de_DE') {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => startTSIBot(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        $addon_versions = $this->getAddonVersion('modul_bt');
        if(!$addon_versions || version_compare($addon_versions['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => startTSIBot(): Requires version "1.0.20" of the Simple Bots!', E_USER_WARNING);
            return false;
        }

        if(!$instance_id) {
            trigger_error(__CLASS__.' => startTSIBot(): Instance ID must be set!', E_USER_WARNING);
            return false;
        }

        if(!$vserver_id) {
            trigger_error(__CLASS__.' => startTSIBot(): Virtual-Server-ID must be set!', E_USER_WARNING);
            return false;
        }

        $this->insertCall('simpleBotRun',[
            'id'=>$instance_id,
            'sid'=>$vserver_id,
            'lang' => $language,
        ]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => startTSIBot(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        return $data;
    }

    /**
     * Stop Simple Bot
     * @param int $instance_id
     * @param int $vserver_id
     * @return array|bool
     * @throws \Exception
     */
    public function stopTSIBot(int $instance_id, int $vserver_id) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => stopTSIBot(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        $addon_versions = $this->getAddonVersion('modul_bt');
        if(!$addon_versions || version_compare($addon_versions['version'], '1.0.20', '<')) {
            trigger_error(__CLASS__.' => stopTSIBot(): Requires version "1.0.20" of the Simple Bots!', E_USER_WARNING);
            return false;
        }

        if(!$instance_id) {
            trigger_error(__CLASS__.' => stopTSIBot(): Instance ID must be set!', E_USER_WARNING);
            return false;
        }

        if(!$vserver_id) {
            trigger_error(__CLASS__.' => stopTSIBot(): Virtual-Server-ID must be set!', E_USER_WARNING);
            return false;
        }

        $this->insertCall('simpleBotStop',[
            'id'=>$instance_id,
            'sid'=>$vserver_id
        ]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => stopTSIBot(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        return $data;
    }

    /**
     * @param int $reseller_id
     * @return bool|TSI_Resellers
     * @throws \Exception
     */
    public function getTSVReseller(int $reseller_id) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => getTSVReseller(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        if(!$reseller_id) {
            trigger_error(__CLASS__.' => getTSVReseller(): Reseller-ID must be set!', E_USER_WARNING);
            return false;
        }

        $this->insertCall('resellerGet',['id'=>$reseller_id]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => getTSVReseller(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        if(count($data) <= 16) {
            trigger_error(__CLASS__.' => getTSVReseller(): response is empty or has invalid result!', E_USER_WARNING);
            return false;
        }

        $reseller = new Models\TSI_Resellers();
        $reseller->setUserID((int)$data['id']);
        $reseller->setRoleID((int)$data['group_id']);
        $reseller->setUsername(html_entity_decode($data['username']));
        $reseller->setFirstName(html_entity_decode($data['first_name']));
        $reseller->setLastName(html_entity_decode($data['last_name']));
        $reseller->setEmail(html_entity_decode($data['email']));
        $reseller->setQueryNickname(html_entity_decode($data['query_nickname']));
        $reseller->setLanguage(html_entity_decode($data['lang']));
        $reseller->setFixedVMs($data['fixed_virtual_servers']);
        $reseller->setLimits([
            'max_slots_per_virtualservers' => (int)$data['max_slots_per_virtualservers'],
            'max_web_users' => (int)$data['max_web_users'],
            'max_virtualservers' => (int)$data['max_virtualservers']
        ]);
        $reseller->setAllowedOwnInstances(($data['allowed_own_instances'] == 1));
        $reseller->setRegDateArray($data['reg_date']);
        $reseller->setActive(($data['active'] ? true : false));
        $reseller->setIcon(html_entity_decode($data['icon_pkg']));
        unset($data);

        return $reseller;
    }

    /**
     * @param string $username
     * @return bool|TSI_Resellers
     * @throws \Exception
     */
    public function getTSVResellerByUsername(string $username) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => getTSVResellerByUsername(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        if(empty($username)) {
            trigger_error(__CLASS__.' => getTSVResellerByUsername(): Reseller-Username must be set!', E_USER_WARNING);
            return false;
        }

        $this->insertCall('resellerFindByUsername',['username'=>$username]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => getTSVResellerByUsername(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        if(count($data) <= 16) {
            trigger_error(__CLASS__.' => getTSVResellerByUsername(): response is empty or has invalid result!', E_USER_WARNING);
            return false;
        }

        $reseller = new Models\TSI_Resellers();
        $reseller->setUserID((int)$data['id']);
        $reseller->setRoleID((int)$data['group_id']);
        $reseller->setUsername(html_entity_decode($data['username']));
        $reseller->setFirstName(html_entity_decode($data['first_name']));
        $reseller->setLastName(html_entity_decode($data['last_name']));
        $reseller->setEmail(html_entity_decode($data['email']));
        $reseller->setQueryNickname(html_entity_decode($data['query_nickname']));
        $reseller->setLanguage(html_entity_decode($data['lang']));
        $reseller->setFixedVMs($data['fixed_virtual_servers']);
        $reseller->setLimits([
            'max_slots_per_virtualservers' => (int)$data['max_slots_per_virtualservers'],
            'max_web_users' => (int)$data['max_web_users'],
            'max_virtualservers' => (int)$data['max_virtualservers']
        ]);
        $reseller->setAllowedOwnInstances(($data['allowed_own_instances'] == 1));
        $reseller->setRegDateArray($data['reg_date']);
        $reseller->setActive(($data['active'] ? true : false));
        $reseller->setIcon(html_entity_decode($data['icon_pkg']));
        unset($data);

        return $reseller;
    }

    /**
     * @param string $reseller_email
     * @return bool|TSI_Resellers
     * @throws \Exception
     */
    public function getTSVResellerByEmail(string $reseller_email) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => getTSVResellerByEmail(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        if(empty($reseller_email)) {
            trigger_error(__CLASS__.' => getTSVResellerByEmail(): Reseller-Username must be set!', E_USER_WARNING);
            return false;
        }

        $this->insertCall('resellerFindByEmail',['email'=>$reseller_email]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => getTSVResellerByEmail(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        if(count($data) <= 16) {
            trigger_error(__CLASS__.' => getTSVResellerByEmail(): response is empty or has invalid result!', E_USER_WARNING);
            return false;
        }

        $reseller = new Models\TSI_Resellers();
        $reseller->setUserID((int)$data['id']);
        $reseller->setRoleID((int)$data['group_id']);
        $reseller->setUsername(html_entity_decode($data['username']));
        $reseller->setFirstName(html_entity_decode($data['first_name']));
        $reseller->setLastName(html_entity_decode($data['last_name']));
        $reseller->setEmail(html_entity_decode($data['email']));
        $reseller->setQueryNickname(html_entity_decode($data['query_nickname']));
        $reseller->setLanguage(html_entity_decode($data['lang']));
        $reseller->setFixedVMs($data['fixed_virtual_servers']);
        $reseller->setLimits([
            'max_slots_per_virtualservers' => (int)$data['max_slots_per_virtualservers'],
            'max_web_users' => (int)$data['max_web_users'],
            'max_virtualservers' => (int)$data['max_virtualservers']
        ]);
        $reseller->setAllowedOwnInstances(($data['allowed_own_instances'] == 1));
        $reseller->setRegDateArray($data['reg_date']);
        $reseller->setActive(($data['active'] ? true : false));
        $reseller->setIcon(html_entity_decode($data['icon_pkg']));
        unset($data);

        return $reseller;
    }

    /**
     * @return bool|array
     * @throws \Exception
     */
    public function getTSVResellers() {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => getTSVResellers(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        $this->insertCall('resellersGet'); //set the call
        $this->Exec(); //execute

        $return = [];
        $resellers = $this->getResponse();
        if(!$resellers) {
            trigger_error(__CLASS__.' => getTSVResellers(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        foreach ($resellers as $key => $data) {
            if(count($data) <= 16) {
                trigger_error(__CLASS__.' => getTSVResellers(): response is empty or has invalid result!', E_USER_WARNING);
                return false;
            }

            $reseller = new Models\TSI_Resellers();
            $reseller->setUserID((int)$data['id']);
            $reseller->setRoleID((int)$data['group_id']);
            $reseller->setUsername(html_entity_decode($data['username']));
            $reseller->setFirstName(html_entity_decode($data['first_name']));
            $reseller->setLastName(html_entity_decode($data['last_name']));
            $reseller->setEmail(html_entity_decode($data['email']));
            $reseller->setQueryNickname(html_entity_decode($data['query_nickname']));
            $reseller->setLanguage(html_entity_decode($data['lang']));
            $reseller->setFixedVMs($data['fixed_virtual_servers']);
            $reseller->setLimits([
                'max_slots_per_virtualservers' => (int)$data['max_slots_per_virtualservers'],
                'max_web_users' => (int)$data['max_web_users'],
                'max_virtualservers' => (int)$data['max_virtualservers']
            ]);
            $reseller->setAllowedOwnInstances(($data['allowed_own_instances'] == 1));
            $reseller->setRegDateArray($data['reg_date']);
            $reseller->setActive(($data['active'] ? true : false));
            $reseller->setIcon(html_entity_decode($data['icon_pkg']));
            unset($data);

            $return[$key] = $reseller;
        }

        return $return;
    }

    /**
     * @param Models\TSI_Resellers $reseller
     * @return bool
     * @throws \Exception
     */
    public function addTSVReseller(Models\TSI_Resellers $reseller) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => addTSVReseller(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        if(empty($reseller->init_pw)) {
            trigger_error(__CLASS__.' => addTSVReseller(): Init-Password must be set!', E_USER_WARNING);
            return false;
        }

        if(empty($reseller->getUsername())) {
            trigger_error(__CLASS__.' => addTSVReseller(): Username must be set!', E_USER_WARNING);
            return false;
        }

        if(empty($reseller->getFirstName())) {
            trigger_error(__CLASS__.' => addTSVReseller(): FirstName must be set!', E_USER_WARNING);
            return false;
        }

        if(empty($reseller->getLastName())) {
            trigger_error(__CLASS__.' => addTSVReseller(): LastName must be set!', E_USER_WARNING);
            return false;
        }

        if(empty($reseller->getEmail())) {
            trigger_error(__CLASS__.' => addTSVReseller(): E-Mail must be set!', E_USER_WARNING);
            return false;
        }

        if(empty($reseller->getRoleID())) {
            trigger_error(__CLASS__.' => addTSVReseller(): Role-ID must be set!', E_USER_WARNING);
            return false;
        }

        $data = [];
        $data['username'] = $reseller->getUsername();
        $data['group_id'] = $reseller->getRoleID();
        $data['init_pw'] = $reseller->init_pw;
        $data['first_name'] = $reseller->getFirstName();
        $data['last_name'] = $reseller->getLastName();
        $data['email'] = $reseller->getEmail();
        $data['lang'] = $reseller->getLanguage();
        $data['fixed_instances'] = $reseller->getFixedInstances();
        $data['fixed_virtual_servers'] = $reseller->getFixedVMs();
        $data['allowed_own_instances'] = ($reseller->getAllowedOwnInstances() ? 1 : 0);

        $this->insertCall('resellerAdd',['data'=>$data]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => addTSVReseller(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        return $data;
    }

    /**
     * @param Models\TSI_Resellers $reseller
     * @return array|bool
     * @throws \Exception
     */
    public function editTSVReseller(Models\TSI_Resellers $reseller) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => editTSVReseller(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        if(empty($reseller->init_pw)) {
            trigger_error(__CLASS__.' => editTSVReseller(): Init-Password must be set!', E_USER_WARNING);
            return false;
        }

        if(empty($reseller->getUsername())) {
            trigger_error(__CLASS__.' => editTSVReseller(): Username must be set!', E_USER_WARNING);
            return false;
        }

        if(empty($reseller->getFirstName())) {
            trigger_error(__CLASS__.' => editTSVReseller(): FirstName must be set!', E_USER_WARNING);
            return false;
        }

        if(empty($reseller->getLastName())) {
            trigger_error(__CLASS__.' => editTSVReseller(): LastName must be set!', E_USER_WARNING);
            return false;
        }

        if(empty($reseller->getEmail())) {
            trigger_error(__CLASS__.' => editTSVReseller(): E-Mail must be set!', E_USER_WARNING);
            return false;
        }

        if(empty($reseller->getRoleID())) {
            trigger_error(__CLASS__.' => editTSVReseller(): Role-ID must be set!', E_USER_WARNING);
            return false;
        }

        $data = [];
        $data['username'] = $reseller->getUsername();
        $data['group_id'] = $reseller->getRoleID();
        $data['init_pw'] = $reseller->init_pw;
        $data['first_name'] = $reseller->getFirstName();
        $data['last_name'] = $reseller->getLastName();
        $data['email'] = $reseller->getEmail();
        $data['lang'] = $reseller->getLanguage();
        $data['fixed_instances'] = $reseller->getFixedInstances();
        $data['fixed_virtual_servers'] = $reseller->getFixedVMs();
        $data['allowed_own_instances'] = ($reseller->getAllowedOwnInstances() ? 1 : 0);

        $this->insertCall('resellerModify',['data'=>$data]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => editTSVReseller(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        return $data;
    }

    /**
     * @param Models\TSI_Resellers $reseller
     * @return array|bool
     * @throws \Exception
     */
    public function deleteTSVReseller(Models\TSI_Resellers $reseller) {
        if(!$this->checkAPI()) {
            return false;
        }

        if(version_compare($this->version['modul_ai']['version'], '1.1.0', '<')) {
            trigger_error(__CLASS__.' => deleteTSVReseller(): Requires version "1.1.0" of the TSI-API interface!', E_USER_WARNING);
            return false;
        }

        if(empty($reseller->getUserID()) || !$reseller->getUserID()) {
            trigger_error(__CLASS__.' => deleteTSVReseller(): User-ID must be set!', E_USER_WARNING);
            return false;
        }

        $data = [];
        $data['id'] = $reseller->getUserID();

        $this->insertCall('resellerDel',['data'=>$data]); //set the call
        $this->Exec(); //execute

        $data = $this->getResponse();
        if(!$data) {
            trigger_error(__CLASS__.' => deleteTSVReseller(): Unknown answer!', E_USER_WARNING);
            return false;
        }

        return $data;
    }
}
