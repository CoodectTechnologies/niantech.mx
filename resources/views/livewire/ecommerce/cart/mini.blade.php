<div>
    <div class="d-grid mt-5">
        <button 
            wire:click.prevent="store" 
            wire:target="store" 
            wire:loading.class="load-more-overlay loading" 
            wire:loading.attr="disabled"
            class="btn btn-primary btn-ellipse">
            {{ __('Add to cart') }}
        </button>
    </div>
</div>
