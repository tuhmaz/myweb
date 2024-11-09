<footer class="landing-footer bg-body footer-text">
  <div class="footer-top position-relative overflow-hidden z-1">
    <picture>
        <source type="image/webp" srcset="{{ asset('assets/img/front-pages/backgrounds/footer-bg-' . $configData['style'] . '.webp') }}">
        <img src="{{ asset('assets/img/front-pages/backgrounds/footer-bg-' . $configData['style'] . '.png') }}"
             alt="Footer Background"
             class="footer-bg banner-bg-img z-n1 lazyload"
             loading="lazy"
             width="100%" height="auto"/>
    </picture>

    <div class="container">
      <div class="row gx-0 gy-6 g-lg-10">
        <!-- Brand Section -->
        <div class="col-lg-5">
          <a href="{{ url('/') }}" class="app-brand-link mb-6 d-flex align-items-center">
            <span class="app-brand-logo">
              <img src="{{ asset('storage/' . config('settings.site_logo')) }}"
                   alt="{{ config('settings.site_name') }} Logo"
                   style="max-width: 50px; height: auto;"
                   loading="lazy" />
            </span>
            <span class="app-brand-text footer-link fw-bold ms-2 ps-1">
              {{ config('settings.site_name') }}
            </span>
          </a>
          <p class="footer-text footer-logo-description mb-6">
            {{ config('settings.site_description') }}
          </p>
        </div>

        <!-- App Download Section -->
        <div class="col-lg-3 col-md-4">
          <h6 class="footer-title mb-6">{{ __('Download our app') }}</h6>
          <a href="javascript:void(0);" class="d-block mb-4">
            <picture>
                <source type="image/webp" srcset="{{ asset('assets/img/front-pages/landing-page/apple-icon.webp') }}">
                <img src="{{ asset('assets/img/front-pages/landing-page/apple-icon.png') }}"
                     alt="Download on Apple Store"
                     loading="lazy"
                     width="150" height="auto" />
            </picture>
          </a>
          <a href="javascript:void(0);" class="d-block">
            <picture>
                <source type="image/webp" srcset="{{ asset('assets/img/front-pages/landing-page/google-play-icon.webp') }}">
                <img src="{{ asset('assets/img/front-pages/landing-page/google-play-icon.png') }}"
                     alt="Download on Google Play"
                     loading="lazy"
                     width="150" height="auto" />
            </picture>
          </a>
        </div>

        <!-- Links Section -->
        <div class="col-lg-3 col-md-4">
          <h6 class="footer-title mb-6">{{ __('Links') }}</h6>
          <a href="/privacy-policy" class="footer-title d-block mb-4">{{ __('Privacy Policy') }}</a>
          <a href="/terms-of-service" class="footer-title d-block mb-4">{{ __('Terms of Service') }}</a>
        </div>

        <!-- Social Icons Section -->
        <div class="social-icons d-flex justify-content-center align-items-center mt-4">
          @foreach (['whatsapp', 'twitter', 'tiktok', 'facebook', 'linkedin'] as $social)
            <a href="{{ config('settings.' . $social) }}" target="_blank" rel="noopener noreferrer" class="me-3">
              <picture>
                  <source type="image/webp" srcset="{{ asset('assets/img/front-pages/icons/' . $social . '.webp') }}">
                  <img src="{{ asset('assets/img/front-pages/icons/' . $social . '.png') }}"
                       alt="{{ ucfirst($social) }} Icon"
                       loading="lazy"
                       width="70"
                       height="70"
                       class="social-icon-img">
              </picture>
            </a>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  <!-- Footer Bottom Section -->
  <div class="footer-bottom py-3 py-md-5">
    <div class="container d-flex flex-wrap justify-content-between flex-md-row flex-column text-center text-md-start">
      <div class="mb-2 mb-md-0">
        <span class="footer-bottom-text">©
          <script>document.write(new Date().getFullYear());</script>
        </span>
        <a href="{{ url('/') }}" target="_blank" class="fw-medium text-white">
          {{ config('settings.site_name') }}
        </a>,
        <span class="footer-bottom-text">{{ __('All rights reserved') }}.</span>
      </div>
    </div>
  </div>
</footer>
