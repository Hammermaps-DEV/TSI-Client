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

class TSI_MultiClient extends TSI_Client implements TSI_MultiClient_Interface {
    /**
     * TSI_MultiClient constructor.
     * @param TSI_Client $client
     */
     function __construct(TSI_Client $client) {
         //Set Instance
         parent::__construct(
             $client->getServerUrl(),
             $client->getKeys()['client_key'],
             $client->getKeys()['secret_key']);

         $this->setProxyServer(
             $client->getProxyServer()['ip'],
             $client->getProxyServer()['port'],
             $client->getProxyServer()['user'],
             $client->getProxyServer()['passwd']);

         $this->setSSLOptions(
             $client->getSSLOptions()['ssl_verifyhost'],
             $client->getSSLOptions()['ssl_verifypeer']);

         $this->setGZIPSupport($client->getGZIPSupport());
         $this->setClientCache($client->getClientCache());

         $this->setRegisterCacheRead(
             $client->getRegisterCacheRead()['class'],
             $client->getRegisterCacheRead()['method']);
         $this->setRegisterCacheExist(
             $client->getRegisterCacheExist()['class'],
             $client->getRegisterCacheRead()['method']);
         $this->setRegisterCacheWrite(
             $client->getRegisterCacheWrite()['class'],
             $client->getRegisterCacheRead()['method']);
     }

    /**
     * TSI_MultiClient deconstruct.
     */
     function __destruct() {
         parent::__destruct();
     }

}