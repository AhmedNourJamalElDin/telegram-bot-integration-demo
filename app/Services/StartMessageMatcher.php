<?php

namespace App\Services;

class StartMessageMatcher
{
    private string $pattern;

    public function __construct()
    {
        $this->pattern = "/\/start/";
    }

    public function match($text): bool
    {
        return preg_match($this->pattern, $text);
    }
}
