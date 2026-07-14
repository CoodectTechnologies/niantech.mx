<div class="mb-xl-8 mt-xl-8">
    <div class="border-0">
        <h3 class="card-title fw-bolder text-dark">{{ __('Addresses') }}</h3>
        <hr>
    </div>
</div>
<!--begin::Content-->
@livewire('admin.user.address.index', ['lazy' => true, 'user' => $user], key('user-address-' . $user->id))
<!--end::Card-->
