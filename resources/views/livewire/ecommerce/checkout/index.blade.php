<div>
    <div x-data="checkout" class="checkout">
        <div class="page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 pr-lg-4 mb-4 border-right">
                        <div class="tab-content p-4">
                            @include('ecommerce.checkout.partials.index.address._index')
                            @include('ecommerce.checkout.partials.index.billing-address._index')
                        </div>
                        <div class="tab-content p-4 mt-5">
                            @include('ecommerce.checkout.partials.index.shipping-method._index')
                        </div>
                    </div>
                    <div class="col-lg-5 pl-lg-4">
                        <div class="tab-content p-4 sticky-sidebar">
                            @include('ecommerce.checkout.partials.index.summary._order')
                            @include('ecommerce.checkout.partials.index.coupon._index')
                            @include('ecommerce.checkout.partials.index.summary._index')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        Alpine.data('checkout', () => ({
            showMoreAddresses: $wire.entangle('showMoreAddresses'),
            showMoreBillingAddresses: $wire.entangle('showMoreBillingAddresses'),
            addressDiferentCreate: $wire.entangle('addressDiferentCreate'),
            billingRequire: $wire.entangle('billingRequire'),
            billingAddressCreate: $wire.entangle('billingAddressCreate'),
            billingAddressDiferentCreate: $wire.entangle('billingAddressDiferentCreate'),
            couponRequire: $wire.entangle('couponRequire'),

            init(){
                Livewire.on('address-saved', (data) => {
                    switch(data.target){
                        case 'shipping.create':
                        case 'shipping.create.diferent':
                            $('html, body').animate({ 'scrollTop': $('#addresses').offset().top }, 1000);
                            break;
                        case 'billing.create.diferent':
                        case 'billing.create':
                            $('html, body').animate({ 'scrollTop': $('#billing-addresses').offset().top }, 1000);
                            break;
                    }
                    
                });
            },
            toogleCouponCode(){
                this.couponRequire = !this.couponRequire;
            }
        }));
    </script>
@endscript