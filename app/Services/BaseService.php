<?php

namespace App\Services;

use App\Libraries\GeneralLibrary;

abstract class BaseService
{
    protected function success(mixed $data = null, string $message = 'Success'): array
    {
        return GeneralLibrary::response(true, $data, $message);
    }

    protected function failure(string $message = 'Something went wrong.', mixed $data = null): array
    {
        return GeneralLibrary::response(false, $data, $message);
    }
}
