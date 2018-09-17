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

$users = $client->getTSIUsers();

echo '<pre><p>##################################################################</p>';

//Jetzt eine Schleife der vorhandenen User
foreach ($users as $user) {
    //Die Server stehen als "TSI_User Objekt" zur verfügung:
    if($user instanceof TSI_Client\TSI_User) {
        echo 'User-ID: '.$user->getUserID();
        echo '<br>';
        echo 'Username: '.$user->getUsername();
        echo '<br>';
        echo 'FirstName: '.$user->getFirstName();
        echo '<br>';
        echo 'LastName: '.$user->getLastName();
        echo '<br>';
        echo 'E-Mail: '.$user->getEmail();
        echo '<br>';
        echo '<p>##################################################################</p>';
    }
}

echo '</pre>';