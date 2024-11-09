<form action="{{ route('settings.update') }}" method="POST">
  @csrf
  @method('PUT')
  <div class="mb-3">
    <label for="google_ads_mobile_home" class="form-label">{{ __('Google Ads Mobile Code home') }}</label>
    <textarea class="form-control" id="google_ads_mobile_home" name="google_ads_mobile_home" rows="5">{{ $settings['google_ads_mobile_home'] ?? '' }}</textarea>
  </div>
  <div class="mb-3">
    <label for="google_ads_home_2" class="form-label">{{ __('Google Ads Mobile Code home 2') }}</label>
    <textarea class="form-control" id="google_ads_mobile_home_2" name="google_ads_mobile_home_2" rows="5">{{ $settings['google_ads_mobile_home_2'] ?? '' }}</textarea>
  </div>
  <div class="mb-3">
    <label for="google_ads_mobile_classes" class="form-label">{{ __('Google Ads Mobile Code classes') }}</label>
    <textarea class="form-control" id="google_ads_mobile_classes" name="google_ads_mobile_classes" rows="5">{{ $settings['google_ads_mobile_classes'] ?? '' }}</textarea>
  </div>
  <div class="mb-3">
    <label for="google_ads_mobile_2" class="form-label">{{ __('Google Ads Mobile Code classes 2') }}</label>
    <textarea class="form-control" id="google_ads_mobile_classes_2" name="google_ads_mobile_classes_2" rows="5">{{ $settings['google_ads_mobile_classes_2'] ?? '' }}</textarea>
  </div>
  <div class="mb-3">
    <label for="google_ads_mobile_subject" class="form-label">{{ __('Google Ads Mobile Code subject') }}</label>
    <textarea class="form-control" id="google_ads_mobile_subject" name="google_ads_mobile_subject" rows="5">{{ $settings['google_ads_mobile_subject'] ?? '' }}</textarea>
  </div>
  <div class="mb-3">
    <label for="google_ads_mobile_subject_2" class="form-label">{{ __('Google Ads Mobile Code subject 2') }}</label>
    <textarea class="form-control" id="google_ads_mobile_subject_2" name="google_ads_mobile_subject_2" rows="5">{{ $settings['google_ads_mobile_subject_2'] ?? '' }}</textarea>
  </div>
  <div class="mb-3">
    <label for="google_ads_mobile_article" class="form-label">{{ __('Google Ads Mobile Code article') }}</label>
    <textarea class="form-control" id="google_ads_mobile_article" name="google_ads_mobile_article" rows="5">{{ $settings['google_ads_mobile_article'] ?? '' }}</textarea>
  </div>
  <div class="mb-3">
    <label for="google_ads_mobile_article_2" class="form-label">{{ __('Google Ads Mobile Code article 2') }}</label>
    <textarea class="form-control" id="google_ads_mobile_article_2" name="google_ads_mobile_article_2" rows="5">{{ $settings['google_ads_mobile_article_2'] ?? '' }}</textarea>
  </div>
  <div class="mb-3">
    <label for="google_ads_mobile_news" class="form-label">{{ __('Google Ads Mobile Code news') }}</label>
    <textarea class="form-control" id="google_ads_mobile_news" name="google_ads_mobile_news" rows="5">{{ $settings['google_ads_mobile_news'] ?? '' }}</textarea>
  </div>
  <div class="mb-3">
    <label for="google_ads_mobile_news_2" class="form-label">{{ __('Google Ads Mobile Code news 2') }}</label>
    <textarea class="form-control" id="google_ads_mobile_news_2" name="google_ads_mobile_news_2" rows="5">{{ $settings['google_ads_mobile_news_2'] ?? '' }}</textarea>
  </div>
  <div class="mb-3">
    <label for="google_ads_mobile_download" class="form-label">{{ __('Google Ads Mobile Code download') }}</label>
    <textarea class="form-control" id="google_ads_mobile_download" name="google_ads_mobile_download" rows="5">{{ $settings['google_ads_mobile_download'] ?? '' }}</textarea>
  </div>
  <div class="mb-3">
    <label for="google_ads_mobile_download_2" class="form-label">{{ __('Google Ads Mobile Code download 2') }}</label>
    <textarea class="form-control" id="google_ads_mobile_download_2" name="google_ads_mobile_download_2" rows="5">{{ $settings['google_ads_mobile_download_2'] ?? '' }}</textarea>
  </div>

  <button type="submit" class="btn btn-primary">{{ __('Save Settings') }}</button>
</form>
