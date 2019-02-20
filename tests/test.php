<?php
require_once '../vendor/autoload.php';

$policyData = [
	"Statement" => [
		[
			"Effect" => "Allow",
			"Action" => ["goods/*", "goods/list"],
		],
		[
			"Effect" => "Allow",
			"Action" => ["goods/*", "goods/list"],
		],
		[
			"Effect" => "Allow",
			"Action" => ["goods/*", "goods/list"],
		],
	],
];
$policy = new \hanwenbo\policy\Policy();
$policy->addPolicy( new \hanwenbo\policy\RequestBean\Policy( $policyData ) );
// 可以添加多组，目的：一个用户属于多个角色组的时候，或者一个角色组对应多个存储的policy的时候
$policy->addPolicy( new \hanwenbo\policy\RequestBean\Policy( $policyData ) );
$result = $policy->verify( 'goods/list' );
var_dump( $result );