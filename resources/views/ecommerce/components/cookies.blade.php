<div class="cookie-banner" id="cookie-banner">
    <div class="cookie-banner-content">
        <div class="cookie-image-container">
            <img class="cookie-image" src="{{ asset('assets/admin/media/cookies/cookies.png') }}" alt="Cookie">
        </div>
        <div class="cookie-text-container">
            <h2 class="cookie-title">{{ __('Cookies and Privacy') }}</h2>
            <p class="cookie-paragraph">
                {{ __('This website use cookies to ensure you get the best user experience on our website.') }}</p>
            <p class="cookie-bold-text">{{ __('We use cookies to optimize functionaly.') }}</p>
            <a class="cookie-link" href="#">{{ __('Read our privacy and Cookie Policy') }}</a>
        </div>
    </div>
    <div class="cookie-buttons">
        <button class="cookie-button-decline" id="btn-decline-cookies">{{ __('Decline cookies') }}</button>
        <button class="cookie-button-accept" id="btn-accept-cookies">{{ __('Accept cookies') }}</button>
    </div>
</div>
<div class="cookie-banner-background" id="cookie-banner-background"></div>
