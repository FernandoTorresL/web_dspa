<?php

namespace App\Http\Controllers\Auth;

use App\Delegacion;
use App\Job;
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
    protected $redirectTo = '/';

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
            'username' => ['required','string','min:18','max:18','unique:users','regex:/^(SIN DATO|[A-Z]{1}(A|E|I|O|U)[A-Z]{2}\d{6}[HM](AS|BC|BS|CC|CH|CL|CM|CS|DF|DG|GR|GT|HG|JC|MC|MN|MS|NE|NL|NT|OC|PL|QR|QT|SL|SP|SR|TC|TL|TS|VZ|YN|ZS)[A-Z]{3}\w{1}\d{1})$/',],
            'matricula' => 'required|string|max:11|unique:users',
            'email' => 'required|string|email|max:100|unique:users',
            'delegacion' => 'required',
            'puesto' => 'required',
            'password' => 'required|string|min:6|max:12|confirmed',
        ]);
    }

    protected function messages()
    {
        return [
            'matricula.required' => 'Matrícula es un campo obligatorio',
            'matricula.max' => 'Matrícula debe tener menos de :max caracteres',
            'matricula.regex' => 'Matrícula inválida. Para BAJA, puede capturar SIN DATO',
            'curp.required' => 'CURP es un campo obligatorio',
            'curp.size' => 'CURP debe contener :size caracteres',
            'curp.regex' => 'CURP inválida. Para BAJA, puede capturar SIN DATO',
            'cuenta.required' => 'User-ID es un campo obligatorio',
            'cuenta.max' => 'User-ID debe tener menos de :max caracteres',
            'gpo_actual.required_if' => 'Grupo Actual es obligatorio cuando Tipo de Movimiento es BAJA.',
            'gpo_nuevo.required_if' => 'Grupo Nuevo es obligatorio cuando Tipo de Movimiento es ALTA o CAMBIO.',
            'comment.max' => 'Comentario ebe tener menos de :max caracteres',
        ];
    }

    protected function attributes()
    {
        return [
            'email' => 'Correo Electrónico',
            'username' => 'CURP',
            'delegacion' => 'Delegación',
            'puesto' => 'Puesto',
            'matricula' => 'Matrícula',
        ];
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        Log::info('Creando usuario:'.$data['username'] . ' Email:'.$data['email'] . 'Del:' . $data['delegacion']);

        return User::create([
            'username' => $data['username'],
            'matricula' => $data['matricula'],
            'name' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'avatar' => 'https://loremflickr.com/300/300/kid',
            'delegacion_id' => $data['delegacion'],
            'job_id' => $data['puesto'],
            'status' => 1,
        ]);
    }

    public function showRegistrationForm()
    {

        $delegaciones = Delegacion::where('status', 1)->orderBy('id', 'asc')->get();
        $puestos = Job::where('id', '>', '1')->where('id', '<=', '4')->orderBy('id', 'asc')->get();

        Log::info('Registrar Nuevo Usuario XXX.');

        return view('auth.register', [
            'delegaciones' => $delegaciones,
            'puestos' => $puestos,
        ]);
    }
}
