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

interface TSI_Client_Base_Interface {
    const virtualserver_modify = array(
        "virtualserver_name",
        "virtualserver_icon_id",
        "virtualserver_port",
        "virtualserver_welcomemessage",
        "virtualserver_password",
        "virtualserver_maxclients",
        "virtualserver_reserved_slots",
        "virtualserver_weblist_enabled",
        "virtualserver_hostmessage",
        "virtualserver_hostbanner_url",
        "virtualserver_hostbanner_gfx_url",
        "virtualserver_hostbanner_gfx_intervall",
        "virtualserver_hostbutton_tooltip",
        "virtualserver_hostbutton_gfx_url",
        "virtualserver_hostbutton_url",
        "virtualserver_max_download_total_bandwith",
        "virtualserver_download_quota",
        "virtualserver_max_upload_total_bandwith",
        "virtualserver_upload_quota",
        "virtualserver_anti_flood_options",
        "virtualserver_security_options",
        "virtualserver_standard_groups_options",
        "virtualserver_compainment_options",
        "virtualserver_other_options",
        "virtualserver_protocol_options"
    );

    //SETTER
    public function setKeys(string $client_key,string $secret_key);
    public function setServerUrl(string $url);
    public function setGZIPSupport(bool $gzip);
    public function setSSLOptions(bool $ssl_verifyhost,bool $ssl_verifypeer);
    public function setCache(string $key,$var,int $ttl);
    public function setClientCache(bool $cache);
    public function setProxyServer(string $ip,int $port,string $username,string $password);

    //GETTER
    public function getKeys();
    public function getServerUrl();
    public function getGZIPSupport();
    public function getSSLOptions();
    public function getResponse(string $call);
    public function getCache(string $key);
    public function getClientCache();
    public function getProxyServer();

    //FUNCTIONS
    public function autoload(string $class);
    public function responseProcessing(string $call);
    public function checkAPI(bool $recache,int $cache);
    public function checkJSON(string $json);
    public function insertCall(string $call,array $post,string $url);
    public function Exec(bool $responseProcessing);
    public function apiIsActual(int $cache);
    public function debugAllIndexes(bool $PrintOutput);

    //REGISTER
    public function setRegisterCacheWrite(string $class,string $method);
    public function setRegisterCacheRead(string $class,string $method);
    public function setRegisterCacheExist(string $class,string $method);

    public function getRegisterCacheWrite();
    public function getRegisterCacheRead();
    public function getRegisterCacheExist();
}
