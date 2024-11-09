<div>
    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="meta_title" class="form-label">Meta Title</label>
            <input type="text" name="meta_title" class="form-control" id="meta_title" value="{{ old('meta_title', $settings['meta_title']) }}">
        </div>

        <div class="mb-3">
            <label for="meta_description" class="form-label">Meta Description</label>
            <textarea name="meta_description" class="form-control" id="meta_description" rows="3">{{ old('meta_description', $settings['meta_description']) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="meta_keywords" class="form-label">Meta Keywords</label>
            <input type="text" name="meta_keywords" class="form-control" id="meta_keywords" value="{{ old('meta_keywords', $settings['meta_keywords']) }}">
        </div>

        <div class="mb-3">
            <label for="canonical_url" class="form-label">Canonical URL</label>
            <input type="text" name="canonical_url" class="form-control" id="canonical_url" value="{{ old('canonical_url', $settings['canonical_url']) }}">
        </div>

        <div class="mb-3">
    <label for="robots_txt" class="form-label">Robots.txt</label>
    <textarea name="robots_txt" class="form-control" id="robots_txt" rows="5">{{ old('robots_txt', $settings['robots_txt']) }}</textarea>
</div>


        <div class="mb-3">
            <label for="sitemap_url" class="form-label">Sitemap URL</label>
            <input type="text" name="sitemap_url" class="form-control" id="sitemap_url" value="{{ old('sitemap_url', $settings['sitemap_url']) }}">
        </div>

        <div class="mb-3">
            <label for="google_analytics_id" class="form-label">Google Analytics ID</label>
            <input type="text" name="google_analytics_id" class="form-control" id="google_analytics_id" value="{{ old('google_analytics_id', $settings['google_analytics_id']) }}">
        </div>

        <div class="mb-3">
            <label for="facebook_pixel_id" class="form-label">Facebook Pixel ID</label>
            <input type="text" name="facebook_pixel_id" class="form-control" id="facebook_pixel_id" value="{{ old('facebook_pixel_id', $settings['facebook_pixel_id']) }}">
        </div>

        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
</div>
