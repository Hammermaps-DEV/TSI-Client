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

//Erstelle neues Properties Objekt für den V-Server (Optional)
$new_server_properties = new TSI_Client\Models\TSI_Properties();

//Erstelle den Server mit 16 Slots
$new_server_properties->setMaxClients(16);

//Servername setzen
$new_server_properties->setName('Ein TestServer');

//Wähle zufällig einen Port
$new_server_properties->setPort(rand(9988,11999));

/* ###################################################### */

//Erstelle neues Server Objekt
$new_server = new TSI_Client\Models\TSI_VServer();

//Auf welcher Instanz soll der neue V-Server erstellt werden?
$new_server->setInstanceID(1);

//Setzt die Einstellungen des V-Servers (Optional)
$new_server->setProperties($new_server_properties);

print_r($client->addTSVServer($new_server)); //Server Erstellen

echo '</pre>';
