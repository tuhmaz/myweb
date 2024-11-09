<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
  public function index()
  {
    $settings = [
      'site_name' => Setting::getValue('site_name', ''),
      'site_logo' => Setting::getValue('site_logo', ''),
      'site_description' => Setting::getValue('site_description', ''),
      'admin_email' => Setting::getValue('admin_email', ''),
      'site_language' => Setting::getValue('site_language', 'ar'),
      'timezone' => Setting::getValue('timezone', config('app.timezone')),
      'two_factor_auth' => Setting::getValue('two_factor_auth', 0),
      'auto_lock_time' => Setting::getValue('auto_lock_time', 15),
      'mail_mailer' => Setting::getValue('mail_mailer', config('mail.mailer')),
      'mail_host' => Setting::getValue('mail_host', config('mail.host')),
      'mail_port' => Setting::getValue('mail_port', config('mail.port')),
      'mail_username' => Setting::getValue('mail_username', config('mail.username')),
      'mail_password' => Setting::getValue('mail_password', config('mail.password')),
      'mail_encryption' => Setting::getValue('mail_encryption', config('mail.encryption')),
      'mail_from_address' => Setting::getValue('mail_from_address', config('mail.from.address')),
      'mail_from_name' => Setting::getValue('mail_from_name', config('mail.from.name')),
      'notification_email' => Setting::getValue('notification_email', true),
      'notification_sms' => Setting::getValue('notification_sms', false),
      'notification_push' => Setting::getValue('notification_push', false),
      'meta_title' => Setting::getValue('meta_title', ''),
      'meta_description' => Setting::getValue('meta_description', ''),
      'meta_keywords' => Setting::getValue('meta_keywords', ''),
      'robots_txt' => Setting::getValue('robots_txt', "User-agent: *\nDisallow: /"),
      'sitemap_url' => Setting::getValue('sitemap_url', '/sitemap.xml'),
      'google_analytics_id' => Setting::getValue('google_analytics_id', ''),
      'facebook_pixel_id' => Setting::getValue('facebook_pixel_id', ''),
      'canonical_url' => Setting::getValue('canonical_url', ''),
      'facebook' => Setting::getValue('facebook', ''),
      'twitter' => Setting::getValue('twitter', ''),
      'linkedin' => Setting::getValue('linkedin', ''),
      'whatsapp' => Setting::getValue('whatsapp', ''),
      'tiktok' => Setting::getValue('tiktok', ''),
      // New Ads settings
      'google_ads_desktop_classes' => Setting::getValue('google_ads_desktop_classes', ''),
      'google_ads_desktop_classes_2' => Setting::getValue('google_ads_desktop_classes_2', ''),
      'google_ads_desktop_subject' => Setting::getValue('google_ads_desktop_subject', ''),
      'google_ads_desktop_subject_2' => Setting::getValue('google_ads_desktop_subject_2', ''),
      'google_ads_desktop_article' => Setting::getValue('google_ads_desktop_article', ''),
      'google_ads_desktop_article_2' => Setting::getValue('google_ads_desktop_article_2', ''),
      'google_ads_desktop_news' => Setting::getValue('google_ads_desktop_news', ''),
      'google_ads_desktop_news_2' => Setting::getValue('google_ads_desktop_news_2', ''),
      'google_ads_desktop_download' => Setting::getValue('google_ads_desktop_download', ''),
      'google_ads_desktop_download_2' => Setting::getValue('google_ads_desktop_download_2', ''),
      'google_ads_desktop_home' => Setting::getValue('google_ads_desktop_home', ''),
      'google_ads_desktop_home_2' => Setting::getValue('google_ads_desktop_home_2', ''),
      'google_ads_mobile_classes' => Setting::getValue('google_ads_mobile_classes', ''),
      'google_ads_mobile_classes_2' => Setting::getValue('google_ads_mobile_classes_2', ''),
      'google_ads_mobile_subject' => Setting::getValue('google_ads_mobile_subject', ''),
      'google_ads_mobile_subject_2' => Setting::getValue('google_ads_mobile_subject_2', ''),
      'google_ads_mobile_article' => Setting::getValue('google_ads_mobile_article', ''),
      'google_ads_mobile_article_2' => Setting::getValue('google_ads_mobile_article_2', ''),
      'google_ads_mobile_news' => Setting::getValue('google_ads_mobile_news', ''),
      'google_ads_mobile_news_2' => Setting::getValue('google_ads_mobile_news_2', ''),
      'google_ads_mobile_download' => Setting::getValue('google_ads_mobile_download', ''),
      'google_ads_mobile_download_2' => Setting::getValue('google_ads_mobile_download_2', ''),
      'google_ads_mobile_home' => Setting::getValue('google_ads_mobile_home', ''),
      'google_ads_mobile_home_2' => Setting::getValue('google_ads_mobile_home_2', ''),
    ];

    return view('dashboard.settings.index', compact('settings'));
  }


  public function update(Request $request)
  {
    $data = $request->except('_token', '_method');

    foreach ($data as $key => $value) {
      if ($request->hasFile($key)) {
        $value = $request->file($key)->store('logos', 'public');
      }

      Setting::setValue($key, $value);
    }

    if (isset($data['robots_txt'])) {
      $this->updateRobotsTxt($data['robots_txt']);
    }

    return redirect()->back()->with('success', 'Settings updated successfully!');
  }

  protected function updateRobotsTxt($content)
  {
    $robotsPath = base_path('robots.txt');
    file_put_contents($robotsPath, $content);
  }
}
