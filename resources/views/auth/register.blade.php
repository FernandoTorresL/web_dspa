@extends('layouts.app')

@section('title', 'Registrar Usuario')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Registro de Usuario') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" aria-label="{{ __('Registrarse') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Correo Electrónico') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('CURP') }}</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required>

                                @if ($errors->has('username'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="matricula" class="col-md-4 col-form-label text-md-right">{{ __('Matrícula') }}</label>

                            <div class="col-md-6">
                                <input id="matricula" type="text" class="form-control{{ $errors->has('matricula') ? ' is-invalid' : '' }}" name="matricula" value="{{ old('matricula') }}">

                                @if ($errors->has('matricula'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('matricula') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="delegacion" class="col-md-4 col-form-label text-md-right">Delegación</label>
                            <div class="col-md-6">
                                    <select class="form-control @if($errors->has('delegacion')) is-invalid @endif" id="delegacion" name="delegacion">
                                        <option value="" selected>Selecciona...</option>
                                        @forelse($delegaciones as $delegacion)
                                            <option value="{{ $delegacion->id }}">{{ $delegacion->id }} - {{ $delegacion->name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @if ($errors->has('delegacion'))
                                        @foreach($errors->get('delegacion') as $error)
                                            <div class="invalid-feedback"><strong>{{ $error }}</strong></div>
                                        @endforeach
                                    @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="puesto" class="col-md-4 col-form-label text-md-right">Puesto</label>
                            <div class="col-md-6">
                                <select class="form-control @if($errors->has('puesto')) is-invalid @endif" id="puesto" name="puesto">
                                    <option value="" selected>Selecciona...</option>
                                    @forelse($puestos as $puesto)
                                        <option value="{{ $puesto->id }}">{{ $puesto->name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                                @if ($errors->has('puesto'))
                                    @foreach($errors->get('puesto') as $error)
                                        <div class="invalid-feedback"><strong>{{ $error }}</strong></div>
                                    @endforeach
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
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirmar Contraseña') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Registrar') }}
                                </button>
                                <a class="btn btn-link" href="{{ route('login') }}">
                                {{ __('¿Ya tienes una cuenta? Entra aquí.') }}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
