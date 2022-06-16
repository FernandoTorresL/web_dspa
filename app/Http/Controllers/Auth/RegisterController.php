<?php

namespace App\Http\Controllers\Auth;

use App\Job;
use App\User;
use App\Delegacion;
use App\Notifications\UserRegisteredSuccessfully;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        $this->middleware(['guest','checkstatus']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

    protected function register(Request $request)
    {
        /** @var User $user */

        Log::info('Validando Datos-Registrar Usuario');

        $validatedData = $request->validate([
            'username' => ['required','string','min:18','max:18','unique:users','regex:/^(SIN DATO|[A-Z]{1}(A|E|I|O|U)[A-Z]{2}\d{6}[HM](AS|BC|BS|CC|CH|CL|CM|CS|DF|DG|GR|GT|HG|JC|MC|MN|MS|NE|NL|NT|OC|PL|QR|QT|SL|SP|SR|TC|TL|TS|VZ|YN|ZS)[A-Z]{3}\w{1}\d{1})$/',],
            'matricula' => 'required|string|max:11|unique:users',
            'email' => 'required|string|email|max:100|unique:users',
            'delegacion' => 'required',
            'puesto' => 'required',
            'password' => 'required|string|min:6|max:12|confirmed',
        ]);
        try {
            $validatedData['password']        = bcrypt(array_get($validatedData, 'password'));
            $validatedData['activation_code'] = str_random(30).time();

            $validatedData['name']            = array_get($validatedData, 'username');
            $validatedData['avatar']          = 'https://loremflickr.com/300/300/kid';
            $validatedData['delegacion_id']   = array_get($validatedData, 'delegacion');
            $validatedData['job_id']          = array_get($validatedData, 'puesto');
            $validatedData['status']          = 0;

            $user                             = app(User::class)->create($validatedData);

            Log::warning('Creando usuario:'. array_get($validatedData, 'username') . ' Email:'. array_get($validatedData, 'email') . ' Del:' . array_get($validatedData, 'delegacion'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            Log::error('Error al Registrar Usuario|' . $exception);
            return redirect()->back()->with('message', 'Unable to create new user.');
        }

//        //We dont have Internet 100% free, only some sites. Can't reach Mail sometimes, so, we have to try to send the notification
//        try {
//            Log::info('Enviando notificación|usuario:'. array_get($validatedData, 'username') . ' Email:'. array_get($validatedData, 'email') . ' Del:' . array_get($validatedData, 'delegacion'));
//            $user->notify(new UserRegisteredSuccessfully($user));
//        } catch (\Exception $exception) {
//            logger()->error($exception);
//            Log::error('Error al enviar notificación|' . $exception);
//        }

        return redirect()->back()->with('message', 'Creación de cuenta exitosa. Por favor revisa tu correo para activar tu acceso. Si no recibe el correo de activación en los próximos minutos, favor de notificarlo a ' . env('ADMIN_MAIL'));
    }

    /**
     * Activate the user with given activation code.
     * @param string $activationCode
     * @return string
     */
    public function activateUser(string $activationCode)
    {
        try {

            Log::warning('Activando Usuario');
            $user = app(User::class)->where('activation_code', $activationCode)->first();
            if (!$user) {
                Log::error('Activando usuario. Código no existe o ya ha sido utilizado.');
                return "El código de activación ya no existe en el sistema o ya ha sido utilizado. Intente ingresar al sistema con su usuario y contraseña. Recuerde utilizar Google Chrome.";
            }
            $user->status          = 2;
            $user->activation_code = null;
            $user->save();
//            auth()->login($user);
            return redirect('/login')->with('login_info', 'Correo verificado! Ahora puedes iniciar sesión');
        } catch (\Exception $exception) {
            logger()->error($exception);
            Log::error('Whoops! Algo salió mal|' . $exception);
            return "Whoops! Algo salió mal. Comunícate con los administradores del portal";
        }
        return redirect()->to('/');
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

    public function showRegistrationForm()
    {

        $delegaciones = Delegacion::where('status', 1)->orderBy('id', 'asc')->get();
        $puestos = Job::whereBetween('id', [2, 4])->orWhere('id', 12)->orderBy('id', 'asc')->get();

        Log::warning('Registrar nuevo usuario.');

        return view('auth.register', [
            'delegaciones' => $delegaciones,
            'puestos' => $puestos,
        ]);
    }
}
