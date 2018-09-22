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

class TSI_Instance implements TSI_Instance_Interface {
    /**
     * @var int
     * @internal
     */
    private $id = 0;

    /**
     * @var string
     * @internal
     */
    private $server_ip = '';

    /**
     * @var int
     * @internal
     */
    private $query_port = 10011;

    /**
     * @var string
     * @internal
     */
    private $serveradmin = '';

    /**
     * @var string
     * @internal
     */
    private $last_perm_import = '';

    /**
     * @param int $user_id
     */
    public function setID(int $user_id): void {
        $this->id = $user_id;
    }

    /**
     * @return int
     */
    public function getID(): int {
        return (int)$this->id;
    }

    /**
     * @param string $server_ip
     */
    public function setIP(string $server_ip): void {
        $server_ip = trim($server_ip);
        if(!filter_var($server_ip, FILTER_VALIDATE_IP)) {
            trigger_error(__CLASS__.' => TSI_Instance::setIP(): No valid IP-Adress!', E_USER_WARNING);
            return;
        }

        $this->server_ip = $server_ip;
    }

    /**
     * @return string
     */
    public function getIP(): string {
        return strval($this->server_ip);
    }

    /**
     * @param int $query_port
     */
    public function setQueryPort(int $query_port): void {
        $this->query_port = $query_port;
    }

    /**
     * @return int
     */
    public function getQueryPort(): int {
        return (int)$this->query_port;
    }

    /**
    * @param string $serveradmin
    */
    public function setServerAdmin(string $serveradmin): void {
        $this->serveradmin = trim($serveradmin);
    }

    /**
     * @return string
     */
    public function getServerAdmin(): string {
        return strval($this->serveradmin);
    }

    /**
     * @param mixed $last_perm_import
     */
    public function setLastPermImport(mixed $last_perm_import): void {
        $this->last_perm_import = $last_perm_import;
    }

    /**
     * @return string
     */
    public function getLastPermImport() {
        return $this->last_perm_import;
    }
}