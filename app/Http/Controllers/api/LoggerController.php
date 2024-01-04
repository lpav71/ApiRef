<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Providers\Logger;
use Illuminate\Http\Request;

class LoggerController extends Controller
{
    protected Logger $logger;

    /**
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function createLog(Request $request)
    {
        $data = $request->data;
        $description = $request->description;
        $tabNane = $request->tab_name;
        $user_id = (int)$request->user_id;

        return $this->logger->create($data, $description, $tabNane, $user_id);
    }
}
