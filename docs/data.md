# RAM

参考：https://help.aliyun.com/document_detail/28627.html

RAM (Resource Access Management) 是为客户提供的用户身份管理与资源访问控制服务。使用 RAM，您可以创建、管理 RAM 用户（比如员工、系统或应用程序），并可以控制这些 RAM 用户对资源的操作权限。当您的企业存在多用户协同操作资源时，使用 RAM 可以让您避免与其他用户共享云账号密钥，按需为用户分配最小权限，从而降低企业信息安全风险。

## 使用 RAM 进行身份管理和资源访问控制

RAM 允许在一个云账户下创建并管理多个用户身份，并允许给单个身份或一组身份分配不同的权限策略，从而实现不同用户拥有不同的云资源访问权限。

## 用户身份

RAM 用户身份是指任意通过控制台或 OpenAPI 操作资源的人、系统或应用程序。为了支持多种应用场景的身份管理，RAM 支持两种不同的用户身份类型：RAM 用户和 RAM 角色。

- RAM 用户：一种实体身份，有确定的身份 ID 和身份认证密钥，它通常与某个确定的人或应用程序一一对应。
- RAM 角色：一种虚拟身份，有确定的身份 ID，但没有确定的身份认证密钥。


- 与当前云账号下的 RAM 用户关联。
- 与其它云账号下的 RAM 用户关联。
- 与阿里云服务（EMR/MTS/…）关联。
- 与外部实体身份（如企业本地账号）关联。

## 权限策略

RAM 允许在云账号下创建并管理多个权限策略，每个权限策略本质上是一组权限的集合。管理员可以将一个或多个权限策略分配给 RAM 用户（包括 RAM 用户和 RAM 角色）。

RAM 权限策略语言可以表达精细的授权语义，可以指定对某个 API-Action 和 Resource-ID 授权，也可以支持多种限制条件（源 IP、访问时间、多因素认证等）。

## 云账户与 RAM 用户的关系

- 从归属关系来看，云账户与 RAM 用户是一种主子关系。
  - 云账户是阿里云资源归属、资源使用计量计费的基本主体。
  - RAM 用户只能存在于某个云账户下的 RAM 实例中。RAM 用户不拥有资源，在被授权操作时所创建的资源归属于主账户；RAM 用户不拥有账单，被授权操作时所发生的费用也计入主账户账单。
- 从权限角度来看，云账户与 RAM 用户是一种 root 与 user 的关系（类比 Linux系统）。
  - Root 对资源拥有一切操作控制权限。
  - User 只能拥有被 root 所授予的某些权限，而且 root 在任何时刻都可以撤销 user 身上的权限。

## 使用 RAM 进行企业级云资源管理

- 简单管理每个操作人员（或应用）的账号及权限。
- 不需要分别核算每个操作人员（或应用）的成本和费用。



# Policy 

参考：https://help.aliyun.com/document_detail/93738.html?spm=a2c4g.11186623.6.589.17fc5530OglQNs

Policy 基本元素是权限策略的基本组成部分，RAM 中使用权限策略来描述授权的具体内容，掌握 Policy 基本元素的基本知识可以更合理的使用权限策略。

## Policy 基本元素

| 元素名称              | 描述                                            |
| --------------------- | ----------------------------------------------- |
| 效力（Effect）        | 授权效力包括两种：允许（Allow）和拒绝（Deny）。 |
| 操作（Action）        | 操作是指对具体资源的操作。                      |
| 资源（Resource）      | 资源是指被授权的具体对象。                      |
| 限制条件（Condition） | 限制条件是指授权生效的限制条件。                |

## Policy 元素使用规则

- 效力（Effect）：取值 为 Allow 或 Deny，例如：`"Effect": "Allow"`。

- 操作（Action）：Action 支持多值，取值为云服务所定义的 API 操作名称。

  说明操作是指对具体资源的操作，多数情况下 Action 与云产品的 API 一一对应，但也有例外。各产品支持的 Action 列表请参阅各产品关于 RAM 授权的文档。

  格式：

  ```
  <service-name>:<action-name>
  ```

  - `service-name`: 产品名称。例如：shop 等。
  - `action-name: service`：相关的 API 操作接口名称。

  描述样例：

  ```
  "Action": ["oss:ListBuckets", "ecs:Describe*", "rds:Describe*"]
  ```

- 资源（Resource）

  Resource 通常指资源，即操作对象。

  格式：

  ```
  acs:<service-name>:<region>:<account-id>:<relative-id>
  ```

  - `shop`: 商城系统。
  - `im`: FaShop的IM系统。
  - `region`: 地域信息。如果不支持该项，可以使用通配符`*`来代替。
  - `account-id`: 账号 ID。例如：`1234567890123456`，可以用`*`代替。
  - `relative-id`: 与服务相关的资源描述部分，其语义由具体服务指定。这部分的格式支持树状结构（类似文件路径）。以 oss 为例，表示一个 OSS 对象的格式为：`relative-id = “mybucket/dir1/object1.jpg”` 。

  描述样例：

  ```
  "Resource": ["acs:ecs:*:*:instance/inst-001", "acs:ecs:*:*:instance/inst-002", "acs:oss:*:*:mybucket", "acs:oss:*:*:mybucket/*"]
  ```

- 限制条件（Condition）：

  图 1. 条件块判断逻辑
  [![条件块判断逻辑](http://static-aliyun-doc.oss-cn-hangzhou.aliyuncs.com/assets/img/23769/155005795538714_zh-CN.png)](http://static-aliyun-doc.oss-cn-hangzhou.aliyuncs.com/assets/img/23769/155005795538714_zh-CN.png) 

  逻辑说明：

  - 条件满足：一个条件关键字可以指定一个或多个值，在条件检查时，如果条件关键字的值与指定值中的某一个相同，即可判定条件满足。
  - 条件子句满足：同一条件操作类型的条件子句下，若有多个条件关键字，所有条件关键字必须同时满足，才能判定该条件子句满足。
  - 条件块满足：条件块下的所有条件子句同时满足的情况下，才能判定该条件块满足。

  **条件操作类型**

  | 条件操作类型              | 支持类型                                                     |
  | ------------------------- | ------------------------------------------------------------ |
  | 字符串类型（String）      | StringEquals<br />StringNotEquals<br />StringEqualsIgnoreCase<br />StringNotEqualsIgnoreCase<br />StringLikeStringNotLike |
  | 数字类型（Numeric）       | NumericEquals<br />NumericNotEquals<br />NumericLessThan<br />NumericLessThanEquals<br />NumericGreaterThan<br />NumericGreaterThanEquals |
  | 日期类型（Date and time） | DateEquals<br />DateNotEquals<br />DateLessThan<br />DateLessThanEquals<br />DateGreaterThan<br />DateGreaterThanEquals |
  | 布尔类型（Boolean）       | Bool                                                         |
  | IP 地址类型（IP address） | IpAddress<br />NotIpAddress                                  |

  **条件关键字**

  ```
  acs:<condition-key>
  ```

  ```
  <service-name>:<condition-key>
  ```

  表 1. 通用条件关键字

  | 通用条件关键字        | 类型          | 说明                                                         |
  | --------------------- | ------------- | ------------------------------------------------------------ |
  | `acs:CurrentTime`     | Date and time | Web Server 接收到请求的时间。以 ISO 8601 格式表示，例如：`2012-11-11T23:59:59Z`。 |
  | `acs:SecureTransport` | Boolean       | 发送请求是否使用了安全信道。例如：HTTPS。                    |
  | `acs:SourceIp`        | IP address    | 发送请求时的客户端 IP 地址。                                 |
  | `acs:MFAPresent`      | Boolean       | 用户登录时是否使用了多因素认证。                             |

  | 产品名称 | 条件关键字   | 类型   | 说明                    |
  | -------- | ------------ | ------ | ----------------------- |
  | OSS      | `oss:Prefix` | String | OSS Object 名称的前缀。 |

## 权限策略样例

以下权限策略的含义：允许对 OSS 的 samplebucket 进行只读操作，限制条件：请求者的 IP 来源为 42.160.1.0。

```json
{
    "Version": "1",
    "Statement":[{
        "Effect": "Allow",
        "Action": ["shop:admin/goods/*", "shop:admin/order/list"],
        "Resource": ["shop:Upload/*"],
        "Condition":
        {
            "IpAddress":
            {
                "acs:SourceIp": "42.160.1.0"
            }
        }
    }]
}
```

Action支持

- *
- admin/*
- admin/goods/*
- admin/goods/list

> 统一为小写，如果存在大写也会被转成小写

TODO

- 单独Action如何加Condition，比如成员只能看category_id = 5下的商品

# Policy 结构和语法

本文介绍 RAM 中权限策略的语法结构和规则，帮助您正确理解 Policy 语法，以完成创建或更新权限策略。

## Policy 结构

Policy 结构包括：版本号及授权语句（Statement）列表。

图 1. Policy 结构
[![Policy 结构](http://static-aliyun-doc.oss-cn-hangzhou.aliyuncs.com/assets/img/23770/155005800914403_zh-CN.png)](http://static-aliyun-doc.oss-cn-hangzhou.aliyuncs.com/assets/img/23770/155005800914403_zh-CN.png) 

## 运用 Policy 语法的前提条件

- Policy 字符：

  - Policy 中所包含的 JSON 字符：`{ } [ ] " , :`。
  - 描述语法使用的特殊字符：`= < > ( ) |`。

- Policy 字符使用规则：

  - 当一个元素允许多值时，使用逗号和省略号来表达。例如：

    ```
    [ <action_string>, <action_string>, ...]
    ```

    **说明** 在所有支持多值的元素中，使用单值进行表达也是有效的，且两种表达方式效果相同。例如：`"Action": [<action_string>]` 和 `"Action": <action_string>`

  - 元素带有问号表示此元素是一个可选元素。例如：`<condition_block?>`。

  - 多值之间用竖线 `|`隔开，表示取值只能选取这些值中的某一个。例如：`("Allow" | "Deny")`。

  - 使用双引号的元素，表示此元素是文本串。例如： `<version_block> = "Version" : ("1")`。

## Policy 语法

```xml
policy  = {
     <version_block>,
     <statement_block>
}
<version_block> = "Version" : ("1")
<statement_block> = "Statement" : [ <statement>, <statement>, ... ]
<statement> = { 
    <effect_block>,
    <action_block>,
    <resource_block>,
    <condition_block?>
}
<effect_block> = "Effect" : ("Allow" | "Deny")  
<action_block> = ("Action" | "NotAction") : 
    ("*" | [<action_string>, <action_string>, ...])
<resource_block> = ("Resource" | "NotResource") : 
    ("*" | [<resource_string>, <resource_string>, ...])
<condition_block> = "Condition" : <condition_map>
<condition_map> = {
  <condition_type_string> : { 
      <condition_key_string> : <condition_value_list>,
      <condition_key_string> : <condition_value_list>,
      ...
  },
  <condition_type_string> : {
      <condition_key_string> : <condition_value_list>,
      <condition_key_string> : <condition_value_list>,
      ...
  }, ...
}  
<condition_value_list> = [<condition_value>, <condition_value>, ...]
<condition_value> = ("String" | "Number" | "Boolean")
```

- 版本：当前支持的 Policy 版本为 1。

- 授权语句：一个 Policy 可以有多条授权语句。

  - 每条授权语句的效力是`Allow`或`Deny`


>  一条授权语句中，Action（操作）和 Resource（资源）都支持多值。


  - 每条授权语句都支持独立的限制条件（Condition）。

    **说明** 一个条件块可以支持多种条件操作类型，以及多种条件的逻辑组合。

- Deny 优先原则： 一个用户可以被授予多个权限策略，当这些权限策略同时包含`Allow`和 `Deny`时，遵循 Deny 优先原则。

- 元素取值：

  - 当取值为数字（Number）或布尔值（Boolean）时，与字符串类似，需要使用双引号。

  - 当元素取值为字符串值（String）时，支持使用`*`和`?`

    进行模糊匹配。

    - ` *`代表 0 个或多个任意的英文字母。


>  说明 例如： `ecs:Describe*` 表示 ecs 的所有以 Describe 开头的 action。

  - `?`代表 1 个任意的英文字母。

## Policy 格式检查

RAM 仅支持 JSON 格式。当创建或更新权限策略时，RAM 会首先检查 JSON 格式的正确性。

- 关于 JSON 的语法标准请参考 [RFC 7159](http://tools.ietf.org/html/rfc7159)。
- 您也可以使用一些在线的 JSON 格式验证器和编辑器来校验 JSON 文本的有效性。