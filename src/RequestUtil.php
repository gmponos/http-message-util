<?php

declare(strict_types=1);

namespace HttpMessageUtil;

use Psr\Http\Message\RequestInterface;

final class RequestUtil
{
    use MessageUtilTrait;

    /**
     * @param RequestInterface $request
     * @param mixed $json
     * @return RequestInterface
     */
    public static function withJsonBody(RequestInterface $request, $json): RequestInterface
    {
        $body = $request->getBody();
        if ($body->isSeekable() === false || $body->isWritable() === false) {
            throw new \InvalidArgumentException('Can not modify a request with non seekable/writable body.');
        }

        $content = json_encode($json);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException('Json encoding failed: ' . json_last_error_msg());
        }

        $body->write($content);
        $body->rewind();
        return $request->withHeader('Content-Type', 'application/json');
    }

    /**
     * @param RequestInterface $request
     * @param array $form
     * @return RequestInterface
     */
    public static function withFormParams(RequestInterface $request, array $form): RequestInterface
    {
        $body = $request->getBody();
        if ($body->isSeekable() === false || $body->isWritable() === false) {
            throw new \InvalidArgumentException('Can not modify a request with non seekable/writable body.');
        }

        $content = http_build_query($form, '', '&');
        $body->write($content);
        $body->rewind();
        return $request->withHeader('Content-Type', 'application/x-www-form-urlencoded');
    }

    /**
     * Modifies the request to add to the URI of the request a query string.
     *
     * @param RequestInterface $request
     * @param string $query
     * @return RequestInterface
     */
    public static function withQuery(RequestInterface $request, string $query): RequestInterface
    {
        return $request->withUri($request->getUri()->withQuery($query));
    }

    /**
     * @param RequestInterface $request
     * @param array $query
     * @return RequestInterface
     */
    public static function withQueryOptions(RequestInterface $request, array $query): RequestInterface
    {
        $query = http_build_query($query, null, '&', PHP_QUERY_RFC3986);
        return $request->withUri($request->getUri()->withQuery($query));
    }

    /**
     * @param RequestInterface $request
     * @param array $headers
     * @return RequestInterface
     */
    public static function withHeaders(RequestInterface $request, array $headers): RequestInterface
    {
        foreach ($headers as $header => $value) {
            $request = $request->withHeader($header, $value);
        }

        return $request;
    }

    public static function toArray(RequestInterface $request): array
    {
        return [
            'method' => $request->getMethod(),
            'headers' => $request->getHeaders(),
            'uri' => $request->getRequestTarget(),
            'version' => 'HTTP/' . $request->getProtocolVersion(),
            'body' => self::parseBodyToArray($request),
        ];
    }
}
