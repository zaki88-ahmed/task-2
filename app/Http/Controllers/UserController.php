<?php

namespace App\Http\Controllers;

use App\Http\Traits\ApiDesignTrait;
use App\Models\User;
use App\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ApiDesignTrait;

    public function register(UserRequest $request)
    {
        $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),

                ]);


        return $this->ApiResponse(200, 'You have signed-in', null, $user);
    }



    public function login(UserRequest $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->ApiResponse(400, 'Validation Errors', $validator->errors());
        }

        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password) ){
            return $this->ApiResponse(401, 'Bad credentials');
        }
        $credentials = $request->only('email', 'password');
        if(Auth::attempt($credentials)){
            $user = Auth::user();
            $response = [
                'token' => $user->createToken('token-name')->plainTextToken
            ];
            return $this->ApiResponse(200, 'Done', null, $response);
        }
    }



    public function logout()
    {
        $user = auth('sanctum')->user();
//        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        $user->tokens()->delete();
        return $this->ApiResponse(200, 'Logged out');
    }
}
