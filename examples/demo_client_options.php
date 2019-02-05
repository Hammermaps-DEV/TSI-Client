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
    'http://10.10.2.19/tsi', //Die Volle-URL zur TSI Installation mit API-Erweiterung
    'JDJ5JDEwJHRRZXMyL2JMamw0RmFxeEc2VUxXYS5HMWNrQXBNTmFNSzFaVzFnUUlZckNPTk5rYmJUclMu', //Dein Client-Key sehe "API Zugänge"
    'JDJ5JDEwJFVrS3EvT3Buc2FLZ1BDallvaVVEWk9IZEt2WGQyRzF3SHZiT1hlZkxyVHVjQkRKWlh0dkNh' //Dein Secret-Key sehe "API Zugänge"
);

//Soll ein Proxy-Server verwendet werden
$client->setProxyServer('10.10.2.1',3128, '', '');

//Soll die Verbindung (wenn möglich) mit GZIP komprimiert werden? [ Ist standardmäßig aktiviert ]
$client->setGZIPSupport(true); // true || false

//Einstellung für SSL Verbindungen zum Server, ob das zertifikat geprüft werden soll (verifyhost und verifypeer) [ Ist standardmäßig beides false ]
$client->setSSLOptions(false,false); // verifyhost & verifypeer

echo 'Version des API Interface Moduls: '.$client->getAddonVersion('modul_ai')['version'];
