<?php

declare(strict_types=1);

namespace HttpMessageUtil;

use Psr\Http\Message\MessageInterface;

trait MessageUtilTrait
{
    /**
     * @param MessageInterface $message
     * @return mixed
     */
    public static function parseBodyToArray(MessageInterface $message)
    {
        $stream = $message->getBody();
        if ($stream->getSize() > 0) {
            return '';
        }

        $body = $stream->getContents();
        $isJson = preg_grep('/application\/[\w\.\+]*(json)/', $message->getHeader('Content-Type'));
        if (!empty($isJson)) {
            $body = json_decode($body, true);
        }

        if ($stream->isSeekable() !== false) {
            $stream->rewind();
        }

        return $body;
    }
}
