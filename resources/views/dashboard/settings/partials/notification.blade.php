<div>
    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="notification_email" class="form-label">{{ __('Enable Email Notifications') }}</label>
            <select name="notification_email" id="notification_email" class="form-control">
                <option value="1" {{ $settings['notification_email'] ? 'selected' : '' }}>{{ __('Enabled') }}</option>
                <option value="0" {{ !$settings['notification_email'] ? 'selected' : '' }}>{{ __('Disabled') }}</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="notification_sms" class="form-label">{{ __('Enable SMS Notifications') }}</label>
            <select name="notification_sms" id="notification_sms" class="form-control">
                <option value="1" {{ $settings['notification_sms'] ? 'selected' : '' }}>{{ __('Enabled') }}</option>
                <option value="0" {{ !$settings['notification_sms'] ? 'selected' : '' }}>{{ __('Disabled') }}</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="notification_push" class="form-label">{{ __('Enable Push Notifications') }}</label>
            <select name="notification_push" id="notification_push" class="form-control">
                <option value="1" {{ $settings['notification_push'] ? 'selected' : '' }}>{{ __('Enabled') }}</option>
                <option value="0" {{ !$settings['notification_push'] ? 'selected' : '' }}>{{ __('Disabled') }}</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">{{ __('Save Settings') }}</button>
    </form>
</div>
