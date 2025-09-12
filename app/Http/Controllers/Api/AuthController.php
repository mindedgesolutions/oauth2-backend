<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function validateCredentials(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'email', 'exists:users,email'],
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $credentials = $request->only('username', 'password');

        if (Auth::attempt(['email' => $credentials['username'], 'password' => $credentials['password']])) {
            return response()->json(['message' => 'Credentials are valid'], Response::HTTP_OK);
        } else {
            return response()->json(['errors' => 'Invalid credentials'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    // ------------------------------

    public function login()
    {
        if (Auth::attempt(['email' => request()->query('username'), 'password' => request()->query('password')])) {
            $query = http_build_query([
                'client_id'     => request()->query('client_id'),
                'redirect_uri'  => request()->query('redirect_uri'),
                'response_type' => 'code',
                'scope'         => request()->query('scope'),
                'code_challenge' => request()->query('code_challenge'),
                'code_challenge_method' => 'S256',
            ]);

            return redirect('/oauth/authorize?' . $query);
        }
    }

    // ------------------------------

    public function me()
    {
        $data = Auth::user();

        return response()->json(['data' => $data], Response::HTTP_OK);
    }

    // ------------------------------

    public function logout(Request $request) {}
}
