<a x-on:click='confirmDestroyAddress({{ $address->id }})'
    class="btn btn-light-danger btn-active-primary my-1 me-2">
    {{ __('Delete') }}
    <span wire:loading wire:target="destroy({{ $address->id }})" class="spinner-border spinner-border-sm"></span>
</a>