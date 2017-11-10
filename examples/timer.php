<?php
use Beanbun\Beanbun;

require_once __DIR__ . '/vendor/autoload.php';

$beanbun = new Beanbun;
$beanbun->name = 'qiubai';
$beanbun->count = 3;
$beanbun->seed = 'https://www.qiushibaike.com/';
$beanbun->max = 30;
$beanbun->logFile = __DIR__ . '/qiubai_access.log';
$beanbun->urlFilter = [
	'/https:\/\/www.qiushibaike.com\/8hr\/page\/(\d*)\//',
];
// 设置队列
$beanbun->setQueue('redis', [
	'host' => '127.0.0.1',
	'port' => '6379',
]);
//添加自定义定时器
$beanbun->timerWorker = function ($beanbun) {
	echo "add timers\n";
	Beanbun::timer(1, function () use ($beanbun) {
		echo 'timer 1s ' . time() . "\n";
	});
	Beanbun::timer(3, function () use ($beanbun) {
		echo 'timer 3s ' . time() . "\n";
	});
};
$beanbun->afterDownloadPage = function ($beanbun) {
	file_put_contents(__DIR__ . '/' . md5($beanbun->url), $beanbun->page);
};
$beanbun->start();