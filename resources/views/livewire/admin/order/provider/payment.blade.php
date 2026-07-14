<div>
    {{-- <p><strong>{{ __('Total') }}</strong> {{ number_format($total, 2) }} {{ $order->currency }}</p> --}}
    <p>{{ $orderProviderPayment->provider_voucher }}</p>
    @include('admin.components.alert-session')
    @include('admin.components.errors')
    <!--begin::Form-->
    <form class="form" wire:submit.prevent="save">
        <!--begin::Input group-->
        <div class="mb-7">
            <div x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                x-on:livewire-upload-finish="isUploading = false" x-on:livewire-upload-error="isUploading = false"
                x-on:livewire-upload-progress="progress = $event.detail.progress">
                <!--begin::Label-->
                <label class="fs-6 fw-bold mb-2">
                    <span class="">{{ __('Voucher') }}</span>
                    <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                        title="Tipo de archivo permitido: pdf,jpg, jpeg, png"></i>
                </label>
                <!--end::Label-->
                <!--begin::Image input wrapper-->
                <div class="mt-1">
                    <!--begin::Image input-->
                    <div class="image-input image-input-outline">
                        @if($voucherTMP)
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" width="100%" height="400px" allowfullscreen
                                    src="{{ $voucherTMP->temporaryUrl() }}"></iframe>
                            </div>
                        @elseif ($orderProviderPayment->voucher && Storage::exists($orderProviderPayment->voucher))
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" width="100%" height="400px" allowfullscreen
                                    src="{{ Storage::url($orderProviderPayment->voucher) }}"></iframe>
                            </div>
                        @else
                            <div class="image-input-wrapper w-125px h-125px"
                                style="background-image: url('{{ asset('assets/admin/media/icons/default.png') }}')">
                            </div>
                        @endif
                        <!--end::Preview existing avatar-->
                        <!--begin::Edit-->
                        <label
                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow image-input"
                            data-kt-image-input-action="change" data-bs-toggle="tooltip"
                            title="{{ __('Attach vaucher') }}">
                            <i class="fa-utility fa-semibold fa-pen-to-square fs-7"></i>
                            <!--begin::Inputs-->
                            <input wire:model="voucherTMP" class="d-none" type="file" name=""
                                accept=".pdf, .jpg, .jpeg, .png" />
                            <!--end::Inputs-->
                        </label>
                        <!--end::Edit-->
                        @if($voucherTMP || $orderProviderPayment->voucher)
                            <!--begin::Remove-->
                            <span wire:click.prevent="removeVoucher()"
                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="">
                                <i class="fa-light fa-circle-trash fs-2"></i>
                            </span>
                            <!--end::Remove-->
                        @endif
                    </div>
                    <!--end::Image input-->
                </div>
                @error('voucherTMP')
                    <small class="form-text text-danger" role="alert">{{ $message }}</small>
                @enderror
                <!-- Progress Bar -->
                <div x-show="isUploading" class="progress h-6px w-100">
                    <div class="progress-bar bg-primary" role="progressbar" :style="`width: ${progress}%;`"
                        :aria-valuenow="`${progress}`" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <!--end::Image input wrapper-->
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="required">{{ __('Account') }}</span>
            </label>
            <input type="text" required wire:model="orderProviderPayment.account"
                class="form-control form-control-solid @error('orderProviderPayment.account') invalid-feedback @enderror"
                placeholder="{{ __('Account') }}" name="" />
            @error('orderProviderPayment.account')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="required">{{ __('Amount') }}</span>
            </label>
            <input type="number" required wire:model="orderProviderPayment.amount"
                class="form-control form-control-solid @error('orderProviderPayment.amount') invalid-feedback @enderror"
                placeholder="{{ __('Amount') }}" name="" />
            @error('orderProviderPayment.amount')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        <!--end::Input group-->
        <!--begin::Actions-->
        <div class="text-center pt-15">
            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal"><i
                    class="fa fa-arrow-left"></i></button>
            <button wire:loading.attr="disabled" wire:target="save" type="submit" class="btn btn-primary">
                <span class="indicator-label">{{ __('Save changes') }}</span>
                <span wire:loading wire:target="save" class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </button>
        </div>
        <!--end::Actions-->
    </form>
    <!--end::Form-->
</div>
