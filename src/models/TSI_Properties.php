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

class TSI_Properties implements TSI_Properties_Interface {
    /**
     * @var array
     */
    public $virtualserver_options = [];

    /**
     * Set server name
     * @param string $name
     */
    public function setName(string $name): void {
        $this->virtualserver_options['virtualserver_name'] = trim($name);
    }

    /**
     * Get server name
     * @return string
     */
    public function getName(): string {
        return strval($this->virtualserver_options['virtualserver_name']);
    }

    /**
     * Set voice server port
     * @param int $port
     */
    public function setPort(int $port): void {
        $this->virtualserver_options['virtualserver_port'] = $port;
    }

    /**
     * Get voice server port
     * @return int
     */
    public function getPort(): int {
        return (int)$this->virtualserver_options['virtualserver_port'];
    }

    /**
     * Set server password
     * @param string $password
     */
    public function setPassword(string $password): void {
        $this->virtualserver_options['virtualserver_password'] = trim($password);
    }

    /**
     * Set number of max server slots
     * @param int $clients
     */
    public function setMaxClients(int $clients): void {
        $this->virtualserver_options['virtualserver_maxclients'] = $clients;
    }

    /**
     * Get number of max server slots
     * @return int
     */
    public function getMaxClients(): int {
        return (int)$this->virtualserver_options['virtualserver_maxclients'];
    }
}