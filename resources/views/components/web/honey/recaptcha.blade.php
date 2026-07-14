@if($status)
    <x-honey recaptcha="{{ $action }}"/>
    @error('honey_recaptcha_token')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
@endif
