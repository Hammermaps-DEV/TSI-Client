<?php

//Include Client
include_once ("../src/TSI_Client.php");

/**
 * ###################################################################################
 * Übersicht über die Methoden die einem Objekt zur Verfügung stehen
 * ###################################################################################
 *
 * TSI_Client_Base Objekt (abstract)    => Datei: TSI_Client_Base_Interface.php
 * TSI_Client Objekt                    => Datei: TSI_Client_Interface.php
 * TSI_VServer Objekt                   => Datei: TSI_VServer_Interface.php
 * TSI_Instance Objekt                  => Datei: TSI_Instance_Interface.php
 * TSI_Role Objekt                      => Datei: TSI_Role_Interface.php
 * TSI_Properties Objekt                => Datei: TSI_Properties_Interface.php
 * TSI_User Objekt                      => Datei: TSI_User_Interface.php
 * TSI_Resellers Objekt                 => Datei: TSI_Resellers_Interface.php
 * TSI_MultiClient Objekt               => Datei: TSI_MultiClient_Interface.php
 */

/****
 * Test Cache Class
 */
class cache {
    public static function set($key,$data,$ttl) {
        $array = [
            'ttl' => (time() + $ttl),
            'data' => $data
        ];

        $stream = gzcompress(json_encode($array));
        file_put_contents(md5($key).'.cache',$stream);
    }

    public static function get($key) {
        if(file_exists(md5($key).'.cache')) {
            $stream = file_get_contents(md5($key).'.cache');
            $json = json_decode(gzuncompress($stream),true);
            if($json['ttl'] >= time()) {
                return $json['data'];
            }
        }

        return false;
    }

    public static function exists($key) {
        if(file_exists(md5($key).'.cache')) {
            $stream = file_get_contents(md5($key).'.cache');
            $json = json_decode(gzuncompress($stream),true);
            if($json['ttl'] >= time()) {
                return true;
            }
        }

        return false;
    }
}

/**
 * ###################################################################################
 * Übersicht über die Methoden die einem Objekt zur Verfügung stehen
 * ###################################################################################
 *
 * TSI_Client_Base Objekt (abstract)    => Datei: TSI_Client_Base_Interface.php
 * TSI_Client Objekt                    => Datei: TSI_Client_Interface.php
 * TSI_VServer Objekt                   => Datei: TSI_VServer_Interface.php
 * TSI_Instance Objekt                  => Datei: TSI_Instance_Interface.php
 * TSI_Role Objekt                      => Datei: TSI_Role_Interface.php
 * TSI_Properties Objekt                => Datei: TSI_Properties_Interface.php
 * TSI_User Objekt                      => Datei: TSI_User_Interface.php
 * TSI_Resellers Objekt                 => Datei: TSI_Resellers_Interface.php
 * TSI_MultiClient Objekt               => Datei: TSI_MultiClient_Interface.php
 */

//Client erstellen
$client = new TSI_Client\TSI_Client(
    'https://meine_domain.de', //Die Volle-URL zur TSI Installation mit API-Erweiterung
    'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX', //Dein Client-Key sehe "API Zugänge"
    'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX' //Dein Secret-Key sehe "API Zugänge"
);

//Register Cache
$client->setRegisterCacheWrite('cache','set');
$client->setRegisterCacheExist('cache','exists');
$client->setRegisterCacheRead('cache','get');

echo 'Version des API Interface Moduls ohne Cache: '.$client->getAddonVersion('modul_ai',0)['version'];
echo '<br>';

echo 'Version des API Interface Moduls mit Cache (30 sekunden): '.$client->getAddonVersion('modul_ai',30)['version'];
echo '<br>';

//Weitere Methoden mit Cache sind..
echo '<pre>';
print_r($client->getAddonVersion('modul_ai',30));
echo '<br>';
print_r($client->getAddons(30));
echo '<br>';
print_r($client->getTSIVersion(30));
echo '<br>';

//Nur verwenden wenn benötigt! Wird intern zum API prüfen verwendet ( return boolean )
print_r($client->checkAPI(false,30)); //Force Recache & TTL
echo '</pre>';
