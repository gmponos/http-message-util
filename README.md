# PSR-7 HTTP Message Utilities

This package contains useful utility classes to manipulate PSR-7 Request/Response objects.

## Description

Let's say that you have a class that sends to an API that accepts a JSON body following code:

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

    public function send(string $email, string $message)
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
        $this->client->sendRequest($request);
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

    public function send(string $email, string $message)
    {
        $request = $this->requestFactory->create('GET', 'http://www.testurl.com');
        $request = RequestUtil::withJsonBody($request, [
            'email' => $email,
            'message' => $message
        ]);
        $this->client->sendRequest($request);
    }
}
```

## Install

You can install this package through composer

```
$ composer require http-message-util/http-message-util
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

1. Run `composer install` from bash.
2. Run `composer tests` from bash.
