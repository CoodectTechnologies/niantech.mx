<div>
    <!--begin::About card-->
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <!--begin::Card title-->
            <div class="card-title">

            </div>
            <!--end::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                @include('admin.about.edit')
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Body-->
        <div class="card-body p-5 px-lg-19 py-lg-16">
            <!--begin::Content main-->
            <div class="mb-14">
                <!--begin::Heading-->
                <div class="mb-15">
                    <!--begin::Title-->
                    <h1 class="fs-2x text-dark mb-6">{{ __('Information general') }}</h1>
                    <!--end::Title-->
                </div>
                <!--end::Heading-->
                <!--begin::Body-->
                <!--begin::Block-->
                <div class="mb-20 pb-lg-20">
                    <div class="row">
                        <div class="col-6">
                            <!--begin::Title-->
                            <h2 class="fw-bolder text-dark mb-8">{{ __('image') }}</h2>
                            <!--end::Title-->
                            <div class="fs-4 fw-bold text-gray-700">
                                <img class="img-fluid" src="{{ $about->imagePreview() }}" alt="">
                            </div>
                            <!--end::Text-->
                        </div>
                        <div class="col-6">
                            <!--begin::Title-->
                            <h2 class="fw-bolder text-dark mb-8">{{ __('image') }} 2</h2>
                            <!--end::Title-->
                            <div class="fs-4 fw-bold text-gray-700">
                                <img class="img-fluid" src="{{ $about->image2Preview() }}" alt="">
                            </div>
                            <!--end::Text-->
                        </div>
                    </div>
                    <!--begin::Title-->
                    <h2 class="fw-bolder text-dark mb-8">{{ __('Information') }}</h2>
                    <!--end::Title-->
                    <div class="fs-4 fw-bold text-gray-700 mb-13" style="white-space:pre-line">
                        {{ $about->information }}
                    </div>
                    <!--end::Text-->
                    <!--begin::Title-->
                    <h2 class="fw-bolder text-dark mb-8">{{ __('Mission') }}</h2>
                    <!--end::Title-->
                    <div class="fs-4 fw-bold text-gray-700 mb-13" style="white-space:pre-line">
                        {{ $about->mission }}
                    </div>
                    <!--end::Text-->
                    <!--begin::Title-->
                    <h2 class="fw-bolder text-dark mb-7">{{ __('Vision') }}</h2>
                    <!--end::Title-->
                    <!--begin::Text-->
                    <div class="fs-4 fw-bold text-gray-700 mb-13" style="white-space:pre-line">
                        {{ $about->vision }}
                    </div>
                    <!--end::Text-->
                    <!--begin::Title-->
                    <h2 class="fw-bolder text-dark mb-8">{{ __('Values') }}</h2>
                    <!--end::Title-->
                    <!--begin::Text-->
                    <div class="fs-4 fw-bold text-gray-700">
                        {{ $about->values }}
                    </div>
                    <!--end::Text-->
                </div>
                <!--end::Block-->
                <!--end::Body-->
            </div>
            <!--end::Content main-->
        </div>
        <!--end::Body-->
    </div>
    <!--end::About card-->
    @push('footer')
        <script>
            Livewire.on('render', function() {
                $('.modal').modal('hide');
            });
        </script>
    @endpush
</div>
