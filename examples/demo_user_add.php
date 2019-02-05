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

//Erstelle neues TSI_User Objekt
$new_tsi_user = new \TSI_Client\Models\TSI_User();

$new_tsi_user->setEmail('test@1234domain.de');
$new_tsi_user->setInitPassword('password');
$new_tsi_user->setLastName('Mustermann');
$new_tsi_user->setFirstName('Max');
$new_tsi_user->setUsername('hammer');
$new_tsi_user->setRoleID(1);
$new_tsi_user->setServer(1, 1);

print_r($client->addTSIUser($new_tsi_user)); //User Erstellen

echo '</pre>';
