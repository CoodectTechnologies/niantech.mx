<div>
    @if(session()->has('alert-newsletter'))
        <div class="alert alert-{{ session()->get('alert-type-newsletter') }} alert-simple alert-inline">
            <h4 class="alert-title">{{ session()->get('alert-newsletter') }}</h4>
        </div>
    @endif
    <form wire:submit.prevent="store" class="input-wrapper input-wrapper-inline input-wrapper-round">
        <input wire:model="email" type="email" class="form-control email font-size-md" name="email" id="email2"
            placeholder="{{ __('Enter your email address') }}" required="">
        <button class="btn btn-dark" type="submit">
            {{ __('Subscribe') }}
            <span wire:loading.class="spinner-grow" wire:target="store"></span>
        </button>
    </form>
    @error('email')
        <small class="form-text text-danger" role="alert">{{ $message }}</small>
    @enderror
</div>
