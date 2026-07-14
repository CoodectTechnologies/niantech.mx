<div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
    <!--begin::Content-->
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-5 g-xl-9">
        <!--begin::Add new card-->
        <div class="col-md-4">
            <!--begin::Card-->
            <div class="card h-md-100">
                <!--begin::Card body-->
                <div class="card-body d-flex flex-center">
                    <!--begin::Button-->
                    <a href="{{ route('admin.setting.privacy-notice.create') }}" type="button"
                        class="btn btn-clear d-flex flex-column flex-center">
                        <!--begin::Illustration-->
                        <img loading="lazy" src="{{ asset('assets/admin') }}/media/illustrations/sketchy-1/4.png"
                            alt="" class="mw-100 mh-150px mb-7" />
                        <!--end::Illustration-->
                        <!--begin::Label-->
                        <div class="fw-bolder fs-3 text-gray-600 text-hover-primary">{{ __('New') }}</div>
                        <!--end::Label-->
                    </a>
                    <!--begin::Button-->
                </div>
                <!--begin::Card body-->
            </div>
            <!--begin::Card-->
        </div>
        @foreach($privacyNotices as $privacyNotice)
            <!--begin::Col-->
            <div class="col-md-4">
                <!--begin::Card-->
                <div class="card card-flush h-md-100">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>{{ $privacyNotice->name }}</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card footer-->
                    <div class="card-footer flex-wrap pt-0">
                        <a href="{{ route('admin.setting.privacy-notice.show', $privacyNotice) }}"
                            class="btn btn-light btn-active-primary my-1 me-2">Ver</a>
                        <a href="{{ route('admin.setting.privacy-notice.edit', $privacyNotice) }}" type="button"
                            class="btn btn-light btn-active-light-primary my-1">{{ __('Update') }}</a>
                        @include('admin.setting.privacy-notice.delete')
                    </div>
                    <!--end::Card footer-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Col-->
        @endforeach
    </div>
    <!--end::Card-->
</div>
