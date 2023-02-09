<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Libraries\Helpers;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /*
        FUNCTION LOGIN
        di gunakan untuk login user

        @response code 406 : terdapat data yang tidak lolos validasi
        @response code 400 : berita sudah pernah di like/love oleh user yang sama
        @response code 500 : terdapat kesalahan pada server ketika menyukai berita
    */
    public function login(Request $request) {
        try {

            $validator = Validator::make($request->all(),[
                'email' => 'required',
                'password' => 'required'
            ],[
                'email.required' => "Email tidak boleh kosong",
                'password.required' => "Password tidak boleh kosong",
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'code' => '406',
                    'status' => 'failed',
                    'message' => $validator->errors()
                ],406,[
                    'Content-Type' => 'application/json'
                ]);
            }

            $userData = User::where('email','=',$request->input('email'))->first();

            if ($userData == null) {
                return response()->json([
                    'code' => '404',
                    'status' => 'failed',
                    'message' => 'email tidak terdaftar'
                ],404,[
                    'Content-Type' => 'application/json'
                ]);
            }

            if (!password_verify($request->input('password'),$userData->password)) {
                return response()->json([
                    'code' => '404',
                    'status' => 'failed',
                    'message' => 'password salah'
                ],404,[
                    'Content-Type' => 'application/json'
                ]);
            }

            $token = Helpers::generateToken();

            User::where('email','=',$userData->email)->update(['token' => $token]);

            return response()->json([
                'code' => '200',
                'status' => 'ok',
                'message' => 'berhasil login',
                'data' => [
                    'email' => $userData->email,
                    'first_name' => $userData->first_name,
                    'last_name' => $userData->last_name,
                    'user_profile' => $userData->user_profile,
                    'token' => $token,
                ]
            ],200,[
                'Content-Type' => 'application/json'
            ]);



        } catch (Exception $th) {
            return response()->json([
                'code' => '500',
                'status' => 'failed',
                'message' => $th->getMessage()
            ],500,[
                'Content-Type' => 'application/json'
            ]);
        }
    }
}
