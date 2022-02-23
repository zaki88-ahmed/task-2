<?php

use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\ManagerController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\OrganizationController;

use App\Http\Controllers\Api\MembershipController;
use App\Http\Controllers\Api\AdminController;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::post('users/login',  [UserController::class, 'login']);
Route::post('users/register',  [UserController::class, 'register']);
Route::post('users/logout',  [UserController::class, 'logout']);



Route::middleware('auth:sanctum')->group(function () {
    Route::post('v1/users',  [DataController::class, 'index']);

});




