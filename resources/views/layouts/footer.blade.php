<footer class="footer">
    <div class="items">
        <a href="{{ env('MIX_HOME_POLICY') }}" target="_blank">{{ __('Privacy Policy') }}</a>

        <a href="{{ env('MIX_HOME_DEVELOPER') }}" target="_blank">{{ __('Developers') }}</a>

        <a href="{{ env('MIX_HOME_TERMS') }}" target="_blank">{{ __('Terms of Service') }}</a>

        <a href="{{ env('MIX_HOME_CONTACT') }}" target="_blank">{{ __('Contact Us') }}</a>
    </div>

    <div class="author">

        <p>
            Copyright ©
            {{ date('Y') }}
            -
            <strong>  Astian .Inc </strong>, All Rights Reserved
        </p>

    </div>
</footer>
