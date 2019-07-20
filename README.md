
<h1><p align="center">JWT-AUTH</p></h1>
<h1><p align="center">Thinkphp5.1 扩展包</p></h1>
<p align="center"> 基于jwt的token包。</p>


## 环境要求

1. php >= 7.0
2. thinkphp >=5.1.*

## 安装

第一步:

```shell
$ composer require thans/tp-jwt-auth
```

第二步:

```shell
$ php think jwt:create
```
此举将生成jwt.php和.env配置文件。不推荐直接修改jwt.php
同时，env中会随机生成secret。请不要随意更新secret，也请保障secret安全。

第三步:

将JWT中间件添加至全局中间件（非验证使用）

```php
return [
    thans\jwt\middleware\JWT::class,
];
```


## 使用方式

对于需要验证的路由或者模块添加中间件：
```php
 thans\jwt\middleware\JWTAuth::class,
```

示例：

```php
use thans\jwt\facade\JWTAuth;

$token = JWTAuth::builder(['uid' => 1]);//参数为用户认证的信息，请自行添加

JWTAuth::auth();//token验证

JWTAuth::refresh();//刷新token，会将旧token加入黑名单
        
```
token刷新说明：

> token默认有效期为60秒，如果需要修改请修改env文件。
> refresh_ttl为刷新token有效期参数，单位为分钟。默认有效期14天。
> token过期后，旧token将会被加入黑名单。
> 如果需要自动刷新，请使用中间件  thans\jwt\middleware\JWTAuthAndRefresh::class,
> 自动刷新后会通过header返回，请保存好。（注意，此中间件过期后第一次访问正常，第二次进入黑名单。）


token传参方式如下：
- 将token加入到url中作为参数。键名为token
- 将token加入到cookie。键名为token
- 将token加入header，如下：Authorization:bearer token值
- 以上三种方式，任选其一即可。推荐加入header中。

## 参考与借鉴

https://github.com/tymondesigns/jwt-auth

## 感谢

- jwt-auth
- php
- lcobucci/jwt
- thinkphp

## License

Apache2.0
