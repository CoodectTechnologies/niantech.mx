<div>
    @include('admin.components.errors')
    <!--begin::Form-->
    <form class="form" wire:submit.prevent="{{ $method }}">
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="required">{{ __('Status') }}</span>
            </label>
            <select wire:model="openpaybbvaStatus"
                class="form-control form-control-solid @error('openpaybbvaStatus') invalid-feedback @enderror">
                <option value="">{{ __('Select a option') }}</option>
                <option value="true">{{ __('Active') }}</option>
                <option value="false">{{ __('Off') }}</option>
            </select>
            @error('openpaybbvaStatus')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        @if(config('services.erp.status'))
            <!--begin::Input group-->
            <div class="fv-row mb-7">
                <label class="fs-6 fw-bold form-label mb-2">
                    <span class="">ERP ID</span>
                </label>
                <input wire:model="openpaybbvaErpId"
                    class="form-control form-control-solid @error('openpaybbvaErpId') invalid-feedback @enderror"
                    placeholder="Ejem: m9pzu6k94flw7actfvip" name="" />
                @error('openpaybbvaErpId')
                    <small class="form-text text-danger" role="alert">{{ $message }}</small>
                @enderror
            </div>
            <!--end::Input group-->
        @endif
        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="">ID</span>
            </label>
            <input wire:model="openpaybbvaId"
                class="form-control form-control-solid @error('openpaybbvaId') invalid-feedback @enderror"
                placeholder="Ejem: m9pzu6k94flw7actfvip" name="" />
            @error('openpaybbvaId')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="">Public key</span>
            </label>
            <input wire:model="openpaybbvaPublic"
                class="form-control form-control-solid @error('openpaybbvaPublic') invalid-feedback @enderror"
                placeholder="Ejem: pk_f2539f8079254b2ca517252e927750e9" name="" />
            @error('openpaybbvaPublic')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="">Private key</span>
            </label>
            <input wire:model="openpaybbvaPrivate"
                class="form-control form-control-solid @error('openpaybbvaPrivate') invalid-feedback @enderror"
                placeholder="Ejem: sk_4557646eefa94a878e6e68d6d5fc2bb3" name="" />
            @error('openpaybbvaPrivate')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="">{{ __('Country code') }}</span>
            </label>
            <select wire:model="openpaybbvaCountryCode"
                class="form-control form-control-solid @error('openpaybbvaCountryCode') invalid-feedback @enderror">
                @foreach($this->countriesCodeAllowed() as $countryCode)
                    <option value="{{ $countryCode }}">{{ $countryCode }}</option>
                @endforeach
            </select>
            @error('openpaybbvaCountryCode')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        @if($openpaybbvaStatus)
            <!--end::Input group-->
            <div wire:ignore.self class="accordion accordion-icon-toggle" id="kt_accordion_openpaybbva">
                <!--begin::Item-->
                <div class="mb-5">
                    <!--begin::Header-->
                    <div wire:ignore.self class="accordion-header py-3 d-flex collapsed" data-bs-toggle="collapse"
                        data-bs-target="#kt_accordion_2_item_openpaybbva">
                        <span class="accordion-icon">
                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr064.svg-->
                            <span class="svg-icon svg-icon-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1"
                                        transform="rotate(-180 18 13)" fill="gray" />
                                    <path
                                        d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z"
                                        fill="gray" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <h3 class="fs-4 fw-bold mb-0 ms-4">{{ __('Webhooks') }}</h3>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div wire:ignore.self id="kt_accordion_2_item_openpaybbva" class="collapse fs-6 ps-10"
                        data-bs-parent="#kt_accordion_openpaybbva">
                        @if(count($webhooks))
                            <div class="alert alert-info mt-3">
                                ¡Perfecto! Tu sistema esta al pendiente de las notificaciones de Openpay BBVA en cuanto
                                a los pagos, si en algún momento cambias de dominio, es necesario actualizar tu webhook,
                                barrándolo y creando uno nuevo, con hacer esto se actualizará el apuntado con la nueva
                                URL de dominio.
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5">
                                    <thead>
                                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                            <th class="min-w-125px">{{ __('ID') }}</th>
                                            <th class="min-w-125px">{{ __('URL') }}</th>
                                            <th class="min-w-125px">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600 fw-bold">
                                        @foreach($webhooks as $webhook)
                                            <tr>
                                                <td>{{ $webhook->id }}</td>
                                                <td>{{ $webhook->url }}</td>
                                                <td>
                                                    <!--begin::Delete-->
                                                    <button
                                                        onclick="event.preventDefault(); confirmDestroyWebhook('{{ $webhook->id }}')"
                                                        class="btn btn-icon btn-active-light-danger w-30px h-30px">
                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen027.svg-->
                                                        <span class="svg-icon svg-icon-3">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none">
                                                                <path
                                                                    d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z"
                                                                    fill="gray" />
                                                                <path opacity="0.5"
                                                                    d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z"
                                                                    fill="gray" />
                                                                <path opacity="0.5"
                                                                    d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z"
                                                                    fill="gray" />
                                                            </svg>
                                                        </span>
                                                        <!--end::Svg Icon-->
                                                    </button>
                                                    <!--end::Delete-->
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mt-3">
                                Si tu sistema no tiene un webhook con Openpay BBVA, no podrá reconocer cuando un págo
                                sea exitoso, es necesario que crees un webhook en el siguiente botón.
                            </div>

                            <button wire:click="createWebhook" wire:loading.attr="disabled"
                                wire:target="createWebhook" type="button" class="btn btn-block btn-info">
                                <span class="indicator-label">{{ __('Crear nuevo webhook') }}</span>
                                <span wire:loading wire:target="createWebhook"
                                    class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </button>
                        @endif

                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Item-->
            </div>
        @endif
        <!--begin::Actions-->
        <div class="text-center pt-15">
            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal"><i
                    class="fa fa-arrow-left"></i></button>
            <button wire:loading.attr="disabled" wire:target="{{ $method }}" type="submit"
                class="btn btn-primary">
                <span class="indicator-label">{{ __('Save changes') }}</span>
                <span wire:loading wire:target="{{ $method }}"
                    class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </button>
        </div>
        <!--end::Actions-->
    </form>
    <!--end::Form-->
    @once
        @push('footer')
            <script>
                function confirmDestroyWebhook(id) {
                    swal.fire({
                        title: "{{ __('Are you sure?') }}",
                        text: "{{ __('You will not be able to retrieve this record') }}",
                        icon: "warning",
                        buttonsStyling: false,
                        showCancelButton: true,
                        confirmButtonText: "<i class='fa fa-trash'></i> <span class='font-weight-bold'>{{ __('Yes, delete') }}</span>",
                        cancelButtonText: "<i class='fas fa-arrow-circle-left'></i>  <span class='text-dark font-weight-bold'>{{ __('No, cancel') }}</span>",
                        reverseButtons: true,
                        cancelButtonClass: "btn btn-light-secondary font-weight-bold",
                        confirmButtonClass: "btn btn-danger",
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            @this.call('deleteWebhook', id);
                        }
                    });
                }
            </script>
        @endpush
    @endonce
</div>
