<?php

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Web\Auth\AuthController;





Route::group(['middleware' => 'guest'], function () {
    Route::get('login', [AuthController::class, 'index'])->name('login');
    Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');
    Route::get('registration', [AuthController::class, 'registration'])->name('register');
    Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');

    //Redis Start

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
        session(['test-key' => 'Redis is working!']);
        return 'Session set!';
    });

   
    //Redis End
});
Route::group(['middleware' => 'auth'], function () {
    Route::get('test', [TestController::class, 'test']);
    Route::get('dashboard', [AuthController::class, 'dashboard']);
    Route::get('/', [AuthController::class, 'dashboard']);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    // Route::get('/', function () {
    //     phpinfo();
    //     return view('welcome');
    // });

});




