<?php

ob_start();
header("Cache-control: private, no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Cache: no-cahce");
ini_set('max_execution_time', 90000);
ini_set("memory_limit", -1);

DEFINE('DB_SERVER', 'localhost'); // eg, localhost - should not be empty for productive servers
DEFINE('DB_SERVER_USERNAME', 'root');
DEFINE('DB_SERVER_PASSWORD', '');
DEFINE('DB_DATABASE', 'owncloud');


$conn = mysqli_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE) or die('Error connecting to database.');

$q = "SELECT * FROM `hg_patel` LIMIT 1";
$res = mysqli_query($conn, $q);
while ($row = mysqli_fetch_assoc($res)) {
    sendMessage($row['name'] . " sending file at " . time());
}

function sendMessage($msg = '') {
    // Create a constant to store your Slack URL
    define('SLACK_WEBHOOK', 'https://hooks.slack.com/services/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');
    // Make your message
    $data = array(
        'text' => $msg,
        "username" => "Vedant Test",
        "icon_emoji" => ":ghost:",
        "channel" => "#random",
        "link_names" => 1
    );
    $message = array('payload' => json_encode($data));
    // Use curl to send your message
    $c = curl_init(SLACK_WEBHOOK);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($c, CURLOPT_POST, true);
    curl_setopt($c, CURLOPT_POSTFIELDS, $message);
    curl_exec($c);
    curl_close($c);
}
