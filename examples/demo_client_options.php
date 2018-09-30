<?php

//Include Client
include_once ("../TSI_Client.php");

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
 * TSI_MultiClient Objekt               => Datei: TSI_MultiClient_Interface.php
 */

//Client erstellen
$client = new TSI_Client\TSI_Client();

//Eine andere Möglichkeit um die Keys zu setzen
$client->setKeys(
    'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX', //Client Key
    'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX' //Secret Key
);

$client->setServerUrl('https://meine_domain.de'); //Server URL

//Soll ein Proxy-Server verwendet werden
$client->setProxyServer('123.123.123.123',9000, 'username', 'password');

//Soll die Verbindung (wenn möglich) mit GZIP komprimiert werden? [ Ist standardmäßig aktiviert ]
$client->setGZIPSupport(true); // true || false

//Wo sollen die Cache-Files gespeichert werden
$client->setCacheDir('cache/');

//Einstellung für SSL Verbindungen zum Server, ob das zertifikat geprüft werden soll (verifyhost und verifypeer) [ Ist standardmäßig beides false ]
$client->setSSLOptions(false,false); // verifyhost & verifypeer

//Sollen bestimmte anfragen wie (version usw.) in einen cache geschrieben werden [ Verbessert die performance, ist standardmäßig aktiviert ]
$client->setClientCache(true);

echo 'Version des API Interface Moduls: '.$client->getAddonVersion('modul_ai')['version'];
