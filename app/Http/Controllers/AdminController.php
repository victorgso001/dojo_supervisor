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
            ], 404);
        }

        if ($admin->status == 0) {
            return response([
                'error_info' => 'admin_not_active',
                'message' => 'Esta conta está desativada. Contacte o administrador do sistema',
            ], 400);
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
        $token = [
            'token' => $request->header('token'),
        ];

        $validator = Validator::make(
            $token,
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
            ], 422);
        }

        $admin = Admin::where('token', md5($request->header('token')))->first();

        if (!$admin) {
            return response([
                'error_info' => 'invalid_token',
                'message' => 'Token inválido. Favor refazer login',
            ], 401);
        }

        return response([
            'message' => 'Login realizado com sucesso.',
            'username' => $admin->username,
        ], 200)->header('token', $admin->token);
    }

    public function index(Request $request)
    {
        $skip = !$request->skip ? 0 : (int) $request->skip;
        $take = $request->take ? (int) $request->take : 20;
        $user = $request->user ? $request->user : '';
        $role = $request->role ? (int) $request->role : null;

        $admins = Admin::query();
        $admins->where('status', 1);

        if (!empty($user)) {
            $admins->where('user', 'like', "%$user%");
        }

        if (!empty($role)) {
            $admins->where('role', $role);
        }

        $admins = $admins->skip($skip)
            ->take($take)
            ->get(['user, role']);

        if (empty($admins)) {
            return response([
                'error_info' => 'admins_not_found',
                'message' => 'Não foram encontradas contas de administrador.',
            ], 404);
        }

        $count = $admins->count();

        return response([
            'admins' => $admins,
            'count' => $count,
            'pages' => $count == 0 ? 0 : ceil($count/$take),
        ]);
    }

    public function show(Admin $admin)
    {
        return response([
            'admin' => $admin,
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'username' => 'required|max:255',
                'senha' => 'required|max:255',
                'name' => 'require|max:255',
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
            } elseif (strpos($errors->first(), 'name') !== false) {
                return response([
                    'error_info' => 'validation_fails',
                    'message' => 'O campo nome de usuário é obrigatório.',
                ], 401);
            }
            return response([
                'error_info' => 'validation_fails',
                'message' => $errors->first(),
            ], 401);
        }

        $username = $request->username;
        $password = md5($request->senha);
        $user = $request->user;

        $admin = new Admin;
        $admin->username = $username;
        $admin->password = $password;
        $admin->user = $user;
        $admin->role = $role;
        $admin->token = '';
        $admin->status = 1;

        if (!$admin->create()) {
            return response([
                'error_info' => 'cannot_create_admin',
                'message' => 'Não foi possível criar o administrador agora. Tente novamente mais tarde',
            ], 400);
        }

        return response([
            'message' => 'Conta de administrador criada com sucesso.',
            'admin_id' => $admin->id,
        ]);
    }

    public function store(Request $request, Admin $admin)
    {
        $input = $request->all();

        $admin->update($input);

        return response([
            'message' => 'Conta de administrador atualizada com sucesso',
            'admin_id' => $admin->id,
        ]);
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();

        if (!$admin->trashed()) {
            return response([
                'error_info' => 'cannot_delete_admin',
                'message' => 'Conta de administrador não pôde ser excluída. Tente novamente mais tarde',
            ], 400);
        }

        return response([
            'message' => 'Conta de administrador excluída com sucesso.',
        ]);
    }
}
