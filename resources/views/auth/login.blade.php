@extends('layouts.app')

@section('title', 'Iniciar sesión')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Entrar') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Entrar') }}">
                        @csrf

                        @if(session()->has('login_error'))
                            <div class="alert alert-success">
                                {{ session()->get('login_error') }}
                            </div>
                        @endif

                        @if(session()->has('login_info'))
                            <div class="alert alert-info">
                                {{ session()->get('login_info') }}
                            </div>
                        @endif

                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(\Illuminate\Support\Facades\Session::has('alert-' . $msg))
                                    <p class="alert alert-{{ $msg }}">{{ \Illuminate\Support\Facades\Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                                @endif
                            @endforeach
                        </div> <!-- end .flash-message -->

                        <div class="form-group row">
                            <label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('Correo Electrónico') }}</label>

                            <div class="col-md-6">
                                <input id="identity" type="identity" class="form-control{{ $errors->has('identity') ? ' is-invalid' : '' }}" name="identity" value="{{ old('identity') }}" required autofocus>

                                @if ($errors->has('identity'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('identity') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Contraseña') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Recuérdame') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Entrar') }}
                                </button>

                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('¿Olvidaste tu contraseña?') }}
                                </a>
                                <a class="btn btn-link" href="{{ route('register') }}">
                                {{ __('¿Aún no tienes cuenta? Registrate aquí.') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
