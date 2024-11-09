<div>
    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="mail_mailer" class="form-label">{{ __('Mail Mailer') }}</label>
            <input type="text" name="mail_mailer" class="form-control" id="mail_mailer" value="{{ old('mail_mailer', $settings['mail_mailer']) }}">
        </div>

        <div class="mb-3">
            <label for="mail_host" class="form-label">{{ __('Mail Host') }}</label>
            <input type="text" name="mail_host" class="form-control" id="mail_host" value="{{ old('mail_host', $settings['mail_host']) }}">
        </div>

        <div class="mb-3">
            <label for="mail_port" class="form-label">{{ __('Mail Port') }}</label>
            <input type="number" name="mail_port" class="form-control" id="mail_port" value="{{ old('mail_port', $settings['mail_port']) }}">
        </div>

        <div class="mb-3">
            <label for="mail_username" class="form-label">{{ __('Mail Username') }}</label>
            <input type="text" name="mail_username" class="form-control" id="mail_username" value="{{ old('mail_username', $settings['mail_username']) }}">
        </div>

        <div class="mb-3">
            <label for="mail_password" class="form-label">{{ __('Mail Password') }}</label>
            <input type="password" name="mail_password" class="form-control" id="mail_password" value="{{ old('mail_password', $settings['mail_password']) }}">
        </div>

        <div class="mb-3">
            <label for="mail_encryption" class="form-label">{{ __('Mail Encryption') }}</label>
            <input type="text" name="mail_encryption" class="form-control" id="mail_encryption" value="{{ old('mail_encryption', $settings['mail_encryption']) }}">
        </div>

        <div class="mb-3">
            <label for="mail_from_address" class="form-label">{{ __('Mail From Address') }}</label>
            <input type="text" name="mail_from_address" class="form-control" id="mail_from_address" value="{{ old('mail_from_address', $settings['mail_from_address']) }}">
        </div>

        <div class="mb-3">
            <label for="mail_from_name" class="form-label">{{ __('Mail From Name') }}</label>
            <input type="text" name="mail_from_name" class="form-control" id="mail_from_name" value="{{ old('mail_from_name', $settings['mail_from_name']) }}">
        </div>

        <button type="submit" class="btn btn-primary">{{ __('Save Settings') }}</button>
    </form>
</div>
