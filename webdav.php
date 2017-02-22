<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require 'vendor/autoload.php';



include_once './config.php';

use Trello\Client;
use Trello\Manager;

$q = " SELECT  max(activity_id) AS activity_id, max(TIMESTAMP) AS timestamp, object_id, displayname, type, " .
        "         oc_filecache.name, FILE AS path, size, oc_filecache.mimetype " .
        " FROM oc_activity " .
        "  INNER JOIN hg_users ON USER = uid " .
        "  INNER JOIN oc_filecache ON object_id = fileid " .
        " WHERE affecteduser = '$monitor_used_id' AND activity_id > $last_max_id " .
        " GROUP BY object_id, displayname, type, name, file , size, mimetype " .
        " ORDER BY activity_id ASC;";

$q = "SELECT * FROM `hg_patel`WHERE affecteduser = '$monitor_used_id' and activity_id =247032 ORDER BY activity_id ASC;"; // query uses static id to check only with one record.


$res = mysqli_query($conn, $q);
while ($row = mysqli_fetch_assoc($res)) {
    if ($row['path'] != '' && $row['name'] != '') {

        $c = curl_init(WEBDAV_PATH . $row['path'] . "/" . $row['name']);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_POST, false);
        curl_setopt($c, CURLOPT_USERPWD, WEBDAV_USERNAME . ":" . WEBDAV_PASSWORD);
        curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $out = curl_exec($c);
        curl_close($c);

        $outArr = explode("https://trello.com", $out);

        /* Variable declaration */
        $cardId = '';
        $cardUrl = '';
        $description = '';

        if (count($outArr) >= 1) {
            $requiredStr = $outArr[1];
            $reqArr = explode("\n", $requiredStr);
            if (count($reqArr) > 1) {
                $cardUrl = $reqArr[0];
                $cardUrl = str_replace("/c/", "", $cardUrl);
                $cardUrl = explode("/", $cardUrl);
                $cardId = $cardUrl[0];

                for ($index = 1; $index < count($reqArr); $index++) {
                    if (trim($reqArr[$index]) != '') {
                        $description.=trim($reqArr[$index]) . "\n";
                    }
                }
            }
        }

        /* TODO - this id is static for testing  - REMOVE when go live */
        $cardId = '5pJ8RSaJ';

        if ($cardId != '') {
            $client = new Client();
            $client->authenticate(TRELLO_KEY, TRELLO_TOKEN, Client::AUTH_URL_CLIENT_ID);
            $manager = new Manager($client);
            $card = $manager->getCard($cardId);
            $card->setDescription($description)->save();
        }
    }
}
?>