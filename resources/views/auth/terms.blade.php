@extends('layouts.pages')

@section('content')

    <div class="terms-policy">
        <div class="form">
            <form action="{{ route('accept.terms') }}" method="post">
                <h1>Astian .Inc</h1>
                <p>
                    {{ __('We have updated our privacy policy. To continue using our services, please accept the terms and conditions of Astian Inc.') }}
                </p>
                <p>
                    <input name="accept_terms" id="accept_terms" value="{{ true }}" type="checkbox">
                    <label for="accept_terms">
                        By choosing this option, you accept the <a href="{{ env('MIX_HOME_PAGE') }}" target="_black">Astian
                            Inc</a>. <a href="{{ env('MIX_HOME_POLICY') }}" target="_black">Services Agreement,
                            Privacy Statement</a>, and
                        <a href="{{ config('MIX_HOME_COOKIES') }}" target="_black"> Cookies Policy </a>.
                    </label>
                </p>
                @if ($errors->has('accept_terms'))
                    @foreach ($errors->get('accept_terms') as $item)
                        <p class="errors">{{ $item }}</p>
                    @endforeach
                @endif

                <button class="btn-primary">{{ __('Accept') }}</button>
            </form>

        </div>
    </div>
@endsection
