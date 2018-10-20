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
 * TSI-Virtual-Server: v1.0.61
 * Class TSI_VServer
 * @package TSI_VServer
 */
class TSI_VServer implements TSI_VServer_Interface {
    private $instance_id = 0;
    private $server_id = 0;
    private $unique_id = '';
    private $status = false;
    private $platform = "Linux";
    private $version = "1.0";
    private $clientsonline = 0;
    private $channelsonline = 0;
    private $created = 0;
    private $uptime = 0;
    private $properties = null;

    /**
     * @return int
     */
    public function getServerID() {
        return (int)$this->server_id;
    }

    /**
     * @param int $server_id
     */
    public function setServerID(int $server_id) {
        $this->server_id = $server_id;
    }

    /**
     * @return int
     */
    public function getInstanceID() {
        return (int)$this->instance_id;
    }

    /**
     * @param int $instance_id
     */
    public function setInstanceID(int $instance_id) {
        $this->instance_id = $instance_id;
    }

    /**
     * @return string
     */
    public function getUID() {
        return strval($this->unique_id);
    }

    /**
     * @param string $unique_id
     */
    public function setUID(string $unique_id) {
        $this->unique_id = $unique_id;
    }

    /**
     * @return string
     */
    public function getServerName() {
        return strval($this->properties->getName());
    }

    /**
     * @param string $name
     */
    public function setServerName(string $name) {
        $this->properties->setName($name);
    }

    /**
     * @return bool
     */
    public function getOnline() {
        return (bool)$this->status;
    }

    /**
     * @param $status
     */
    public function setOnline($status) {
        if(is_bool($status)) {
            $this->status = (bool)$status;
            return;
        }

        $this->status = ($status == 'online');
    }

    /**
     * @return string
     */
    public function getPlatform() {
        return strval($this->platform);
    }

    /**
     * @param string $platform
     */
    public function setPlatform(string $platform) {
        $this->platform = $platform;
    }

    /**
     * @return string
     */
    public function getVersion() {
        return strval($this->version);
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version) {
        $this->version = $version;
    }

    /**
     * @return int
     */
    public function getMaxClients() {
        return (int)$this->properties->getMaxClients();
    }

    /**
     * @param int $maxclients
     */
    public function setMaxClients(int $maxclients) {
        $this->properties->getMaxClients($maxclients);
    }

    /**
     * @return int
     */
    public function getClientsOnline() {
        return (int)$this->clientsonline;
    }

    /**
     * @param int $clientsonline
     */
    public function setClientsOnline(int $clientsonline) {
        $this->clientsonline = $clientsonline;
    }

    /**
     * @return int
     */
    public function getChannelOnline() {
        return (int)$this->channelsonline;
    }

    /**
     * @param int $channelsonline
     */
    public function setChannelOnline(int $channelsonline) {
        $this->channelsonline = $channelsonline;
    }

    /**
     * @return int
     */
    public function getCreatedTime() {
        return (int)$this->created;
    }

    /**
     * @param int $createdtime
     */
    public function setCreatedTime(int $createdtime) {
        $this->created = $createdtime;
    }

    /**
     * @return int
     */
    public function getUptime() {
        return (int)$this->uptime;
    }

    /**
     * @param int $uptime
     */
    public function setUptime(int $uptime) {
        $this->uptime = $uptime;
    }

    /**
     * @return TSI_Properties
     */
    public function getProperties() {
        if($this->properties instanceof TSI_Properties) {
            return $this->properties;
        }

        return new TSI_Properties();
    }

    /**
     * @param TSI_Properties $properties
     */
    public function setProperties(TSI_Properties $properties) {
        $this->properties = $properties;
    }

    /**
     * @return string
     */
    public function isTeaSpeak() {
        return (strpos(strtolower($this->version), 'teaspeak') !== false);
    }
}