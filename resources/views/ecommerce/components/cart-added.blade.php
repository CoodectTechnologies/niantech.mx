<script>
    $(document).ready(function() {
        Livewire.on('notify-add-cart', (object) => {
            let name = object[0];
            let url = object[1];
            let image = object[2];
            Coodect.Minipopup.open({
                productClass: ' product-cart',
                name: name,
                nameLink: url,
                imageSrc: image,
                imageLink: url,
                message: '<p>{{ __('Has been added to cart') }}:</p>',
                actionTemplate: '<a href="{{ route('ecommerce.cart.index') }}" class="btn btn-rounded btn-sm">{{ __('View Cart') }}</a><a href="{{ route('ecommerce.checkout.index') }}" class="btn btn-dark btn-rounded btn-sm">{{ __('Checkout') }}</a>'
            });
        });
        Livewire.on('notify-add-compare', (object) => {
            let name = object[0];
            let url = object[1];
            let image = object[2];
            Coodect.Minipopup.open({
                productClass: ' product-cart',
                name: name,
                nameLink: url,
                imageSrc: image,
                imageLink: url,
                message: '<p>{{ __('Has been added to compare') }}:</p>',
                actionTemplate: '<a href="{{ route('ecommerce.compare.index') }}" class="btn btn-rounded btn-sm">{{ __('View comparisons') }}</a>'
            });
        });
        Livewire.on('notify-add-wishlist', (object) => {
            let name = object[0];
            let url = object[1];
            let image = object[2];
            Coodect.Minipopup.open({
                productClass: ' product-cart',
                name: name,
                nameLink: url,
                imageSrc: image,
                imageLink: url,
                message: '<p>{{ __('Has been added to wishlist') }}:</p>',
                actionTemplate: '<a href="{{ route('ecommerce.compare.index') }}" class="btn btn-rounded btn-sm">{{ __('View wishlist') }}</a>'
            });
        });
    });
</script>
