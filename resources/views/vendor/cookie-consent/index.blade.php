@if($cookieConsentConfig['enabled'] && ! $alreadyConsentedWithCookies)

    @include('cookie-consent::dialogContents')

    <script>
        window.eduCookiePopupManager = (function () {

            const COOKIE_VALUE = 1;
            const COOKIE_DOMAIN = '{{ config('session.domain') ?? request()->getHost() }}';

            function acceptCookies() {
                setCookie('{{ $cookieConsentConfig['cookie_name'] }}', COOKIE_VALUE, {{ $cookieConsentConfig['cookie_lifetime'] }});
                closePopup();
            }

            function cookieExists(name) {
                return (document.cookie.split('; ').indexOf(name + '=' + COOKIE_VALUE) !== -1);
            }

            function closePopup() {
                const popups = document.getElementsByClassName('js-edu-cookie-popup');
                for (let i = 0; i < popups.length; ++i) {
                    popups[i].style.display = 'none';
                }
            }

            function setCookie(name, value, expirationInDays) {
                const date = new Date();
                date.setTime(date.getTime() + (expirationInDays * 24 * 60 * 60 * 1000));
                let cookieString = name + '=' + value
                    + ';expires=' + date.toUTCString()
                    + ';domain=' + COOKIE_DOMAIN
                    + ';path=/';

                @if(config('session.secure'))
                    cookieString += ';secure';
                @endif

                @if(config('session.same_site'))
                    cookieString += ';samesite={{ config('session.same_site') }}';
                @endif

                document.cookie = cookieString;
            }

            if (cookieExists('{{ $cookieConsentConfig['cookie_name'] }}')) {
                closePopup();
            }

            const buttons = document.getElementsByClassName('js-edu-cookie-popup-accept');
            for (let i = 0; i < buttons.length; ++i) {
                buttons[i].addEventListener('click', acceptCookies);
            }

            return {
                acceptCookies: acceptCookies,
                closePopup: closePopup
            };
        })();
    </script>

@endif
