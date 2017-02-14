<?php

include_once './config.php';

$q = " SELECT  max(activity_id) AS activity_id, max(TIMESTAMP) AS timestamp, object_id, displayname, type, ".
     "         oc_filecache.name, FILE AS path, size, oc_filecache.mimetype ".
     " FROM oc_activity ".
     "  INNER JOIN hg_users ON USER = uid ".
     "  INNER JOIN oc_filecache ON object_id = fileid ".
     " WHERE affecteduser = '$monitor_used_id' AND activity_id > $last_max_id ".
     " GROUP BY object_id, displayname, type, name, file , size, mimetype " .
     " ORDER BY activity_id ASC;";

$q = "SELECT * FROM `hg_patel`WHERE affecteduser = '$monitor_used_id' and activity_id > $last_max_id ORDER BY activity_id ASC;";
$res = mysqli_query($conn, $q);
while ($row = mysqli_fetch_assoc($res)) {
    $username = $row['displayname'];

    $icon = BASE_URL . $row['mimetype'] . "." . IMAGE_EXT;

    echo "<pre>activity id = ";
    print_r($row['activity_id']);
    echo "  ---- icon = ";
    print_r($icon);


    $linkMimearr = array(4, 10, 12, 20, 100, 30, 31, 44, 42, 46, 55, 59, 60, 62, 76, 80, 81, 82, 85, 91, 99, 107, 108, 110);
    if (in_array($row['mimetype'], $linkMimearr)) {
        $link = "https://docs.hugin.co/index.php/apps/onlyoffice/" . $row['object_id'];
    } else {
        $link = "https://docs.hugin.co/index.php/apps/files/?fileid=" . $row['object_id'];
    }

    $size = formatBytes($row['size']);
    $channel = '';
    if ($row['type'] == 'file_created' || $row['type'] == 'file_changed') {
        $pathArr = explode("/", $row['path']);
        unset($pathArr[count($pathArr) - 1]);

        $channelKey = implode("/", $pathArr);
        $channelname = findChannelName($channel_lookup, $channelKey . "/");
        $channel = str_replace("#", "", $channelname);
        echo "  ---- Channel Selected = ";
        print_r($channel);
        echo "</pre>";
    }

    $srchType = array('file_created', 'file_changed');
    $rplType = array('created a file', 'changed a file');
    $pretext = str_replace($srchType, $rplType, $row['type']);
    $pathArr = explode("/", $row['path']);
    unset($pathArr[count($pathArr) - 1]);

    $remaining_path = implode("/", $pathArr);

    $footer_msg = $size . " " . $remaining_path;
    $result = sendMessage($username, $pretext, $icon, $channel, $row['name'], $link, $footer_msg);
    if ($result) {
        $files = glob('max_activity_id/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        fopen("max_activity_id/" . $row['activity_id'], 'w');
    }
}

function sendMessage($username = 'guest', $pretext = '', $icon = '', $channel = '', $file_name, $file_link, $footer_msg) {
    // Make your message
    $data = array(
//        'text' => $msg,
        "username" => $username,
//        "icon_url" => $icon,
        "link_names" => true
    );
    if (trim($channel) != '') {
        $data["channel"] = $channel;
    }
    $data['attachments'] = array(
        array(
//            'fallback' => 'Required plain-text summary of the attachment.',
//            "color" => "#ff00ff",
            "pretext" => $pretext,
//            "author_name" => "Bobby Tables",
//            "author_link" => "https://www.google.com",
//            "author_icon" => $icon,
            "title" => $file_name,
            "title_link" => $file_link,
//            "text" => $msg,
//            "fields" => array(
//                "title" => "Priority",
//                "value" => "High",
//                "short" => true
//            ),
//            "image_url" => $icon,
//            "thumb_url" => $icon,
            "footer" => $footer_msg,
            "footer_icon" => $icon
//            "ts" => time()
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
    return $out;
}

function formatBytes($size, $precision = 2) {
    $base = log($size, 1024);
    $suffixes = array('', 'KB', 'MB', 'GB', 'TB');

    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}

function findChannelName($channel_lookup, $path) {
    if (!key_exists($path, $channel_lookup)) {
        $path = rtrim($path, "/");
        $pathArr = explode("/", $path);
        unset($pathArr[count($pathArr) - 1]);
        $channelKey = implode("/", $pathArr);

        return findChannelName($channel_lookup, $channelKey . "/");
    } else {
        return $channel_lookup[$path];
    }
}
