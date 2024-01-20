<?php
require '../app/ui/bootstrap.php';
require '../app/ui/CameraMarketLinkApp.php';

$app = new CameraMarketLinkApp(true);
$app->run();
