<div>
    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="two_factor_auth" class="form-label">{{ __('Enable Two-Factor Authentication') }}</label>
            <select name="two_factor_auth" id="two_factor_auth" class="form-control">
                <option value="1" {{ $settings['two_factor_auth'] ? 'selected' : '' }}>{{ __('Enabled') }}</option>
                <option value="0" {{ !$settings['two_factor_auth'] ? 'selected' : '' }}>{{ __('Disabled') }}</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="auto_lock_time" class="form-label">{{ __('Auto Lock Time (minutes)') }}</label>
            <input type="number" name="auto_lock_time" class="form-control" id="auto_lock_time" value="{{ old('auto_lock_time', $settings['auto_lock_time']) }}">
        </div>

        <button type="submit" class="btn btn-primary">{{ __('Save Settings') }}</button>
    </form>
</div>
