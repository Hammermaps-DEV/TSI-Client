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
 * TSI_Resellers Objekt                 => Datei: TSI_Resellers_Interface.php
 * TSI_MultiClient Objekt               => Datei: TSI_MultiClient_Interface.php
 */

//Client erstellen
$client = new TSI_Client\TSI_Client(
    'https://meine_domain.de', //Die Volle-URL zur TSI Installation mit API-Erweiterung
    'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX', //Dein Client-Key sehe "API Zugänge"
    'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX' //Dein Secret-Key sehe "API Zugänge"
);

//Die Instanz-ID deines TeamSpeak / TeaSpeak Servers
$instance_id = 1;

//Holen der Server als Array
$array_of_servers = $client->getTSVServerList($instance_id);

echo '<pre><p>##################################################################</p>';

//Jetzt eine Schleife der vorhandenen Server
foreach ($array_of_servers as $server) {

    //Die Server stehen als "TSI_VServer Objekt" zur verfügung:
    if($server instanceof TSI_Client\Models\TSI_VServer) {
        echo 'VServer ID: '.$server->getServerID();
        echo '<br>';
        echo 'Name: '.$server->getServerName();
        echo '<br>';
        echo 'Platform: '.$server->getPlatform();
        echo '<br>';
        echo 'Version: '.$server->getVersion();
        echo '<br>';
        echo 'Max Clients: '.$server->getMaxClients();
        echo '<br>';
        echo 'Clients Online: '.$server->getClientsOnline();
        echo '<br>';
        echo 'Created: '.$server->getCreatedTime();
        echo '<br>';
        echo 'Laufzeit: '.$server->getUptime();
        echo '<br>';
        echo '<p>##################################################################</p>';
    }
}

echo '</pre>';