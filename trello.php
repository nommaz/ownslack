<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require 'vendor/autoload.php';
include_once './config.php';

use Trello\Client;
use Trello\Manager;

/* Variable declaration */
$cardId = '5pJ8RSaJ';   //this card is from " changed label change events in trello " 

if ($cardId != '') {

    $client = new Client();
    $client->authenticate(TRELLO_KEY, TRELLO_TOKEN, Client::AUTH_URL_CLIENT_ID);
    $manager = new Manager($client);
    $card = $manager->getCard($cardId);
    $cardName = $card->getName();
    $card_url = $card->getShortUrl();
    $boradId = $card->getBoardId();


    $filecontent = '<img style="float: right;" src="https://docs.hugin.co/l">

Owncloud markdown files to Trello card sync
===========================================

Project OwnTrello
-----------------

| **Author** |  **Date**  | **Ver.** | **Note** |
| ---------- |:----------:|:--------:| -------- |
| Vedat Nommaz | 2017-MM-DD | 0.1  | Document created |
| |            |     | - |
| |            |     | - |

[](' . $card_url . ')

###  1. Introduction
Hugin uses Trello.com system to assign tasks to developers. Requirements are currently written as Word documents and are attached to Trello cards as Owncloud links.';

    $boardData = $manager->getBoard($boradId);
    $userfriendlyBoradId = $boardData->getShortLink();
    if ($userfriendlyBoradId != '') {
        $ownCloundPath = $trello_board_owncloud_path[$userfriendlyBoradId];
        $ownCloundPath = trim($ownCloundPath, "/");
        $file_path_str = $cardName . $webdav_file_ext;
        // generating file at owncloud server if not exist :: START
        $url = str_replace(' ', '%20', WEBDAV_PATH . "/" . $ownCloundPath . "/" . $file_path_str);

        $c1 = curl_init($url);
        curl_setopt($c1, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c1, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c1, CURLOPT_USERPWD, WEBDAV_USERNAME . ":" . WEBDAV_PASSWORD);
        curl_setopt($c1, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $out1 = curl_exec($c1);
        curl_close($c1);

        if (strpos($out1, 'not be located') !== false) {

            $fh = fopen($file_path_str, "w+");
            fwrite($fh, $filecontent);
            fclose($fh);

            $c2 = curl_init($url);
            curl_setopt($c2, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($c2, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($c2, CURLOPT_PUT, true);
            curl_setopt($c2, CURLOPT_USERPWD, WEBDAV_USERNAME . ":" . WEBDAV_PASSWORD);
            curl_setopt($c2, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

            $fh_res = fopen($file_path_str, 'r');
            curl_setopt($c2, CURLOPT_INFILE, $fh_res);
            curl_setopt($c2, CURLOPT_INFILESIZE, filesize($file_path_str));
            curl_setopt($c2, CURLOPT_BINARYTRANSFER, TRUE); // --data-binary

            $out2 = curl_exec($c2);
            curl_close($c2);
            fclose($fh_res);
            unlink($file_path_str);
        }
        // generating file at owncloud server if not exist :: END
    }
}
?>