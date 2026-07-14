@extends('admin.layouts.main')

@section('head')
    <title>{{ __('Pulse') }}</title>
@endsection

@section('breadcrumb')
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <!--begin::Page title-->
        <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
            data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
            class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <!--begin::Title-->
            <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">{{ __('Questions and answers') }}</h1>
            <!--end::Title-->
        </div>
        <!--end::Page title-->
    </div>
    <!--end::Container-->
@endsection

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <x-pulse>
                <livewire:pulse.servers cols="full" />
                <livewire:pulse.usage cols="4" rows="2" />
                <livewire:pulse.queues cols="4" />
                <livewire:pulse.cache cols="4" />
                <livewire:pulse.slow-queries cols="8" />
                <livewire:pulse.exceptions cols="6" />
                <livewire:pulse.slow-requests cols="6" />
                <livewire:pulse.slow-jobs cols="6" />
                <livewire:pulse.slow-outgoing-requests cols="6" />
            </x-pulse>
        </div>
    </div>
@endsection
