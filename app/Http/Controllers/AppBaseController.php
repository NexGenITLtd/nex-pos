<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class AppBaseController extends BaseController
{
    /**
     * Generate a toast message.
     *
     * @param string $message
     * @param string $type
     * @return string
     */
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
