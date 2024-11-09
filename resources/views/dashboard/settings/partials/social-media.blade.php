<div>
    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="facebook" class="form-label">Facebook URL</label>
            <input type="text" name="facebook" class="form-control" id="facebook" value="{{ old('facebook', $settings['facebook'] ?? '') }}">

        </div>

        <div class="mb-3">
            <label for="twitter" class="form-label">Twitter URL</label>
            <input type="text" name="twitter" class="form-control" id="twitter" value="{{ old('twitter', $settings['twitter'] ?? '') }}">
        </div>

        <div class="mb-3">
            <label for="linkedin" class="form-label">LinkedIn URL</label>
            <input type="text" name="linkedin" class="form-control" id="linkedin" value="{{ old('linkedin', $settings['linkedin'] ?? '') }}">
        </div>

        <div class="mb-3">
            <label for="whatsapp" class="form-label">WhatsApp Number</label>
            <input type="text" name="whatsapp" class="form-control" id="whatsapp" value="{{ old('whatsapp', $settings['whatsapp'] ?? '') }}">
        </div>

        <div class="mb-3">
            <label for="tiktok" class="form-label">TikTok URL</label>
            <input type="text" name="tiktok" class="form-control" id="tiktok" value="{{ old('tiktok', $settings['tiktok']?? '') }}">
        </div>

        <button type="submit" class="btn btn-primary">Save Social Media Settings</button>
    </form>
</div>
