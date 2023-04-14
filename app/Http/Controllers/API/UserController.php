<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function all()
    {
        $users = User::with('roles')->get();
        return response()->json(['data' => ['users' => $users]]);
    }

    public function findById($id)
    {
        $user = User::with('roles')->find($id);
        return response()->json(['data' => ['user' => $user]]);
    }

    public function update(int $id, Request $request)
    {
        $user = User::find($id);
        if (!isset($user)) {
            return response()->json(['error' => ['message' => 'usuario no encontrado']])->setStatusCode(404);
        }
        $user->nombre = $request->nombre;
        $user->apellidos = $request->apellidos;
        $user->username = $request->username;
        $user->email = $request->email;
        if ($request->password) $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['data' => ['user' => $user]]);
    }


    public function addRol(int $user, int $rol)
    {
        $user = User::with('roles')->find($user);
        if (!isset($user)) {
            return response()->json(['error' => ['message' => 'usuario no encontrado']])->setStatusCode(404);
        }
        $user->roles()->attach($rol);

        return response()->json(['data' => ['message' => 'actualizado de manera exitosa']]);
    }
}
