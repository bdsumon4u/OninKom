<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function oninkari(callable $callback) {
        $result = null;

        $key = 'database.connections.'.config('database.default').'.database';

        foreach ([config('database.paikaridor'), config($key)] as $database) {
            try {
                config([$key => $database]);
                \DB::purge('mysql');
                \DB::reconnect('mysql');
                $result = $callback();
            } catch (\Exception $e) {
                // Handle the exception or log the error
                // You can also throw the exception if needed
            }
        }
        return $result;
    }
}
