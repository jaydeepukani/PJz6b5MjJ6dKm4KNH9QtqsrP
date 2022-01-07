<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

class CommonController extends Controller
{
    public function displayImage(Request $request, $file)
    {
        try {
            $file = decrypt($file);
        } catch (DecryptException $e) {
            return abort(404);
        }

        $file = storage_path('app/' . $file);

        if (!File::exists($file)) {
            return abort(404);
        }

        $type = File::mimeType($file);
        $file = File::get($file);
        $response = Response::make($file, 200);
        $response->header('Content-Type', $type);

        return $response;
    }
}
