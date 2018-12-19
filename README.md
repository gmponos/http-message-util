# PSR-7 HTTP Message utilities

This package contains useful utility classes to manipulate PSR-7 Request/Response objects.

**Important note:**

- This package is still in version 0.x.x. According to [semantic versioning](https://semver.org/#spec-item-4) major changes can 
occur while we are still on 0.x.x version. If you use the package for a project that is in production please lock 
this package in your composer to a specific version like ^0.3.0.

## Description

Let's say that you have a class that sends to an API a JSON body.
Most probably you would have the following code:

**Before** 

```php
<?php
class SendNotification
{
    private $client;
    private $requestFactory;

    public function __construct(ClientInterface $client, RequestFactoryInterface $requestFactory)
    {
        // PSR-17 factory
        $this->requestFactory = $requestFactory;
        // PSR-18 HTTP Client
        $this->client = $client;
    }

    public function send(string $email, string $message): \Psr\Http\Message\ResponseInterface
    {
        $request = $this->requestFactory->create('GET', 'http://www.testurl.com');
        $body = $request->getBody();
        if ($body->isSeekable() === false || $body->isWritable() === false) {
            throw new \InvalidArgumentException('Can not modify a request with non writable body.');
        }

        $content = json_encode(['email' => $email, 'message' => $message]);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException('Json encoding failed: ' . json_last_error());
        }

        $body->write($content);
        $body->rewind();
        $request = $request->withHeader('Content-Type', 'application/json');
        return $this->client->sendRequest($request);
    }
}
```

**Using the utilities**

```php
<?php

use HttpMessageUtil\RequestUtil;

class SendNotification
{
    private $client;
    private $requestFactory;

    public function __construct(ClientInterface $client, RequestFactoryInterface $requestFactory)
    {
        // PSR-17 factory
        $this->requestFactory = $requestFactory;
        // PSR-18 HTTP Client
        $this->client = $client;
    }

    public function send(string $email, string $message): \Psr\Http\Message\ResponseInterface
    {
        $request = $this->requestFactory->create('GET', 'http://www.testurl.com');
        $request = RequestUtil::withJsonBody($request, [
            'email' => $email,
            'message' => $message
        ]);
        return $this->client->sendRequest($request);
    }
}
```

## Install

You can install this package through composer

```
$ composer require gmponos/http-message-util
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

1. Run `composer install` from bash.
2. Run `composer tests` from bash.
