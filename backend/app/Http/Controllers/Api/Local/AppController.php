<?php

namespace App\Http\Controllers\Api\Local;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function app()
    {
        $app_name = env('APP_NAME');
        $google_analytics = env('GOOGLE_ANALYTICS_ID');
        return response()->json(compact('app_name', 'google_analytics'));
    }
}
