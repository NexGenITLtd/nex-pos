<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function toastMessage(string $message, string $type = 'success'): string
    {
        return "
            <script>
                Toast.fire({
                    icon: '{$type}',
                    title: '{$message}'
                });
            </script>
        ";
    }
}
