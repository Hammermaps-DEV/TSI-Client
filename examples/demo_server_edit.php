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
 */

//Client erstellen
$client = new TSI_Client\TSI_Client(
    'https://meine_domain.de', //Die Volle-URL zur TSI Installation mit API-Erweiterung
    'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX', //Dein Client-Key sehe "API Zugänge"
    'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX' //Dein Secret-Key sehe "API Zugänge"
);

$instance_id = 1; //Instanz ID
$vserver_id = 1; //Virtual Server ID

//Alten Server abrufen und TSI_VServer Objekt erstellen
$edit_server = $client->getTSVServer($instance_id,$vserver_id);

//Holen des Properties Objekt für den V-Server
$edit_server_properties = $edit_server->getProperties();

//Ändere von 16 slots auf zbs. 32
$edit_server_properties->setMaxClients(32);

//Servername setzen
$edit_server_properties->setName('Umbenannter TestServer');

//Setzt die Einstellungen des V-Servers
$edit_server->setProperties($edit_server_properties);

//Änderungen an Server senden
$client->editTSVServer($edit_server);
