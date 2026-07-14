@extends('admin.layouts.main')

@section('title', __('Caracteristicas de planes'))

@section('breadcrumb')
    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
        <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
            @yield('title')
        </h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
                <a href="{{ route('admin.subscription.plan-feature.index') }}" class="text-muted text-hover-primary">
                    {{ __('Caracteristicas de planes') }}
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container">
            @livewire('admin.subscription.plan-feature.index')
        </div>
    </div>
@endsection
