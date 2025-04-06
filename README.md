# 企业微信SDK
## 声明

该SDK提供企业微信API部分功能对接，若发现SDK提供的接口方法不能满足当前的操作功能，请联系作者。
SDK不提供配置存储、授权具体流程实现、事件监听等具体技术流程逻辑，请在具体项目中实现该部分功能。
SDK中接口功能可能对企业微信API变更未能及时跟进，实际接口返回请自行保证。

## 安装

由于SDK部分功能依赖外部库(GuzzleHttp、LFPhp\Logger等），请使用composer安装使用。

```ba
composer require xiaoe/wework
```

## 使用

### 1. 授权

```php
<?php 
    use LFPhp\WeworkSdk\Service\Auth;

    //由于授权部分逻辑复杂，该部分代码仅做演示。实际代码请参考官方API文档，
    //调用Auth类下相应的方法。
    //....
    $access_token = Auth::getAccessToken($corp_id, $suite_access_token, $permanent_code);
    //...继续正常逻辑
```

### 2、业务服务调用

注意：大部分接口调用逻辑在失败时，会以 ``BusinessException``	方式抛出，如业务逻辑需要做规避，需要主动进行try catch容错。

```php
<?php
    use LFPhp\WeworkSdk\Service\AddressBook\Department;
	use LFPhp\WeworkSdk\Base\AuthorizedService;
	use Xiaoe\Common\Exception\BusinessException;

	//公共Token初始化，这部分代码可以放在公共用入口里面做。
	AuthorizedService::setAccessToken($access_token);
	//...more code
	
	//查询部门列表
	//非容错模式调用：
	$dep_list = Department::getList();
	var_dump($dep_list);

	//容错模式调用：
	try {
        $dep_list = Department::getList();
    } catch(BusinessException $be){
        log('Error while get department list:'.$be->getMessage());
    }

```

## 3、事件处理

项目需主动开启回调URL监听捕获，再调用本SDK事件解析方法进行解析。

```php
<?php
    use LFPhp\WeworkSdk\ExternalAppEvent\Resolver;
	use LFPhp\WeworkSdk\ExternalAppEvent\AddressBook\EventCreateUser;

	//业务代码自行捕获事件回调参数
	$xml_string = get_request();

	/** @var EventAbstract $event **/
	$event = Resolver::parser($xml_string);
	switch(get_class($event)){
        case EventCreateUser::class
			//处理创建用户事件
            var_dump($event->user_id);
            break;
        //case .... 更多事件类型处理
    }
```

### 3、接口调用日志

本SDK远程接口调用日志采用``LFPhp\Logger``进行记录，如需捕捉、记录该部分日志，请使用``LFPhp\Logger`` 提供方法进行编码。
举例：

```php
<?php
    use LFPhp\Logger\Logger;
	use LFPhp\Logger\LoggerLevel;
	//...业务逻辑入口代码
    
	$logger = Logger::instance(LFPhp\WeworkSdk\Base\Request::class);
	$logger->register(new FileOutput('/tmp/wework.request.log'), LoggerLevel::INFO);

```

更多日志记录、日志过滤请参考 [LFPhp\Logger](http://github.com/sasumi/logger) 公开库相关文档。







