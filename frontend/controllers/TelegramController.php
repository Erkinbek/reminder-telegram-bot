<?php
/**
 * Created by PhpStorm.
 * User: ErkinPardayev
 * Date: 01.02.2019
 * Time: 17:59
 */

namespace frontend\controllers;

use common\models\Tusers;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;

class TelegramController extends Controller
{
	public $enableCsrfValidation = false;

	public function actionIndex()
	{
		if(Yii::$app->request->isPost) {
			$data = $this->getMessage();
			$this->registerUser($data);
			$result = $this->registerDate($data);
			var_dump($result); exit();
		}
	}

	public function getMessage()
	{
		$data = Json::decode(file_get_contents('php://input'));
		return $data;
	}

	public function registerUser($data)
	{
		if($this->isNewUser($data['message']['chat'])) {
			$tUser = new Tusers();
			$tUser->name = $data['message']['chat']['first_name'] . " " . $data['message']['chat']['last_name'];
			$tUser->username = $data['message']['chat']['username'];
			$tUser->chat_id = $data['message']['chat']['id'];
			$tUser->save();
		} else {
			return;
		}
	}

	public function isNewUser($user)
	{
		$tUser = Tusers::find()->where(['chat_id' => $user['id']])->one();
		if ($tUser) {
			return false;
		} else {
			return true;
		}
	}

	public function registerDate($data)
	{
		$result = $this->dateValidation($data['message']['text']);
		if (is_array($result)) {
			// ToDO Write to database and send success message
		} else {
			$this->sendMessage($data['message']['chat']['id'], $result);
		}
	}

	public function dateValidation($message)
	{
		$date = str_replace(" ", "", substr($message, 0, 5));
		$date = explode(".", $date);
		if(count($date) == 2) {
			if (checkdate($date[1], $date[0], 2020)) {
				return [
					'day' => (int)ltrim($date[0], '0'),
					'month' => (int)ltrim($date[1], '0'),
				];
			} else {
				return "Wrong date, please send me valid date";
			}
		} else {
			return "Wrond date format. Please send me date in valid format for me. Send me date and comment for this date. Example 07.01 Creator of bot's birthday. (This is 7th January)";
		}
	}

	public function sendMessage($chat_id, $message)
	{
		Yii::$app->telegram->sendMessage([
			'chat_id' => $chat_id,
			'text' => $message
		]);
	}
}