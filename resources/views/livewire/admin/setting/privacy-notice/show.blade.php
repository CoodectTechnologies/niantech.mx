<div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
    <!--begin::Post card-->
    <div class="card">
        <!--begin::Body-->
        <div class="card-body p-lg-20 pb-lg-0">
            <!--begin::Post content-->
            <div class="mb-17">
                <!--begin::Wrapper-->
                <div class="mb-8">
                    <!--begin::Info-->
                    <div class="d-flex flex-wrap mb-6">
                        <!--begin::Item-->
                        <div class="me-9 my-1">
                            <!--begin::Icon-->
                            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                            <span class="svg-icon svg-icon-primary svg-icon-2 me-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <rect x="2" y="2" width="9" height="9" rx="2" fill="gray" />
                                    <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2"
                                        fill="gray" />
                                    <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2"
                                        fill="gray" />
                                    <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2"
                                        fill="gray" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <!--end::Icon-->
                            <!--begin::Label-->
                            <span class="fw-bolder text-gray-400">{{ $privacyNotice->dateToString() }}</span>
                            <!--end::Label-->
                        </div>
                        <!--end::Item-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Title-->
                    <a class="text-dark text-hover-primary fs-2 fw-bolder">{{ $privacyNotice->name }}</a>
                    <!--end::Title-->
                </div>
                <!--end::Wrapper-->
                <!--begin::Description-->
                <div class="fs-5">
                    <!--begin::Text-->
                    {!! $privacyNotice->content !!}
                    <!--end::Text-->
                </div>
                <!--end::Description-->
            </div>
            <!--end::Post content-->
        </div>
        <!--end::Body-->
    </div>
    <!--end::Post card-->

</div>
