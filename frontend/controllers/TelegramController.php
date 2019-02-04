<?php
/**
 * Created by PhpStorm.
 * User: ErkinPardayev
 * Date: 01.02.2019
 * Time: 17:59
 */

namespace frontend\controllers;

use common\models\Tusers;
use common\models\Reminding;
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
			$user = $this->registerUser($data);
			if($user === true) {
				return;
			}
			$result = $this->registerDate($data);
		}
	}

	public function getMessage()
	{
		$data = Json::decode(file_get_contents('php://input'));
		return $data;
	}

	public function registerUser(Array $data)
	{
		$this->isStarted($data);
		if($this->isNewUser($data['message']['chat'])) {
			$tUser = new Tusers();
			$tUser->name = $data['message']['chat']['first_name'] . " " . $data['message']['chat']['last_name'];
			$tUser->username = $data['message']['chat']['username'];
			$tUser->chat_id = $data['message']['chat']['id'];
			$tUser->save();
			return true;
		}
	}

	public function isStarted(Array $data)
	{
		if($data['message']['text'] == '/start') {
			$this->sendMessage($data['message']['chat']['id'], Yii::$app->params['startMessage']);
		}
	}

	public function isNewUser(Array $user)
	{
		$tUser = Tusers::find()->where(['chat_id' => $user['id']])->one();
		if ($tUser) {
			return false;
		} else {
			return true;
		}
	}

	public function registerDate(Array $data)
	{
		$result = $this->dateValidation($data['message']['text']);
		if (is_array($result)) {
			// ToDO Write to database and send success message
			$model = new Reminding();
			$tUser = Tusers::find()->select('id')->where(['chat_id' => $data['message']['chat']['id']])->one();
			$model->tuser_id = $tUser->id;
			$model->month = $result['month'];
			$model->day = $result['day'];
			$model->comment = $this->getComment($data['message']['text']);
			if($model->save()) {
				$message = Yii::$app->params['savedMessage'];
			}
		} else {
			$message = $result;
		}
		$this->sendMessage($data['message']['chat']['id'], $message);
	}

	public function dateValidation(String $message)
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
				return Yii::$app->params['wrongDate'];
			}
		} else {
			return Yii::$app->params['wrongDateFormat'];
		}
	}

	/**
	 * @param String $message
	 * @return bool|string
	 */
	public function getComment(String $message)
	{
		$comment = substr($message, 6);
		return $comment;
	}

	public function sendMessage(Int $chat_id, String $message)
	{
		Yii::$app->telegram->sendMessage([
			'chat_id' => $chat_id,
			'text' => $message
		]);
	}
}