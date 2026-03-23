<?php

namespace App\Libraries;

class GeneralLibrary
{
    /**
     * Build a standard service response array.
     */
    public static function response(bool $status, mixed $data = null, string $message = ''): array
    {
        return [
            'status'  => $status,
            'data'    => $data,
            'message' => $message,
        ];
    }

    /**
     * Generate a random OTP code.
     */
    public static function generateOtp(int $length = 6): string
    {
        return str_pad((string) random_int(0, (int) str_repeat('9', $length)), $length, '0', STR_PAD_LEFT);
    }
}
