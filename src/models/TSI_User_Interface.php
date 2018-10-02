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
 * Interface TSI_User_Interface
 * @package TSI_Client\Models
 */
interface TSI_User_Interface {
    //SETTER
    public function setUserID(int $user_id);
    public function setResellerID(int $id);
    public function setRoleID(int $role_id);
    public function setUsername(string $username);
    public function setFirstName(string $first_name);
    public function setLastName(string $last_name);
    public function setEmail(string $email);
    public function setQueryNickname(string $query_name);
    public function setLanguage(string $lang);
    public function setFixedVMs(array $vms);
    public function setMaxSlotsVMs(int $slots);
    public function setRegDateArray(array $reg_date);
    public function setActive(bool $active);
    public function setIcon(string $icon_pkg);
    public function setInitPassword(string $password);
    public function setServer(int $instance, int $vserver_id);

    //GETTER
    public function getUserID();
    public function getResellerID();
    public function getRoleID();
    public function getUsername();
    public function getFirstName();
    public function getLastName();
    public function getEmail();
    public function getQueryNickname();
    public function getLanguage();
    public function getFixedVMs();
    public function getMaxSlotsVMs();
    public function getRegDate();
    public function getRegDateTimezone();
    public function getRegDateTimezoneType();
    public function getActive();
    public function getIcon();
    public function getServer(int $instance, int $vserver_id);
    public function getVMsByInstance(int $instance);

    //MULTI
    public function getServersMulti();
}