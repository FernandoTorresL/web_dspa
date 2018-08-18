<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/Inicio';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        Log::info('Validando Datos-Registrar Usuario');
        return Validator::make($data, [
            'username' => 'required|string|min:18|max:18|unique:users',
            'matricula' => 'required|string|max:11|unique:users',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6|max:12|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        Log::info('Creando usuario:'.$data['username'] . ' Email:'.$data['email']);
        return User::create([
            'username' => $data['username'],
            'matricula' => $data['matricula'],
            'name' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'avatar' => 'https://loremflickr.com/300/300/kid',
            'delegacion_id' => env('DEL_DEFAULT'),
            'job_id' => env('JOB_DEFAULT'),
        ]);
    }
}
