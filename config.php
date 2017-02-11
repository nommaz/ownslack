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
    '/Legal/' => 'nommaz',
    '/Test/' => 'qa',
    '/Onay/Hugin FP-300/' => 'devecr',
    '/Onay/Hugin FP-202/' => 'devecr',
    '/Onay/ISO/' => 'opstsm',
    '/Onay/TSM/' => 'opstsm',
    '/Onay/Verifone Vx675/' => 'devvx',
    '/Onay/YN PYK/' => 'devhw',
    '/Üretim/' => 'uretim',
    '/Devops/' => 'opstsm',
    '/Pazarlama/' => 'pazar',
    '/Donanım/' => 'devhw',
    '/Rakip/' => 'pazar',
    '/Finance/' => 'nommaz',
    '/Resimler/' => 'genel',
    '/Genel/' => 'genel',
    '/Satış/' => 'pazar',
    '/Ithalat/' => 'nommaz',
    '/Servis/' => 'servis',
    '/Yazılım/Release/CEPOS/' => 'devbbs',
    '/Yazılım/Release/FPU245/' => 'devbbs',
    '/Yazılım/Release/CEPOSNG/' => 'devbbs',
    '/Yazılım/Release/FT202/' => 'devecr',
    '/Yazılım/Release/CRM/' => 'devcrm',
    '/Yazılım/Release/GIBSimulator/' => 'devtsm',
    '/Yazılım/Release/Speedy/' => 'devbbs',
    '/Yazılım/Release/CertLoader' => 'devtsm',
    '/Yazılım/Release/GuiPOS/' => 'devbbs',
    '/Yazılım/Release/Turkmen/' => 'devvx',
    '/Yazılım/Release/ECR_675/' => 'devvx',
    '/Yazılım/Release/HuginTPS/' => 'devtsm',
    '/Yazılım/Release/Utility/' => 'devbbs',
    '/Yazılım/Release/HuginTSM/' => 'devtsm',
    '/Yazılım/Release/Vfxbridger_raspberry/' => 'devbbs',
    '/Yazılım/Release/EFTPOS/' => 'devpp',
    '/Yazılım/Release/JibeInstaller/' => 'devpp',
    '/Yazılım/Release/Vx675_hugin_sign/' => 'devvx',
    '/Yazılım/Release/EJReader/' => 'devpp',
    '/Yazılım/Release/JibeProTest/' => 'devpp',
    '/Yazılım/Release/X900/ ' => 'devkf',
    '/Yazılım/Release/FP300/' => 'devbbs',
    '/Yazılım/Release/Order_Bridge/' => 'devbbs',
    '/Yazılım/Release/hgnsoftwaremanager/' => 'devpp',
    '/Yazılım/Release/FPU Servis/' => 'devbbs',
    '/Yazılım/Reference/' => 'dev',
    '/Yazılım/_CRM/' => 'devcrm',
    '/Yazılım/_Pump/' => 'devhw',
    '/Yazılım/_X900/' => 'devkf',
    '/Yazılım/Project/' => 'dev',
    '/Yazılım/_ECR/' => 'devecr',
    '/Yazılım/_TSM/' => 'devtsm',
    '/Yazılım/_ZRaporum/' => 'devcrm',
    '/Yazılım/Recruitment/' => 'dev',
    '/Yazılım/_BBS/' => 'devbbs',
    '/Yazılım/_Platform/' => 'devpp',
    '/Yazılım/_VX675/' => 'devvx',
    '/Yazılım/' => 'dev'
);
$monitor_used_id = 'vedat@hugin.com.tr';

$dir = 'max_activity_id';
$files1 = scandir($dir);
$last_max_idorder = $files1[2];
