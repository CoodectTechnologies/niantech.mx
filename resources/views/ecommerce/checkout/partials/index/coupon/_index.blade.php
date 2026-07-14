{{-- CUPON --}}
<div id="formCoupon">
    <div style="border-top: 1px dashed #5f5b5b; border-bottom: 1px dashed #5f5b5b; padding: 10px 0; margin: 4px 0 12px;">
        <div class="sticky-sidebar-wrapper">
            <div class="d-flex justify-content-between">
                <span style="font-size: 12px;">
                    {{ __('Have a coupon?') }}
                </span>
                <a x-on:click="toogleCouponCode()" class="font-weight-bold text-dark" style="cursor: pointer; font-size: 12px;">
                    <span x-show="!couponRequire">
                        {{ __('Enter your code') }} 
                    </span>
                    <span x-show="couponRequire">
                        {{ __('Hide') }} 
                    </span>
                </a>
            </div>
            <div x-show="couponRequire" x-cloak>
                @if(session()->has('alert-coupon'))
                    <div class="alert alert-{{ session()->get('alert-coupon-type') }} alert-simple alert-inline">
                        <h4 class="alert-title">{{ session()->get('alert-coupon') }}</h4>
                    </div>
                @endif
                <div class="input-wrapper-inline mt-2">
                    <input wire:model="couponCode" type="text" name="couponCode" class="form-control form-control-sm me-1 mb-2 @error('couponCode') invalid-feedback @enderror" placeholder="{{ __('Coupon code') }}" id="coupon_code">
                    <button type="button" wire:click="applyCoupon" class="btn button btn-sm btn-rounded btn-coupon mb-2" name="apply_coupon">
                        {{ __('Apply Coupon') }}
                        <span wire:loading.class="spinner-border spinner-border-sm ms-3" wire:target="applyCoupon"></span>
                    </button>
                </div>
                @error('couponCode')
                    <small class="form-text text-danger" role="alert">{{ $message }}</small>
                @enderror
                @error('coupon.id')
                    <small class="form-text text-danger" role="alert">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>
</div>
