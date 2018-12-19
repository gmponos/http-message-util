<?php

namespace Test\HttpMessageUtil;

use GuzzleHttp\Psr7\Request;
use HttpMessageUtil\RequestUtil;
use PHPUnit\Framework\TestCase;

final class RequestUtilTest extends TestCase
{
    /**
     * @test
     */
    public function withJsonBody()
    {
        $request = new Request('get', 'http://www.test.com');
        $request = RequestUtil::withJsonBody($request, ['email' => 'test@domain.com', 'message' => 'my message']);
        $body = $request->getBody();
        $this->assertSame(0, $body->tell());
        $contents = $body->getContents();
        $this->assertJson($contents);
        $this->assertSame('application/json', $request->getHeaderLine('Content-Type'));
    }

    /**
     * @test
     */
    public function withFormParams()
    {
        $request = new Request('get', 'http://www.test.com');
        $request = RequestUtil::withFormParams($request, ['email' => 'test@domain.com', 'message' => 'my message']);
        $body = $request->getBody();
        $this->assertSame(0, $body->tell());
        $contents = $body->getContents();
        $this->assertContains('email=test%40domain.com', $contents);
        $this->assertSame('application/x-www-form-urlencoded', $request->getHeaderLine('Content-Type'));
    }
}
