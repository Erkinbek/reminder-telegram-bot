<?php
/**
 * Created by PhpStorm.
 * User: ErkinPardayev
 * Date: 04.02.2019
 * Time: 14:13
 */

namespace frontend\controllers;

use common\models\Reminding;
use common\models\Tusers;
use DateTime;
use Yii;
use yii\web\Controller;

class ReminderController extends Controller
{
	public function actionIndex()
	{
		$tomorrow = date('d.m',strtotime("+1 days"));
		$day = (int)ltrim(substr($tomorrow, 0, 2), 0);
		$month = (int)ltrim(substr($tomorrow, 3, 2), 0);
		$notes = Reminding::find()->where(['month' => $month, 'day' => $day])->all();
		if (!$notes) {
			return 'Nothing found';
		}
		foreach ($notes as $note) {
			$this->sendMessage($note);
		}
		return 'All messages are send';
	}

	public function sendMessage($note)
	{
		$user = Tusers::find()->where(['id' => $note->tuser_id])->one();

		$message = $this->createMessage($note, $user->name);
		Yii::$app->telegram->sendMessage([
			'chat_id' => $user->chat_id,
			'text' => $message
		]);
	}

	public function createMessage($note, $user)
	{

		$monthNum  = $note->month;
		$dateObj   = DateTime::createFromFormat('!m', $monthNum);
		$monthName = $dateObj->format('F');
		$date = $note->day . " - " . $monthName;
		$remindText = "Hello " . $user . ". Tomorrow is: " . $date . " " . $note->comment;

		return $remindText;
	}
}