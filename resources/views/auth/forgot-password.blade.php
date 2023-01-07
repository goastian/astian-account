@extends('layouts.app')


@section('content')
    <div class="card mt-5 bg-light" style="width: 50%; margin:auto">
        <form action="{{ route('password.email') }}" method="post">
            @csrf
            <div class="card-body">
                <div class="card-title">
                    <h4 class="text-center fw-bold mb-4">Solicitud para cambiar la contraseña</h4
                        class="text-center fw-bold mb-4">
                    <p>
                        Si olvidaste tu contraseña o deseas cambiarla, por favor envianos
                        una peticion para el cambio de la misma y te llegara un correo electonico
                        para que que lo puedas realizar
                    </p>
                </div>
                <div class="row row-cols-1 col-lg-12">
                    <div class="col">
                        <label for="">{{ __('Email') }}</label>
                        <input type="text" class="form-control" name="email" placeholder="admin@email.com">
                        @if ($errors->has('email'))
                            @foreach ($errors->get('email') as $item)
                                <span class="error">{{ $item }}</span>
                            @endforeach
                        @endif
                    </div>

                    <div class="col mt-4 text-center">
                        <button class="btn btn-success" style="width: 80%">{{ __('Submit') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
