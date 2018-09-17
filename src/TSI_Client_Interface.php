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

interface TSI_Client_Interface extends TSI_Client_Base_Interface {
    //GETTER
    public function getTSIVersion(int $cache);
    public function getAPIVersion(int $cache);
    public function getAddons(int $cache);
    public function getAddonVersion(string $addon_name, int $cache);
    public function getTSIUsers();
    public function getTSIUser(int $user_id);
    public function getTSIUserByUsername(string $username);
    public function getTSIUserByEMail(string $email);
    public function getTSIRolesList();
    public function getTSIRole(int $role_id);
    public function getTSIRoleByName(string $name);
    public function getTSInstanceList();
    public function getTSInstance(int $instance_id);
    public function getTSInstanceByIP(string $ip);
    public function getTSVServerList(int $instance_id);
    public function getTSVServer(int $instance_id, int $vserver_id);
    public function getTSViewer(int $instance_id, int $vserver_id, bool $show_flags, string $style, int $cache);

    //ADD
    public function addTSIUser(TSI_User $user);
    public function addTSVServer(TSI_VServer $vserver);

    //EDIT
    public function editTSIUser(TSI_User $user);
    public function editTSVServer(TSI_VServer $vserver);

    //DELETE
    public function deleteTSIUser(TSI_User $user);
    public function deleteTSIRole(TSI_Role $role);
    public function deleteTSInstance(TSI_Instance $instance);
    public function deleteTSVServer(TSI_VServer $vserver);

    //START / STOP
    public function startTSVServer(TSI_VServer $vserver);
    public function stopTSVServer(TSI_VServer $vserver);
    public function startTSIBot(int $instance_id, int $vserver_id, string $language);
    public function stopTSIBot(int $instance_id, int $vserver_id);

    //RUN / STATUS
    public function runTSICron();
    public function isTSIBotRun(int $instance_id, int $vserver_id);
}
