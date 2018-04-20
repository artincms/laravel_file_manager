<?php

return [

	/* Important Settings */

	// ======================================================================
	// never remove 'web', . just put your middleware like auth or admin (if you have) here. eg: ['web','auth']
	'private_middlewares' => ['web'],
	'public_middlewares' => ['web'],
	// you can change default route from sms-admin to anything you want
	'private_route_prefix' => 'LFM',
	'public_route_prefix' => 'LFM',
	// SMS.ir Api Key
	'api-key' => env('SMSIR-API-KEY','Your api key'),
	// SMS.ir Secret Key
	'secret-key' => env('SMSIR-SECRET-KEY','Your secret key'),
	// Your sms.ir line number
	'line-number' => env('SMSIR-LINE-NUMBER','Your Sms.ir Line Number'),
	// ======================================================================

	// set true if you want log to the database
	'db-log' => true,

	/* Admin Panel Title */
	'title' => 'مدیریت پیامک ها',
	// How many log you want to show in sms-admin panel ?
	'in-page' => '15'

];