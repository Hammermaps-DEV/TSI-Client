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

class TSI_Role implements TSI_Role_Interface {
    private $id = 0;
    private $name = '';
    private $level = 0;
    private $icon = '';
    private $virtualserver_permissions = [];
    private $virtualserver_modify = [];
    private $virtualserver_channel_modify = [];
    private $tsi_permissions = [];

    /**
     * @param int $id
     */
    public function setID(int $id) {
        if(!$id) {
            trigger_error(__CLASS__.' => setID(): ID must be set!', E_USER_WARNING);
            return;
        }

        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getID() {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName(string $name) {
        if(empty($name)) {
            trigger_error(__CLASS__.' => setName(): Name must be set!', E_USER_WARNING);
            return;
        }

        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param int $level
     */
    public function setLevel(int $level) {
        if($level > 100) {
            trigger_error(__CLASS__.' => setLevel(): The level must be less than 100!', E_USER_WARNING);
            return;
        }

        if($level <= 100)
            $this->level = $level;
    }

    /**
     * @return int
     */
    public function getLevel() {
        return $this->level;
    }

    /**
     * @param string $icon
     */
    public function setIcon(string $icon) {
        $this->icon = $icon;
    }

    /**
     * @return string
     */
    public function getIcon() {
        return $this->icon;
    }

    /**
     * @param array $permissions
     */
    public function setPermissions(array $permissions) {
        $this->virtualserver_permissions = $permissions;
    }

    /**
     * @param string $permission
     * @param bool $granted
     */
    public function setPermission(string $permission,bool $granted = false) {
        if(empty($permission)) {
            trigger_error(__CLASS__.' => getPermission(): Permission must be set!', E_USER_WARNING);
            return;
        }

        foreach ($this->getPermissionsList() as $permissons_list) {
            foreach ($permissons_list as $permisson_check) {
                if(strtolower($permisson_check) == strtolower($permission)) {
                    $this->virtualserver_permissions[$permission] = ($granted ? 1 : 0);
                    return;
                }
            }
        }

        trigger_error(__CLASS__.' => setModify(): Permission not set, "'.
            $permission.'"" was not found', E_USER_WARNING);
    }

    /**
     * @param string $permission
     * @return bool
     */
    public function getPermission(string $permission) {
        if(empty($permission)) {
            trigger_error(__CLASS__.' => getPermission(): Permission must be set!', E_USER_WARNING);
            return false;
        }

        foreach ($this->getPermissionsList() as $permissons_list) {
            foreach ($permissons_list as $permisson_check) {
                if(strtolower($permisson_check) == strtolower($permission)) {
                    if($this->getLevel() === 100)
                        return true;

                    if(array_key_exists($permission,$this->virtualserver_permissions))
                        return ($this->virtualserver_permissions[$permission] == 1);

                    return false;
                }
            }
        }

        trigger_error(__CLASS__.' => getPermission(): Permission not set, "'.
            $permission.'"" was not found', E_USER_WARNING);

        return false;
    }

    /**
     * @return array
     */
    public function getPermissions() {
        return $this->virtualserver_permissions;
    }

    /**
     * @return array
     */
    public function getPermissionsList() {
        $groups = [];
        foreach (TSI_Role_Interface::virtualserver_permissions as $group => $permissionsList) {
            $permissions = [];
            foreach ($permissionsList as $permission) {
                $permissions[$permission] = 0;
            }

            $groups[$group] = $permissions;
        }

        return $groups;
    }

    /**
     * @param array $modify
     */
    public function setModifys(array $modify) {
        $this->virtualserver_modify = $modify;
    }

    /**
     * @param string $permission
     * @param bool $granted
     */
    public function setModify(string $permission,bool $granted = false) {
        if(empty($permission)) {
            trigger_error(__CLASS__.' => setModify(): Permission must be set!', E_USER_WARNING);
            return;
        }

        if(in_array($permission,TSI_Client_Base_Interface::virtualserver_modify)) {
            $this->virtualserver_modify[$permission] = ($granted ? 1 : 0);
            return;
        }

        trigger_error(__CLASS__.' => setModify(): Permission not set, "'.
            $permission.'"" was not found', E_USER_WARNING);
    }

    /**
     * @param string $permission
     * @return bool
     */
    public function getModify(string $permission) {
        if(empty($permission)) {
            trigger_error(__CLASS__.' => getModify(): Permission must be set!', E_USER_WARNING);
            return false;
        }

        if(in_array($permission,TSI_Client_Base_Interface::virtualserver_modify)) {
            if($this->getLevel() === 100)
                return true;

            if(array_key_exists($permission,$this->virtualserver_modify))
                return ($this->virtualserver_modify[$permission] == 1);

            return false;
        }

        trigger_error(__CLASS__.' => getModify(): Permission not set, "'.
            $permission.'"" was not found', E_USER_WARNING);

        return false;
    }

    /**
     * @return array
     */
    public function getModifys() {
        return $this->virtualserver_modify;
    }

    /**
     * @return array
     */
    public function getModifyList() {
        $permissions = [];
        foreach (TSI_Client_Base_Interface::virtualserver_modify as $permission) {
            $permissions[$permission] = TSI_Role_Interface::virtualserver_modify_desc[$permission];
        }

        return $permissions;
    }

    /**
     * @param array $channel_modify
     */
    public function setChannelModifys(array $channel_modify) {
        $this->virtualserver_channel_modify = $channel_modify;
    }

    /**
     * @param string $permission
     * @param bool $granted
     */
    public function setChannelModify(string $permission,bool $granted = false) {
        if(empty($permission)) {
            trigger_error(__CLASS__.' => getChannelModify(): Permission must be set!', E_USER_WARNING);
            return;
        }

        if(in_array($permission,TSI_Role_Interface::virtualserver_channel_modify)) {
            $this->virtualserver_channel_modify[$permission] = ($granted ? 1 : 0);
            return;
        }

        trigger_error(__CLASS__.' => setChannelModify(): Permission not set, "'.
            $permission.'"" was not found', E_USER_WARNING);
    }

    /**
     * @param string $permission
     * @return bool
     */
    public function getChannelModify(string $permission) {
        if(empty($permission)) {
            trigger_error(__CLASS__.' => getChannelModify(): Permission must be set!', E_USER_WARNING);
            return false;
        }

        if(in_array($permission,TSI_Role_Interface::virtualserver_channel_modify)) {
            if($this->getLevel() === 100)
                return true;

            if(array_key_exists($permission,$this->virtualserver_channel_modify))
                return ($this->virtualserver_channel_modify[$permission] == 1);

            return false;
        }

        trigger_error(__CLASS__.' => getChannelModify(): Permission not set, "'.
            $permission.'"" was not found', E_USER_WARNING);

        return false;
    }

    /**
     * @return array
     */
    public function getChannelModifys() {
        return $this->virtualserver_channel_modify;
    }

    /**
     * @return array
     */
    public function getChannelModifyList() {
        $permissions = [];
        foreach (TSI_Role_Interface::virtualserver_channel_modify as $permission) {
            $permissions[$permission] = TSI_Role_Interface::virtualserver_channel_modify_desc[$permission];
        }

        return $permissions;
    }

    /**
     * @param string $permission
     * @param bool $granted
     */
    public function setTSIPermission(string $permission,bool $granted = false) {
        if(empty($permission)) {
            trigger_error(__CLASS__.' => setTSIPermission(): Permission must be set!', E_USER_WARNING);
            return;
        }

        if(in_array($permission,TSI_Role_Interface::tsi_permissions)) {
            $this->tsi_permissions[$permission] = ($granted ? 1 : 0);
            return;
        }

        trigger_error(__CLASS__.' => setTSIPermission(): Permission not set, "'.
            $permission.'"" was not found', E_USER_WARNING);
    }

    /**
     * @param string $permission
     * @return bool
     */
    public function getTSIPermission(string $permission) {
        if(empty($permission)) {
            trigger_error(__CLASS__.' => getTSIPermission(): Permission must be set!', E_USER_WARNING);
            return false;
        }

        if(in_array($permission,TSI_Role_Interface::tsi_permissions)) {
            if($this->getLevel() === 100)
                return true;

            if(array_key_exists($permission,$this->tsi_permissions)) {
                return ($this->tsi_permissions[$permission] == 1);
            }

            return false;
        }

        trigger_error(__CLASS__.' => getTSIPermission(): Permission not set, "'.
            $permission.'"" was not found', E_USER_WARNING);

        return false;
    }

    /**
     * @param array $tsi_permissions
     */
    public function setTSIPermissions(array $tsi_permissions) {
        $this->tsi_permissions = $tsi_permissions;
    }

    /**
     * @return array
     */
    public function getTSIPermissions() {
        return $this->tsi_permissions;
    }

    /**
     * @return array
     */
    public function getTSIPermissionsList() {
        $permissions = [];
        foreach (TSI_Role_Interface::tsi_permissions as $permission) {
            $permissions[$permission] = TSI_Role_Interface::tsi_permissions_desc[$permission];
        }

        return $permissions;
    }
}