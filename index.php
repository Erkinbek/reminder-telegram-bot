<?php
/**
 * Created by PhpStorm.
 * User: ErkinPardayev
 * Date: 01.02.2019
 * Time: 14:50
 */

$config = require(__DIR__ . 'app/config/telegram.php');
require(__DIR__ . 'app/controllers/MainController.php');

$app = new MainController();
$app->run($config);
