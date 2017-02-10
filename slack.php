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
        $pathArr = explode("/", $row['path']);
        if($pathArr[0]!=''){
            $channel = $pathArr[0];
        }else if($pathArr[1]!=''){
            $channel = $pathArr[1];
        }
        $channel = str_replace("#", "", $channel);
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
    $data['attachments'] = array(
        array(
            'fallback' => 'Required plain-text summary of the attachment.',
            "color" => "#ff00ff", 
            "pretext" => "Optional text that appears above the attachment block", 
            "author_name" => "Bobby Tables", 
            "author_link" => "https://www.google.com",
            "author_icon" => "http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/sign-check-icon.png", 
            "title" => "Attachment Title",
            "title_link" => "https://www.heatandplumb.com/brochure/page2.pdf",
            "text" => "Optional 1 text that appears within the attachment",
            "fields" => array(
                "title" => "Priority",
                "value" => "High",
                "short" => false
            ),
            "image_url" => "http://blog.iconfinder.com/wp-content/uploads/2014/03/1394141400_eye_black_circle1.png.pagespeed.ce.ly0J4jWC8V.png",
            "thumb_url" => "https://image.flaticon.com/teams/1-freepik.jpg",
            "footer" => "Vimal Patel",
            "footer_icon" => "https://image.flaticon.com/teams/1-freepik.jpg",
            "ts" => time()
        )
    );
    $message = array('payload' => json_encode($data));
    // Use curl to send your message
    $c = curl_init(SLACK_WEBHOOK);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($c, CURLOPT_POST, true);
    curl_setopt($c, CURLOPT_POSTFIELDS, $message);
    $out = curl_exec($c);
    curl_close($c);
}

function formatBytes($size, $precision = 2) {
    $base = log($size, 1024);
    $suffixes = array('', 'KB', 'MB', 'GB', 'TB');

    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}
