<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->all();

        $validator = validator($data, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string|unique:users,phone',
            'document_number' => 'required|numeric|digits:10',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->error($validator);
        }

        $data['api_token'] = Str::random();

        $user = User::create($data);

        return response("", 204);
    }

    public function login(Request $request)
    {
        $data = $request->all();

        $validator = validator($data, [
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->error($validator);
        }

        $user = User::where('phone', $data['phone'])->firstWhere('password', $data['password']);

        if (!$user) {
            return response()->json([
                'error' => [
                    'code' => 401,
                    'message' => 'Unauthorized',
                    'errors' => [
                        'phone' => ['phone or password incorrect'],
                    ],
                ]
            ], 401);
        }
        $token = Str::random();

        $user->api_token = $token;
        $user->save();

        return response()->json([
            'data' => [
                'token' => $token,
            ]
        ], 200);

    }
}
