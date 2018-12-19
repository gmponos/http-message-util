<?php

declare(strict_types=1);

namespace HttpMessageUtil;

use Psr\Http\Message\ResponseInterface;

final class ResponseUtil
{
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
        if ($body->isReadable() === false || $body->isSeekable() === false) {
            throw new \InvalidArgumentException('Can not read the body of the Response.');
        }

        $contents = json_decode($body->getContents(), $assoc);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException('Could not json decode body: ' . json_last_error_msg());
        }

        return $contents;
    }
}
