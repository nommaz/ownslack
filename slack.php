<?php

include_once './config.php';

$q = "SELECT * FROM `hg_patel` LIMIT 1";
$res = mysqli_query($conn, $q);
while ($row = mysqli_fetch_assoc($res)) {
    $username = $row['displayname'];

    $icon = BASE_URL . $row['mimetype'] . "." . IMAGE_EXT;
    $msg = $row['type'];

    $linkMimearr = array(4, 10, 12, 20, 100, 30, 31, 44, 42, 46, 55, 59, 60, 62, 76, 80, 81, 82, 85, 91, 99, 107, 108, 110);
    if (in_array($row['mimetype'], $linkMimearr)) {
        $link = "https://docs.hugin.co/index.php/apps/onlyoffice/" . $row['object_id'];
    } else {
        $link = "https://docs.hugin.co/index.php/apps/files/?fileid=" . $row['object_id'];
    }
    $link = "<$link | " . $row['name'] . ">";

    $size = formatBytes($row['size']);
    $channel = '';
    if ($row['type'] == 'file_created' || $row['type'] == 'file_changed') {
        $channel = 'general'; // TODO : get firstname(folder name) from $row['path'] and then lookup the channel name from other table.. if not found then do not pass channel
    }

    sendMessage($username, $msg, $icon, $channel, $link);
}

function sendMessage($username = 'guest', $msg = '', $icon = '', $channel = '', $link = '') {
    // Make your message
    $data = array(
        'text' => $link . " " . $msg,
        "username" => $username,
        "icon_emoji" => $icon,
        "link_names" => 1
    );
    if (trim($channel) != '') {
        $data["channel"] = $channel;
    }
    $message = array('payload' => json_encode($data));
    // Use curl to send your message
    $c = curl_init(SLACK_WEBHOOK);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($c, CURLOPT_POST, true);
    curl_setopt($c, CURLOPT_POSTFIELDS, $message);
    curl_exec($c);
    curl_close($c);
}

function formatBytes($size, $precision = 2) {
    $base = log($size, 1024);
    $suffixes = array('', 'KB', 'MB', 'GB', 'TB');

    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}
