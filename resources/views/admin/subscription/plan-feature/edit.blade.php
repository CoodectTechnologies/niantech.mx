<a
    href="#"
    data-bs-toggle="modal" data-bs-target="#kt_modal_update_plan_feature_{{ $planFeature->id }}"
    class="btn btn-icon btn-bg-light btn-active-color-success btn-sm me-1">
    <i class="ki-outline ki-pencil fs-2"></i>
</a>
 <div wire:ignore.self class="modal fade" id="kt_modal_update_plan_feature_{{ $planFeature->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bolder">{{ __('Actualizar') }}</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-success" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="gray" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="gray" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                @livewire('admin.subscription.plan-feature.form', ['planFeature' => $planFeature], key($planFeature->id))
            </div>
        </div>
    </div>
</div>
