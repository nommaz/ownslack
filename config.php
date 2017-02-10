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
mysqli_set_charset($conn, "UTF8");
// Create a constant to store your Slack URL
define('SLACK_WEBHOOK', 'https://hooks.slack.com/services/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');
define("DEFAULT_CHANNEL", "#general");
define("BASE_URL", "https://docs.hugin.co/core/img/fileicons/");
define("IMAGE_EXT", "png");

$channel_lookup = array(
    'Donanım' => 'devhw',
    'Devops' => 'opstsm',
    'Pazarlama' => 'pazar',
    'Satış' => 'pazar'
);
