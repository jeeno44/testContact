<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function show ()
    {
        $logs = Log::where("user_id",Auth::user()->id)->orderBy("id","ASC")->paginate(5);

        return $logs;
    }

    public function exportToCsv ()
    {
        $logs = Log::where("user_id",Auth::user()->id)->get()->toArray();

        /*return \Illuminate\Support\Facades\Response::download(public_path("/upload/file.txt"),[
            "responseType" => "",
            "headers" => ["Accept" => "application/octet-stream"]
        ]);*/

//        $pathToFile = public_path("/upload/file.txt");

//        return response()->file($pathToFile,['responseType' => "arraybuffer","Accept" => "application/octet-stream"]);

        return $logs;
    }
}
