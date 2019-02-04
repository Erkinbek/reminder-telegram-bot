<?php
return [
	'aliases' => [
		'@bower' => '@vendor/bower-asset',
		'@npm'   => '@vendor/npm-asset',
	],
	'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
	'components' => [
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],
		'telegram' => [
			'class' => 'aki\telegram\Telegram',
			'botToken' => 'YOUR BOT API TOKEN',
		]
	],
];
