<?php

declare(strict_types=1);

namespace HttpMessageUtil;

use Psr\Http\Message\ResponseInterface;

final class ResponseUtil
{
    use MessageUtilTrait;

    /**
     * Parses the json body of a response.
     *
     * @param ResponseInterface $response
     * @param bool $assoc
     * @return mixed
     */
    public static function getJsonBody(ResponseInterface $response, bool $assoc = true)
    {
        $body = $response->getBody();
        $contents = json_decode($body->getContents(), $assoc);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException('Could not json decode body: ' . json_last_error_msg());
        }

        if ($body->isSeekable() === false) {
            $body->rewind();
        }

        return $contents;
    }

    public static function toArray(ResponseInterface $response): array
    {
        return [
            'headers' => $response->getHeaders(),
            'status_code' => $response->getStatusCode(),
            'version' => 'HTTP/' . $response->getProtocolVersion(),
            'message' => $response->getReasonPhrase(),
            'body' => self::parseBodyToArray($response),
        ];
    }
}
