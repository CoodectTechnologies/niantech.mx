@extends('admin.layouts.main')

@section('title', __('Ajustes'))

@section('breadcrumb')
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">
                <a href="{{ route('admin.setting.welcome') }}">{{ __('Settings') }}</a>
                <span class="h-20px border-1 border-gray-200 border-start ms-3 mx-2 me-1"></span>
                <span class="text-muted fs-7 fw-bold mt-2">{{ __('Notifications') }}</span>
            </h1>
        </div>
    </div>
@endsection

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            @livewire('admin.setting.notification.form')
        </div>
    </div>
@endsection
