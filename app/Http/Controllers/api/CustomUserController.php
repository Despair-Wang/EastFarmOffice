<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class CustomUserController extends Controller
{
    private function makeJson($state, $data = null, $msg = null)
    {
        return response()->json(['state' => $state, 'data' => $data, 'msg' => $msg])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $params = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'Auth' => 'admin',
            ];

            // return $this->makeJson(0, $params, 'HERE,MOTHERFUCKER');

            $user = User::create($params);

            event(new Registered($user));

            if ($user->id == '') {
                return $this->makeJson(0, $user, 'CREATE_USER_ERROR');
            }
            return $this->makeJson(1, null, null);

        } catch (\Throwable $e) {
            return $this->makeJson(0, $e->xdebug_message, 'CODE_ERROR');
        }
    }

    public function changePassword(Request $request)
    {
        if (!Auth::check()) {
            return $this->makeJson(0, null, 'PLEASE_LOGIN');
        }
        $user = User::Where('id', Auth::id())->first();
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $result = $user->update(['password' => Hash::make($request->password)]);
        if (!$result) {
            return $this->makeJson(0, $result, 'PASSWORD_CHANGE_ERROR');
        }
        return $this->makeJson(1, null, null);
    }

    public function changeInfo(Request $request)
    {
        if (!Auth::check()) {
            return $this->makeJson(0, null, 'PLEASE_LOGIN');
        }
        $user = User::Where('id', Auth::id())->first();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);

        $result = $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if (!$result) {
            return $this->makeJson(0, $result, 'INFORMATION_CHANGE_ERROR');
        }
        return $this->makeJson(1, null, null);
    }
}