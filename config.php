<?php

ob_start();
header("Cache-control: private, no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Cache: no-cache");
ini_set('max_execution_time', 90000);
ini_set("memory_limit", -1);

DEFINE('DB_SERVER', 'localhost'); // eg, localhost - should not be empty for productive servers
DEFINE('DB_SERVER_USERNAME', 'root');
DEFINE('DB_SERVER_PASSWORD', '');
DEFINE('DB_DATABASE', 'owncloud');

$conn = mysqli_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE) or die('Error connecting to database.');
mysqli_set_charset($conn, "UTF8");
// Create a constant to store your Slack URL
define('SLACK_WEBHOOK', 'https://hooks.slack.com/services/<your key here>');
define("DEFAULT_CHANNEL", "#general");
define("BASE_URL", "<url where you have your file icons>");
define("IMAGE_EXT", "png");

define("WEBDAV_PATH", "https://docs.hugin.co/remote.php/webdav");
define("WEBDAV_USERNAME", "vimal");
define("WEBDAV_PASSWORD", "patel");
define("TRELLO_TOKEN", "b0e802d8b59197687997160ef4e6661f0fc1c5c2b2d1f8e9d0c30683bd1a4395");
define("TRELLO_KEY", "db7a52bc8461a23a6b6b2a68618abb62");

$channel_lookup = array(
    '/Dir1/Subdir1/Subdir2' => 'subdir2',
    '/Dir1/Subdir1/' => 'subdir1',
    '/Dir1/' => 'dir1',
);
$monitor_used_id = 'email@address.com';

$dir = 'max_activity_id';
$files1 = scandir($dir);
$last_max_id = $files1[2];
