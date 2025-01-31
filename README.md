# Yii2 Wablas

This extension is wrapper of Wablas API for [Yii framework 2.0](http://www.yiiframework.com) (requires PHP 7.4+).

[![Latest Stable Version](http://poser.pugx.org/diecoding/yii2-wablas/v)](https://packagist.org/packages/diecoding/yii2-wablas)
[![Total Downloads](http://poser.pugx.org/diecoding/yii2-wablas/downloads)](https://packagist.org/packages/diecoding/yii2-wablas)
[![Latest Unstable Version](http://poser.pugx.org/diecoding/yii2-wablas/v/unstable)](https://packagist.org/packages/diecoding/yii2-wablas)
[![License](http://poser.pugx.org/diecoding/yii2-wablas/license)](https://packagist.org/packages/diecoding/yii2-wablas)
[![PHP Version Require](http://poser.pugx.org/diecoding/yii2-wablas/require/php)](https://packagist.org/packages/diecoding/yii2-wablas)

## Table of contents

- [Yii2 Wablas](#yii2-wablas)
  - [Table of contents](#table-of-contents)
  - [Instalation](#instalation)
  - [Dependencies](#dependencies)
  - [Basic Usage](#basic-usage)
  - [Create Request](#create-request)
  - [Create Response](#create-response)
  - [Custom version](#custom-version)
  - [Send Message Example](#send-message-example)
    - [Step by step usage](#step-by-step-usage)

## Instalation

Package is available on [Packagist](https://packagist.org/packages/diecoding/yii2-wablas), you can install it using [Composer](https://getcomposer.org).

```shell
composer require diecoding/yii2-wablas ^1.0
```

or add to the require section of your `composer.json` file.

```shell
"diecoding/yii2-wablas": "^1.0"
```

## Dependencies

- PHP 7.4+
- [yiisoft/yii2](https://github.com/yiisoft/yii2)
- [yiisoft/yii2-httpclient](https://github.com/yiisoft/yii2-httpclient)

## Basic Usage

Add `wablas` component to your configuration file

```php
'components' => [
    'wablas' => [
        'class'    => \diecoding\yii2\wablas\Wablas::class,
        'endpoint' => 'my-wablas.com/api',                  // Change with your wablas API endpoint
        'token'    => 'my-token',                           // Change with your wablas API token
        'secret'   => 'my-secret',                          // Optional, change with your wablas API secret, you can use token with format `token.secret`
    ],
],
```

## Create Request

```php
$data = [
    [
        'phone'   => '6281218xxxxxx',
        'message' => 'hello there',
    ]
];

/** @var \diecoding\yii2\wablas\versions\V2 $wablas */
$wablas  = $this->wablas->build('v2');
$request = $wablas->sendMessage($data)->request;

// Print request to string
print_r($request->toString());

// Short command
$request = $this->wablas->build('v2')->sendMessage($data)->request;
```

## Create Response

```php
$data = [
    [
        'phone'   => '6281218xxxxxx',
        'message' => 'hello there',
    ]
];

/** @var \diecoding\yii2\wablas\versions\V2 $wablas */
$wablas = $this->wablas->build('v2');
$response = $wablas
    ->sendMessage($data)
    ->send()
    ->response;

// Print whether response is OK
print_r($response->isOk);

// Print status code
print_r($response->statusCode);

// Print response data
print_r($response->data);

// Short command
$response = $this->wablas->build('v2')->sendMessage($data)->send()->response;
```

## Custom version

You can create your own version as follows

1. Create custom version

```php
class CustomVersion extends BaseObject
{
    public $wablas;

    public function sendMessage(array $data): Wablas
    {
        $this->wablas->setRequest($this->wablas->client->post(['custom/send-message'])->setData($data));
        return $this->wablas;
    }
}
```

2. Register custom version

```php
'components' => [
    'wablas' => [
        'class'    => \diecoding\yii2\wablas\Wablas::class,
        'endpoint' => 'my-wablas.com',                      // Change with your endpoint
        'token'    => 'my-token',                           // Change with your wablas token,
        'secret'   => 'my-secret',                          // Optional, change with your wablas API secret, you can use token with format `token.secret`
        'versions' => [
            'custom' => CustomVersion::class,
        ]
    ],
],
```

3. Call the custom version

```php
$wablas = $this->wablas->build('custom')->sendMessage($data)->send();
```

## Send Message Example

### Step by step usage

1. Install component

```shell
composer require diecoding/yii2-wablas ^1.0
```

2. Update your components configuration

```php
'components' => [
    // other components here...
    'wablas' => [
        'class'    => \diecoding\yii2\wablas\Wablas::class,
        'endpoint' => 'my-wablas.com/api',
        'token'    => 'my-token',
        'secret'   => 'my-secret',                          // Optional, you can use token with format `token.secret`
    ],
    // ...
],
```

3. Update controller

```php

use Yii;
use yii\web\Controller;

class TestController extends Controller
{
    public function actionTest()
    {
        $data = [
            [
                'phone'   => '6281218xxxxxx',
                'message' => 'hello there',
            ]
        ];

        $response = Yii::$app->wablas->build('v2')->sendMessage($data)->send()->response;
        
        if ($response->isOk) {
            print_r($response); // Do action
        } else {
            print_r($response); // Do action
        }
    }
}
```
