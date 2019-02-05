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

//Client erstellen
$client = new TSI_Client\TSI_Client(
    'https://meine_domain.de', //Die Volle-URL zur TSI Installation mit API-Erweiterung
    'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX', //Dein Client-Key sehe "API Zugänge"
    'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX' //Dein Secret-Key sehe "API Zugänge"
);

echo '<pre>';

//Array ( [name] => Teamspeak Interface [version] => 1.0.67 [last_update] => 2018-09-12 08:00:07.000000 )
$version_tsi = $client->getTSIVersion();

echo 'Name: '.$version_tsi['name'];
echo '<br>';
echo 'Version: '.$version_tsi['version'];
echo '<br>';
echo 'Letztes Update: '.$version_tsi['last_update'];
echo '<br>';
echo 'Ist der TSI-Client aktuell: '.($client->apiIsActual() ? 'JA' : 'NEIN');
echo '<br>';

/**
 * array (size=6)
'tsi_main' => array (size=3)
'name' => string 'Teamspeak Interface' (length=19)
'version' => string '1.0.67' (length=6)
'last_update' => array (size=3)
'date' => string '2018-09-12 08:00:07.000000' (length=26)
'timezone_type' => int 3
'timezone' => string 'Europe/Berlin' (length=13)
 *
'modul_ai' => array (size=4)
'name' => string 'API Interface' (length=13)
'extended' => boolean false
'version' => string '1.0.19' (length=6)
'last_update' => array (size=3)
'date' => string '2018-09-12 08:01:04.000000' (length=26)
'timezone_type' => int 3
'timezone' => string 'Europe/Berlin' (length=13)
 *
'modul_bt' => array (size=3)
'name' => string 'Simple Bots' (length=11)
'version' => string '1.0.20' (length=6)
'last_update' => array (size=3)
'date' => string '2018-09-08 10:55:15.000000' (length=26)
'timezone_type' => int 3
'timezone' => string 'Europe/Berlin' (length=13)
 * ...........
 */
$tsi_addons = $client->getAddons();

echo '<p>##################################################################</p>';

foreach ($tsi_addons as $tag => $addon) {
    echo 'Interne Bezeichnung: '.$tag;
    echo '<br>';
    echo 'Name: '.$addon['name'];
    echo '<br>';
    echo 'Version: '.$addon['version'];
    echo '<br>';
    echo 'Letztes Update: '.$addon['last_update']['date'];
    echo '<br>';
    echo 'Timezone: '.$addon['last_update']['timezone'];
    echo '<br>';
    echo 'Timezone Type: '.$addon['last_update']['timezone_type'];
    echo '<p>##################################################################</p>';
}

/**
 * Eine schnellere Möglichkeit um an die Version usw. eines Moduls dranzukommen ist die getAddonVersion() methode
 * Bezeichung der Module:
 * modul_ai = API Interface
 * modul_bt = Simple Bots
 * modul_ft = Serverfiles
 * modul_sg = Servergroups
 * modul_cl = Clients & Permissions
 */
echo 'Version des API Interface Moduls: '.$client->getAddonVersion('modul_ai')['version'];
echo '<br>';
