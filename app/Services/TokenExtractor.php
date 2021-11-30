<?php

namespace App\Services;

class TokenExtractor
{
    public function extract($text): string
    {
        return substr($text, -token_length());
    }
}
