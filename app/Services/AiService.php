<?php

namespace App\Services;

class AiService
{
    public function process(string $input): string
    {
        // Example logic — replace with your AI integration
        return "You sent: ".strtoupper($input);
    }
}

