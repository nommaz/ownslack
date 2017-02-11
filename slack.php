<?php

include_once './config.php';

$q = "SELECT  activity_id, timestamp, object_id, displayname, type, ".
     "  oc_filecache.name, file as path, size, oc_filecache.mimetype ".
     "FROM oc_activity ".
     "  INNER JOIN hg_users on user = uid ".
     "  INNER JOIN oc_filecache on object_id = fileid ".
     "WHERE affecteduser = '$monitor_used_id' and activity_id > $last_max_id ".
     "ORDER BY activity_id desc;";

$q = "SELECT * FROM `hg_patel`WHERE affecteduser = '$monitor_used_id' and activity_id > $last_max_id ORDER BY activity_id desc;";
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
        unset($pathArr[count($pathArr) - 1]);

        $channelKey = implode("/", $pathArr);
        $channelname = $channel_lookup[$channelKey . "/"];
        
        $channel = str_replace("#", "", $channelname);
    }
    $result = sendMessage($username, $msg, $icon, $channel, $link);
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
    /* $data['attachments'] = array(
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
      ); */
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
