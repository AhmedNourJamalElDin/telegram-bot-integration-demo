<?php

namespace App\Services;

class StartMessageMatcher
{
    private string $pattern;
    private int $length;

    public function __construct()
    {
        $token_length = token_length();
        $constants_length = 7;
        $this->length = $token_length + $constants_length;
        $this->pattern = "/\/start .{{$token_length}}/";
    }

    public function match($text): bool
    {
        return preg_match($this->pattern, $text) && strlen($text) == $this->length;
    }
}
