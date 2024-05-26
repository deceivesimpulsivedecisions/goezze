<?php

namespace App\Service;

class GenerateUniqueIdService
{
    function generateUniqueId() {
        // Get current datetime with microseconds
        $dateTimeNow = new \DateTime();
        $datetimeString = $dateTimeNow->format('YmdHisu');

        // Generate a cryptographically secure random value
        $randomValue = random_bytes(16); // 16 bytes = 128 bits

        // Get unique identifier for the process (optional)
        $processId = getmypid();

        // Concatenate all data
        $inputData = $datetimeString . $randomValue . $processId;

        // Hash the combined data using SHA-256
        return substr(hash('sha256', $inputData), 0, 38);
    }
}