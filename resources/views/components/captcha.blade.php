{{-- 
<!-- English Description
Captcha Component

Usage:
<x-captcha />

Description:
This Blade component renders either hCaptcha or Turnstile depending on the configuration set in the backend.

Once the user successfully completes the CAPTCHA challenge, the component automatically adds a hidden input
to the form with the response token. This input is named according to the provider (e.g., "h-captcha-response" or "g-recaptcha-response"),
so it can be submitted and validated on the server side.

No additional setup is required in your Blade form. Just include <x-captcha /> inside the <form>.

-->

<!-- Descripción en Español
Componente Captcha

Uso:
<x-captcha />

Descripción:
Este componente Blade renderiza hCaptcha o Turnstile según la configuración definida en el backend.

Cuando el usuario completa correctamente el desafío CAPTCHA, el componente agrega automáticamente
un campo hidden al formulario con el token de respuesta. Este input tiene un nombre acorde al proveedor
(ej. "h-captcha-response" o "g-recaptcha-response") para que pueda ser enviado y validado desde el servidor.

No se requiere configuración adicional en tu formulario Blade. Simplemente incluye <x-captcha /> dentro del <form>.

--}}


@if ($status)
    @switch($provider)
        @case('turnstile')
            @push('head')
                <script nonce="{{ $nonce }}" src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
            @endpush
            @if (!$only_links)
                <div class="cf-turnstile" data-sitekey="{{ $siteKey }}"></div>
            @endif
        @break

        @case('hcaptcha')
            @push('head')
                <script nonce="{{ $nonce }}" src="https://js.hcaptcha.com/1/api.js" async defer></script>
            @endpush
            @if (!$only_links)
                <div class="h-captcha" data-sitekey="{{ $siteKey }}"></div>
            @endif
        @break

        @default
            <div></div>
    @endswitch
@endif
