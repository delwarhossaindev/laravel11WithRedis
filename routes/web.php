<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/', function () {
    phpinfo();
    return view('welcome');
});


Route::get('/database', function () {
    $time_start = microtime(true);
    $cacheUsers = Redis::get('users');
    $time_end = microtime(true);
    $execution_time = ($time_end - $time_start);

    if (isset($cacheUsers)) {

        $users = json_decode($cacheUsers, true);

        return response()->json([
            'message' => 'User data retrieved from redis',
            'data' => $users,
            'execution_time' => $execution_time,
        ]);
    } else {
        $time_start = microtime(true);
        $users = User::all();
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);

        Redis::set('users', json_encode($users));

        return response()->json([
            'message' => 'User data retrieved from database',
            'data' => $users,
            'execution_time' => $execution_time,
        ]);
    }

});

Route::get('/cache', function () {

    Cache::store('redis')->put('test-key', 'Redis is working!', 600);
    Cache::store('redis')->get('test-key');

});

Route::get('/session', function () {

    session(['test_key' => 'test_value']);
    return 'Session set!';

});



