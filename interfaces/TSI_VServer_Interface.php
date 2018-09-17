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

interface TSI_VServer_Interface {
    //SETTER
    public function setInstanceID(int $instance_id);
    public function setServerID(int $server_id);
    public function setUID(string $unique_id);
    public function setServerName(string $name);
    public function setOnline($status);
    public function setPlatform(string $platform);
    public function setVersion(string $version);
    public function setMaxClients(int $maxclients);
    public function setClientsOnline(int $clientsonline);
    public function setChannelOnline(int $channelsonline);
    public function setCreatedTime(int $createdtime);
    public function setUptime(int $uptime);
    public function setProperties(TSI_Properties $properties);

    //GETTER
    public function getInstanceID();
    public function getServerID();
    public function getUID();
    public function getServerName();
    public function getOnline();
    public function getPlatform();
    public function getVersion();
    public function getMaxClients();
    public function getClientsOnline();
    public function getChannelOnline();
    public function getCreatedTime();
    public function getUptime();
    public function getProperties();

    //ANY
    public function isTeaSpeak();
}