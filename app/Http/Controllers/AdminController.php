<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

use App\Admin;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'username' => 'required|max:255',
                'senha' => 'required|max:255',
            ],
            $messages = [
                'required' => 'O campo :attribute é obrigatório.',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            if (strpos($errors->first(), 'username') !== false) {
                return response([
                    'error_info' => 'validation_fails',
                    'message' => 'O campo usuário é obrigatório.',
                ], 401);
            }
            return response([
                'error_info' => 'validation_fails',
                'message' => $errors->first(),
            ], 401);
        }

        $username = $request->username;
        $password = md5($request->senha);

        $admin = Admin::where('username', $username)->first();

        if (!$admin) {
            return response([
                'error_info' => 'username_not_exists',
                'message' => 'O usuário informado não existe.',
            ], 401);
        }

        if ($password != $admin->password) {
            return response([
                'error_info' => 'wrong_password',
                'message' => 'Senha incorreta.',
            ], 401);
        }

        $token = \Str::random(60);

        $admin->token = md5($token);

        $admin->save();

        return response([
                'username' => $admin->username,
                'user' => $admin->user,
                'message' => 'Login realizado com sucesso.',
            ], 200)->header('token', $token);
    }

    public function splash(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'token' => 'required',
            ],
            $messages = [
                'required' => ':attribute vazio. Favor fazer login.',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response([
                'error_info' => 'empty_token',
                'message' => ucfirst($errors->first()),
            ], 401);
        }

        $admin = Admin::where('token', md5($request->token))->first();

        if (!$admin) {
            return response([
                'error_info' => 'invalid_token',
                'message' => 'Token inválido. Favor refazer login',
            ], 401);
        }

        $admin->token = md5(\Str::random(60));
        $admin->save();

        return response([
            'message' => 'Login realizado com sucesso.',
            'username' => $admin->username,
        ], 200)->header('token', $token);
    }
}
