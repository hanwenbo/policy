# policy
用于验证、解析Policy结构和语法
## 安装
```bash
composer require hanwenbo/policy
```
## 使用方法
```php

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
```
## 思路说明

假设是一个FaShop的商城项目

权限应该分为3个表，一个组可以拥有多个策略，为了方便开发者可以将每个模块的策略预置到数据库里，比如订单模块，商品模块，在给权限组分权限的时候，多选这些模块（策略），当验证的时候，查询出来所有的策略，扔给该库进行验证。

auth_group 角色组

auth_group_policy 角色组拥有的 policy_id 集合

auth_policy 权限策略表

策略表里要有id、存放json的字段，json格式如下：
```json
{
  "Statement": [
    {
      "Effect": "Allow",
      "Action": [
        "goods/*",
        "goods/list"
      ]
    },
    {
      "Effect": "Allow",
      "Action": [
        "goods/*",
        "goods/list"
      ]
    },
    {
      "Effect": "Allow",
      "Action": [
        "goods/*",
        "goods/list"
      ]
    }
  ]
}

```
## TODO
- 前端路由根据规则生成示例
- 接口应该返回给前端策略列表的示例
- 前端policy验证的库