<?php
/**
 * Created by PhpStorm.
 * User: ErkinPardayev
 * Date: 01.02.2019
 * Time: 17:59
 */

namespace frontend\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\Controller;

class TelegramController extends Controller
{
	public $enableCsrfValidation = false;

	public function actionIndex()
	{
		if(Yii::$app->request->isPost) {
			$this->getMessage(file_get_contents('php://input'));
		}
	}

	public function getMessage($data)
	{
		$data = Json::decode($data);
		var_dump($data);
	}

	public function isNewUser($user)
	{

	}
}