<div class="cookie-banner" id="cookie-banner">
    <div class="cookie-container">
        <div class="cookie-content">
            <div class="cookie-image-container">
                <img class="cookie-image" src="{{ asset('assets/admin/media/cookies/cookies.png') }}" alt="Cookie">
            </div>
            <div class="cookie-text-container">
                <h3 class="cookie-title">{{ __('Cookies & Privacy') }}</h3>
                <p class="cookie-paragraph">
                    {{ __('This website uses cookies to ensure you get the best browsing experience.') }} 
                    <a class="cookie-link" href="#footer">{{ __('Go to the policies') }}</a>
                </p>
            </div>
        </div>
        <div class="cookie-buttons">
            <button class="cookie-button-decline" id="btn-decline-cookies">{{ __('Decline') }}</button>
            <button class="cookie-button-accept" id="btn-accept-cookies">{{ __('Accept') }}</button>
        </div>
    </div>
</div>