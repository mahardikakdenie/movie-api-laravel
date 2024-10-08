<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\ResponseFormatter;
use App\Http\Interface\AuthServiceInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $auth_svc;
    public function __construct(AuthServiceInterface $auth_svc)
    {
        $this->auth_svc = $auth_svc;
    }
    public function login(Request $request)
    {
        // try {
        // $request->validate([
        //     'email' => 'required|email',
        //     'password' => 'required',
        //     'device_name' => 'required',
        // ]);

        $pipeline = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        $token = $this->auth_svc->loginService($pipeline);

        return ResponseFormatter::success($token);
        // } catch (\Throwable $th) {
        //     throw $th;
        // }
    }

    public function register(Request $request)
    {
        try {
            // Create the user
            $validate = $request->validate([
                'name' => ['required'],
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            $token = $this->auth_svc->registerService($validate);

            // Return success response with token
            return ResponseFormatter::success([
                'token' => $token,
            ], 'Registration successful');
        } catch (\Throwable $th) {
            $message = $th->getMessage();

            return ResponseFormatter::error($message);
        }
    }
}
