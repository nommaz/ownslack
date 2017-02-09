<?php

include_once './config.php';

$q = "SELECT * FROM `hg_patel` LIMIT 1";
$res = mysqli_query($conn, $q);
while ($row = mysqli_fetch_assoc($res)) {
    sendMessage($row['name'] . " sending file at " . time());
}

function sendMessage($msg = '') {
    // Make your message
    $data = array(
        'text' => $msg,
        "username" => "Vedant Test",
        "icon_emoji" => ":ghost:",
        "channel" => DEFAULT_CHANNEL,
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
