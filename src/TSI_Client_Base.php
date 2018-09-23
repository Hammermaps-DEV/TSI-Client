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

abstract class TSI_Client_Base implements TSI_Client_Base_Interface
{
    /**
     * @var null|resource
     */
    public $curl_multi = null;

    /**
     * @var string
     */
    public $client_key = '';

    /**
     * @var string
     */
    public $secret_key = '';

    /**
     * @var array
     */
    public $curl_handles = [];

    /**
     * @var array
     */
    public $curl_config = [];

    /**
     * @var bool
     */
    public $ssl_verifyhost = false;

    /**
     * @var bool
     */
    public $ssl_verifypeer = false;

    /**
     * @var string
     */
    public $server_url = '';

    /**
     * @var array
     */
    public $http_query = [];

    /**
     * @var bool
     */
    public $server_gzip = true;

    /**
     * @var bool
     */
    public $client_cache = true;

    /**
     * @var array
     */
    public $server_data = [
        'query'=>[],
        'input'=>[],
        'data'=>[]
    ];

    /**
     * @var array
     */
    public $version = [];

    /**
     * @var string
     */
    public $lastcall = '';

    /**
     * @var bool
     */
    public $cache_dir = 'cache/';

    /**
     * @var array
     */
    private $cache_functions = [];

    /**
     * PHP TSI-Client Version
     */
    const TSI_CLIENT_VERSION = '1.1.0';

    /**
     * CURL Agent
     */
    const USER_AGENT = 'PHP-TSI-Client-V{version}';

    /**
     * TSI_Client constructor.
     * @param string $server_url
     * @param string $client_key
     * @param string $secret_key
     */
    public function __construct(string $server_url = '', string $client_key = '', string $secret_key = '') {
        if (!extension_loaded('curl')) {
            trigger_error(__CLASS__.": Die PHP-Erweiterung Curl ist nicht geladen! ".
                "| The PHP extension Curl is not loaded!", E_USER_ERROR);
            return;
        }

        spl_autoload_register(array($this,'autoload'));

        $this->curl_multi = curl_multi_init();

        if(!empty($client_key))
            $this->client_key = $client_key;

        if(!empty($secret_key))
            $this->secret_key = $secret_key;

        if(!empty($secret_key))
            $this->server_url = $server_url;

        $this->http_query = [
            'controller'=>'api'
        ];
    }

    /**
     * TSI_Client deconstructor.
     */
    public function __destruct() {
        if (extension_loaded('curl')) {
            curl_multi_close($this->curl_multi);
        }
    }

    /**
     * Autoloader for interfaces & classes
     * @param $class
     */
    public function autoload(string $class) {
        if(class_exists($class, false) || interface_exists($class, false)) {
            return;
        }

        $class = str_replace([__NAMESPACE__,'\\'],'',$class);
        if (file_exists("models/".$class.'.php') &&
            file_exists("models/".$class."_Interface.php")) {
            require_once("models/".$class."_Interface.php");
            require_once("models/".$class.".php");
        }
    }

    /*************************** CONFIG FUNCTIONS ***************************/
    /**
     * Set client and secret key for API
     * @param string $client_key
     * @param string $secret_key
     */
    public function setKeys(string $client_key,string $secret_key) {
        $this->client_key = $client_key;
        $this->secret_key = $secret_key;
    }

    /**
     * Get the client and secret keys
     * @return array
     */
    public function getKeys() {
        return [
            'client_key'=>$this->client_key,
            'secret_key'=>$this->secret_key
        ];
    }

    /**
     * Set the Full-URL to TSI Installation
     * @param string $url
     */
    public function setServerUrl(string $url = 'http://localhost') {
        $this->server_url = $url;
    }

    /**
     * Get the URL to TSI Installation
     * @return string
     */
    public function getServerUrl() {
        return strval($this->server_url);
    }

    /**
     * Use GZip compressed server answer
     * @param bool $gzip
     */
    public function setGZIPSupport(bool $gzip = false) {
        $this->server_gzip = $gzip;
    }

    /**
     * Get var for GZip compressed server answer
     * @return bool
     */
    public function getGZIPSupport() {
        return (bool)$this->server_gzip;
    }

    /**
     * Use cache for server answers
     * @param bool $cache
     */
    public function setClientCache(bool $cache = false) {
        $this->client_cache = $cache;
    }

    /**
     * Get var for cache server answer
     * @return bool
     */
    public function getClientCache() {
        return $this->client_cache;
    }

    /**
     * Set dir for cache files
     * @param string $dir
     */
    public function setCacheDir(string $dir) {
        $this->cache_dir = $dir;
    }

    /**
     * Get dir of cache files
     * @return bool
     */
    public function getCacheDir() {
        return strval($this->cache_dir);
    }

    /**
     * Set ssl_verifyhost and ssl_verifypeer option for curl
     * @param bool $ssl_verifyhost
     * @param bool $ssl_verifypeer
     */
    public function setSSLOptions(bool $ssl_verifyhost,bool $ssl_verifypeer) {
        $this->ssl_verifyhost = $ssl_verifyhost;
        $this->ssl_verifypeer = $ssl_verifypeer;
    }

    /**
     * Get ssl_verifyhost and ssl_verifypeer option for curl
     * @return array
     */
    public function getSSLOptions() {
        return [
            'ssl_verifyhost'=>$this->ssl_verifyhost,
            'ssl_verifypeer'=>$this->ssl_verifypeer
        ];
    }

    /**
     * @param string $call
     * @return bool|array
     */
    public function getResponse(string $call = '') {
        if(empty($call)) {
            $call = strval($this->lastcall);
        }

        if(!empty($this->server_data['data'][$call]) &&
            array_key_exists($call,$this->server_data['data'])) {
            return (array)$this->server_data['data'][$call]['response'];
        }

        return false;
    }

    /**
     * Processing the response
     * @param string $call
     * @return bool|array
     * @internal
     */
    public function responseProcessing(string $call = '') {
        if (!extension_loaded('curl')) { return false; }

        if(empty($call)) {
            $call = $this->lastcall;
        }

        if($this->server_data['http_status_code'][$call] >= 400 && $this->server_data['http_status_code'][$call] < 500) {
            trigger_error(__CLASS__.': The call "'.ucfirst($call).'" returned a "'.$this->server_data['http_status_code'][$call].'" HTTP-Statuscode by Client!\'', E_USER_WARNING);
            return false;
        }

        if($this->server_data['http_status_code'][$call] >= 500 && $this->server_data['http_status_code'][$call] < 600) {
            trigger_error(__CLASS__.': The call "'.ucfirst($call).'" returned a "'.$this->server_data['http_status_code'][$call].'" HTTP-Statuscode by Server!\'', E_USER_WARNING);
            return false;
        }

        if(empty($this->server_data['json'][$call])) {
            trigger_error(__CLASS__.': The call "'.ucfirst($call).'" returned a blank result!\'', E_USER_WARNING);
            return false;
        }

        if($data = $this->checkJSON($this->server_data['json'][$call])) {
            if($data['valid']) {
                $this->server_data['data'][$call] = $data;
                unset($data);
            } else {
                $this->server_data['data'][$call] = null;
                trigger_error(__CLASS__.": ".
                    ucfirst($data['error']), E_USER_WARNING);
            }
        }

        return false;
    }

    /**
     * Check the Server-API Version
     * @param int $cache
     * @param bool $recache
     * @return bool
     * @throws \Exception
     */
    public function checkAPI(bool $recache = false, int $cache = 60) {
        if (!extension_loaded('curl')) { return false; }

        //No MultiCalls
        if(!empty($this->version['tsi_main']['version']) && !empty($this->version['modul_ai']['version'])) {
            return true;
        }

        //Use Cache
        if(!$recache && $cache >= 1 && ($cache_data = $this->getCache('checkAPI'))) {
            $this->server_data['data']['versionsGet'] = $cache_data['data'];
            $this->version = $cache_data['version'];
            return true;
        }

        $this->insertCall('api'); //set a call
        $this->Exec(); //execute
        if(array_key_exists('api',$this->server_data['json'])) {
            if(empty($this->server_data['json']['api'])) {
                trigger_error(__CLASS__.': The call "Api" returned a blank result!\'', E_USER_WARNING);
                return false;
            }

            if($data = $this->checkJSON($this->server_data['json']['api'])) {
                $this->server_data['data']['api'] = $data; unset($data);
                if($this->server_data['data']['api']['valid']) {
                    //response is ok (access granted)
                    $this->insertCall('versionsGet');
                    $this->Exec(); //execute

                    if(empty($this->server_data['json']['versionsGet'])) {
                        trigger_error(__CLASS__.': The call "Version" returned a blank result!', E_USER_WARNING);
                        return false;
                    }

                    if($data = $this->checkJSON($this->server_data['json']['versionsGet'])) {
                        $this->server_data['data']['versionsGet'] = $data; unset($data);
                        if($this->server_data['data']['versionsGet']['valid']) {
                            if(count($this->server_data['data']['versionsGet']['response']) &&
                                array_key_exists('modul_ai',$this->server_data['data']['versionsGet']['response'])) {
                                foreach ($this->server_data['data']['versionsGet']['response'] as $key => $var) {
                                    $this->version[$key] = $var; //Set API Version
                                }

                                if($cache >= 1) {
                                    $this->setCache('checkAPI',[
                                        'data' => $this->server_data['data']['versionsGet'],
                                        'version' => $this->version,
                                    ],$cache);
                                }

                                return true;
                            } else {
                                trigger_error(__CLASS__.": The line \"API version\" is missing!", E_USER_WARNING);
                            }
                        } else {
                            trigger_error(__CLASS__.": ".
                                ucfirst($this->server_data['data']['versionsGet']['error']), E_USER_WARNING);
                        }
                    }
                } else {
                    trigger_error(__CLASS__.": ".
                        ucfirst($this->server_data['data']['api']['error']), E_USER_WARNING);
                }
            }
        }

        return false;
    }

    /**
     * Check the JSON string
     * @param string $json
     * @return bool|mixed|string
     */
    public function checkJSON(string $json) {
        $stream_orginal = $json;
        $json = @json_decode($json,true);
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return $json;
                break;
            case JSON_ERROR_DEPTH:
                trigger_error(__CLASS__.': Maximum stack depth exceeded', E_USER_WARNING);
                print_r($stream_orginal);
                break;
            case JSON_ERROR_STATE_MISMATCH:
                trigger_error(__CLASS__.': Underflow or the modes mismatch', E_USER_WARNING);
                print_r($stream_orginal);
                break;
            case JSON_ERROR_CTRL_CHAR:
                trigger_error(__CLASS__.': Unexpected control character found', E_USER_WARNING);
                print_r($stream_orginal);
                break;
            case JSON_ERROR_SYNTAX:
                trigger_error(__CLASS__.': Syntax error, malformed JSON', E_USER_WARNING);
                print_r($stream_orginal);
                break;
            case JSON_ERROR_UTF8:
                trigger_error(__CLASS__.': Malformed UTF-8 characters, possibly incorrectly encoded', E_USER_WARNING);
                print_r($stream_orginal);
                break;
            default:
                trigger_error(__CLASS__.': Unknown error', E_USER_WARNING);
                print_r($stream_orginal);
                break;
        }

        unset($stream_orginal);
        return false;
    }

    /**
     * @param string $call
     * @param array $post
     * @throws \Exception
     */
    public function insertCall(string $call,array $post=[]) {
        if (!extension_loaded('curl')) { return; }

        $curl = curl_init();
        $this->http_query['action'] = trim($call);
        curl_setopt($curl, CURLOPT_URL, $this->server_url.'/?'.
            http_build_query($this->http_query));
        curl_setopt($curl,CURLOPT_HEADER, $this->server_gzip);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_DNS_CACHE_TIMEOUT, 0);
        curl_setopt($curl,CURLOPT_USERAGENT,
            str_replace('{version}',self::TSI_CLIENT_VERSION,self::USER_AGENT));

        //only on SSL connection
        if (strpos($this->server_url, 'https') !== false) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $this->ssl_verifyhost);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
        }

        $client_secret_keys = [
            'client' => $this->client_key,
            'secret' => $this->secret_key
        ];

        if($this->server_gzip) {
            curl_setopt($curl, CURLOPT_ENCODING, "gzip,deflate");
        }

        curl_setopt($curl,CURLOPT_POST, true);
        curl_setopt($curl,CURLOPT_POSTFIELDS,
            http_build_query(array_merge($client_secret_keys,$post)));
        $code = \curl_multi_add_handle($this->curl_multi,$curl);
        if ($code != CURLM_OK) {
            throw new \Exception(__CLASS__.": "."Curl handle for ".$this->server_url.'/?'.
                http_build_query($this->http_query)." could not be added");
        }
        $this->curl_handles[$call] = $curl;
        $this->curl_config[$call] = ['gzip'=>$this->server_gzip];
        $this->server_data['query'][$call] = $this->server_url.'/?'.
            http_build_query($this->http_query);
        $this->server_data['input'][$call] = $post;
        $this->lastcall = $call;
    }

    public function Exec() {
        if (!extension_loaded('curl')) { return; }
        do {
            $status = curl_multi_exec($this->curl_multi, $active);
        } while ($status === CURLM_CALL_MULTI_PERFORM || $active);

        foreach ($this->curl_handles as $i => $url) {
            $this->server_data['json'][$i] = curl_multi_getcontent($this->curl_handles[$i]);
            $this->server_data['http_status_code'][$i] = curl_getinfo($this->curl_handles[$i], CURLINFO_HTTP_CODE);
            if($this->server_data['http_status_code'][$i] == 200) {
                if ($this->curl_config[$i]['gzip']) {
                    $sections = explode("\x0d\x0a\x0d\x0a", $this->server_data['json'][$i], 2);
                    while (!strncmp($sections[1], 'HTTP/', 5)) {
                        $sections = explode("\x0d\x0a\x0d\x0a", $sections[1], 2);
                    }

                    if (count($sections) >= 2) {
                        if (preg_match('/^Content-Encoding: gzip/mi', $sections[0])) {
                            $this->server_data['json'][$i] = $sections[1];
                        }
                    }
                }

                $this->responseProcessing($i); //processing data
            }

            curl_close($this->curl_handles[$i]);
            unset($this->curl_handles[$i],$this->curl_config[$i]);
        }

        $this->curl_multi = curl_multi_init();
    }

    /**
     * @param string $key
     * @return bool|mixed
     */
    public function getCache(string $key) {
        if(!$this->client_cache)
            return false;

        if(!array_key_exists('exist',$this->cache_functions) ||
            !array_key_exists('read',$this->cache_functions))
            return false;

        //IS EXIST
        if(class_exists($this->cache_functions['exist']['class']) &&
            is_callable([$this->cache_functions['exist']['class'],
                $this->cache_functions['exist']['method']])) {
            if(call_user_func_array([$this->cache_functions['exist']['class'],
                $this->cache_functions['exist']['method']], [$key])) {
                //GET
                if(class_exists($this->cache_functions['read']['class']) &&
                    is_callable([$this->cache_functions['read']['class'],
                        $this->cache_functions['read']['method']])) {
                    $serialize_data = call_user_func_array([$this->cache_functions['read']['class'],
                        $this->cache_functions['read']['method']], [$key]);
                    $data_store = unserialize($serialize_data); unset($serialize_data);
                    //For Static file cache
                    if(!empty($data_store['data']) && $data_store['ttl'] >= time()) {
                        return $data_store['data'];
                    }
                }

            }
        }

        return false;
    }

    /**
     * @param string $key
     * @param mixed $var
     * @param int $ttl
     * @return bool
     */
    public function setCache(string $key,$var,int $ttl=60) {
        if(!$this->client_cache)
            return false;

        if(!array_key_exists('write',$this->cache_functions))
            return false;

        $data_store = serialize(['data' => $var, 'ttl' => (time()+$ttl)]);
        if(class_exists($this->cache_functions['write']['class']) &&
            is_callable([$this->cache_functions['write']['class'],
                $this->cache_functions['write']['method']]))
        return call_user_func_array([$this->cache_functions['write']['class'],
            $this->cache_functions['write']['method']], [$key,$data_store,$ttl]);

        return false;
    }

    /**
     * @param string $class
     * @param string $method
     * @api
     */
    public function registerCacheWrite(string $class, string $method) {
        $this->cache_functions['write']['class'] = trim($class);
        $this->cache_functions['write']['method'] = trim($method);
    }

    /**
     * @param string $class
     * @param string $method
     * @api
     */
    public function registerCacheRead(string $class, string $method) {
        $this->cache_functions['read']['class'] = trim($class);
        $this->cache_functions['read']['method'] = trim($method);
    }

    /**
     * @param string $class
     * @param string $method
     * @api
     */
    public function registerCacheExist(string $class, string $method) {
        $this->cache_functions['exist']['class'] = trim($class);
        $this->cache_functions['exist']['method'] = trim($method);
    }
}