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

//Holen der Gruppe
$roles = $client->getTSIRole(2); //ID der Gruppe

echo '<pre>';
echo 'Gruppe: '.$roles->getName(); //Gibt den Gruppennamen aus
echo '<br>';
//Prüft ob eine Gruppe ein Recht hat
echo 'Hat das Recht "tsi_virtualserver_ban_list": '.
    ($roles->getTSIPermission('tsi_virtualserver_ban_list') ? 'Ja' : 'Nein');
echo '<br>';
print_r($roles->getTSIPermissionsList()); //Ausgabe aller existierenden Rechte (TSI)
echo '<br>';
print_r($roles->getModifyList()); //Ausgabe aller existierenden Rechte (Server)
echo '<br>';
print_r($roles->getChannelModifyList()); //Ausgabe aller existierenden Rechte (Channel)
echo '<br>';
echo '</pre>';

