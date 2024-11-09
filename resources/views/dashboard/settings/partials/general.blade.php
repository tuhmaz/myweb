<div>
    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="site_name" class="form-label">{{ __('Site Name') }}</label>
            <input type="text" name="site_name" class="form-control" id="site_name" value="{{ old('site_name', $settings['site_name']) }}">
        </div>

        <div class="mb-3">
            <label for="site_logo" class="form-label">{{ __('Site Logo') }}</label>
            <input type="file" name="site_logo" class="form-control" id="site_logo">
            @if($settings['site_logo'])
                <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="{{ __('Site Logo') }}" width="100" class="mt-2">
            @endif
        </div>
        <div class="mb-3">
    <label for="site_favicon" class="form-label">Site Favicon</label>
    <input type="file" name="site_favicon" class="form-control" id="site_favicon">
    @if(config('settings.site_favicon'))
        <img src="{{ asset('storage/' . config('settings.site_favicon')) }}" alt="Favicon" width="32" class="mt-2">
    @endif
</div>


        <div class="mb-3">
            <label for="site_description" class="form-label">{{ __('Site Description') }}</label>
            <textarea name="site_description" class="form-control" id="site_description" rows="3">{{ old('site_description', $settings['site_description']) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="admin_email" class="form-label">{{ __('Admin Email') }}</label>
            <input type="email" name="admin_email" class="form-control" id="admin_email" value="{{ old('admin_email', $settings['admin_email']) }}">
        </div>

        <div class="mb-3">
            <label for="site_language" class="form-label">{{ __('Site Language') }}</label>
            <select name="site_language" id="site_language" class="form-control">
                <option value="en" {{ $settings['site_language'] === 'en' ? 'selected' : '' }}>{{ __('English') }}</option>
                <option value="ar" {{ $settings['site_language'] === 'ar' ? 'selected' : '' }}>{{ __('Arabic') }}</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="timezone" class="form-label">{{ __('Timezone') }}</label>
            <select name="timezone" id="timezone" class="form-control">
                @foreach(timezone_identifiers_list() as $timezone)
                    <option value="{{ $timezone }}" {{ $settings['timezone'] === $timezone ? 'selected' : '' }}>{{ $timezone }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">{{ __('Save Settings') }}</button>
    </form>
</div>
