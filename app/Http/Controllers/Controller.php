<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @OA\Info(
     *     description="This is an example API for users management",
     *     version="1.0.0",
     *     title="User Management API"
     * )
     */
    use AuthorizesRequests, ValidatesRequests;
}
