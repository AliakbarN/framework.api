<?php

namespace api\Facades;

final class Response
{
    public function send(mixed $data): string
    {
        header('ContentType: application/json');
        return json_encode($data);
    }
}