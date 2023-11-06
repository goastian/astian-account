@extends('layouts.app')

@section('content')
    <div class="container-fuild">
        <div class="card bg-dark text-light mt-5" style="width: 90%; margin: auto  ">
            <div class="card-head text-center text-primary text-uppercase">
                La aplicacion esta requiriendo que le otrogues permisos
            </div>
            <div class="card-body">
                <form action="{{ route('send.for.authorize') }}" method="GET">
                    <div class="row row-cols-3 col-12 text-sm">
                        @foreach ($scopes as $item => $value)
                            <div class="col form-check mt-2">
                                <input type="checkbox" class="form-check-input px-0" name="scopes[]" id="{{ $value->id }}"
                                    value="{{ $value->id }}">
                                <label class="form-check-label" for="{{ $value->id }}">
                                    <strong>
                                        {{ $value->id }}
                                    </strong>
                                    <span>
                                        {{ $value->description }}
                                    </span>
                                </label>
                            </div>
                        @endforeach
                        <div class="col-12">
                            @foreach ($params as $name => $value)
                                <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                            @endforeach
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn mt-5 btn-primary">
                                Solicitar acceso
                            </button>
                        </div>
                    </div>
                </form>
                <div class="text-center">
                    <form action="/logout" method="POST">
                        @csrf
                        <button type="submit" class="btn mx-3 mt-2 btn-danger">
                            Cancelar solicitud
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
