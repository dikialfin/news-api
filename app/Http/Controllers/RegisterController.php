<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        private $userModel = new User() 
    ){}

    public function register(Request $request) {
        try {
            $validator = Validator::make($request->all(),[
                'first_name' => 'required|string',
                'last_name' => 'nullable|string',
                'email' => 'required|email|unique:App\Models\User,email',
                'password' => 'required|string|min:5',
                'password_conf' => 'required|same:password',
            ],[
                'first_name.required' => "First Name tidak boleh kosong",
                'first_name.string' => "First Name hanya boleh berupa string",
                'last_name.required' => "Last Name tidak boleh kosong",
                'last_name.string' => "Last Name hanya boleh berupa string", 
                'email.required' => "Email tidak boleh kosong", 
                'email.email' => "Email format tidak valid", 
                'email.unique' => "Email sudah terdaftar", 
                'password.required' => "Password tidak boleh kosong", 
                'password.string' => "Password hanya boleh berupa sting", 
                'password.min' => "Password minimal dengan panjang 5", 
                'password_conf.required' => "Password Confirm tidak boleh kosong", 
                'password_conf.same' => "Password Confirm berbeda dengan Password", 
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'code' => '406',
                    'status' => 'failed',
                    'message' => $validator->errors()
                ],406);
            }

            $this->userModel::insert([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'password' => password_hash($request->input('password'), PASSWORD_DEFAULT),
            ]);

            return response()->json([
                'code' => '201',
                'status' => 'ok',
                'message' => 'Selamat registrasi berhasil'
            ],201);
        } catch (Exception $th) {
            throw $th;
            return response([
                'code' => '500',
                'status' => 'failed',
                'message' => 'Oops terjadi kesalahan di dalam server, silahkan coba lagi nanti'
            ],500);
        }
    }


}
