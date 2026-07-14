<div>

    <div wire:ignore.self class="modal fade" id="order_providers_guide_{{ $orderProvider->id }}" tabindex="-1"
        aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bolder">{{ __('GUIDE') }}</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)" fill="gray" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.41422 6)" fill="gray" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    @if(!$orderProvider->provider_guide)
                        <div class="alert alert-info mt-3">
                            <p>Al adjuntar esta guia, y guardar los cambios, se enviara automaticamente al proveedor</p>
                        </div>
                    @endif
                    @include('admin.components.alert')
                    <form class="form" wire:submit.prevent="create">
                        <div class="card card-flush py-4">
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Label-->
                                <label class="form-label required">{{ __('Guide') }}</label>
                                <!--end::Label-->
                                <div x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                                    x-on:livewire-upload-finish="isUploading = false"
                                    x-on:livewire-upload-error="isUploading = false"
                                    x-on:livewire-upload-progress="progress = $event.detail.progress">
                                    <!--begin::Label-->
                                    <!--begin::Image input wrapper-->
                                    <div class="mt-1">
                                        <!--begin::Image input-->
                                        <div class="image-input image-input-outline">
                                            @if($providerGuideTMP)
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <iframe class="embed-responsive-item" width="100%" height="400px"
                                                        allowfullscreen
                                                        src="{{ $providerGuideTMP->temporaryUrl() }}"></iframe>
                                                </div>
                                            @elseif ($orderProvider->provider_guide && Storage::exists($orderProvider->provider_guide))
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <iframe class="embed-responsive-item" width="100%" height="400px"
                                                        allowfullscreen
                                                        src="{{ Storage::url($orderProvider->provider_guide) }}"></iframe>
                                                </div>
                                            @else
                                                <div class="image-input-wrapper w-125px h-125px"
                                                    style="background-image: url('{{ asset('assets/admin/media/icons/pdf.png') }}')">
                                                </div>
                                            @endif
                                            <!--end::Preview existing avatar-->
                                            <!--begin::Edit-->
                                            <label
                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow image-input"
                                                data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                                title="{{ __('Change guide') }}">
                                                <i class="fa-utility fa-semibold fa-pen-to-square fs-7"></i>
                                                <!--begin::Inputs-->
                                                <input wire:model="providerGuideTMP" class="d-none" type="file"
                                                    name="" accept=".pdf" />
                                                <!--end::Inputs-->
                                            </label>
                                            <!--end::Edit-->
                                        </div>
                                        <!--end::Image input-->
                                    </div>
                                    <!-- Progress Bar -->
                                    <div x-show="isUploading" class="progress h-6px w-100">
                                        <div class="progress-bar bg-primary" role="progressbar"
                                            :style="`width: ${progress}%;`" :aria-valuenow="`${progress}`"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <!--begin::Description-->
                                <div class="text-muted fs-7">
                                    {{ __('Set the digital file. Only .pdf files are accepted') }}</div>
                                <!--end::Description-->
                                @error('providerGuideTMP')
                                    <small class="form-text text-danger" role="alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--begin::Actions-->
                        <div class="text-center pt-15">
                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal"><i
                                    class="fa fa-arrow-left"></i></button>
                            <button wire:loading.attr="disabled" wire:target="create" type="submit"
                                class="btn btn-primary">
                                <span class="indicator-label">{{ __('Save changes') }}</span>
                                <span wire:loading wire:target="create"
                                    class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </button>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div>

    <div wire:ignore.self class="modal fade" id="order_providers_show_{{ $orderProvider->id }}" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bolder">{{ $orderProvider->provider }} {{ $orderProvider->provider_id }}</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)" fill="gray" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.41422 6)" fill="gray" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <div class="card card-flush py-4">
                        <div class="card-body pt-0">
                            <h3>{{ __('Information provider') }}</h3>
                            <hr>
                            <pre>
                                @json(json_decode($orderProvider->provider_id_data), JSON_PRETTY_PRINT)
                            </pre>

                            <h3>{{ __('Information guide provider') }}</h3>
                            <hr>
                            <pre>
                                {!! $orderProvider->provider_guide_data !!}
                            </pre>
                        </div>
                    </div>
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal"><i
                                class="fa fa-arrow-left"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
