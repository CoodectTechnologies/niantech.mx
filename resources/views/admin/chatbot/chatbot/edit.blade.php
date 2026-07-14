@extends('admin.layouts.main')

@section('head')
    <title>{{ __('Chatbot') }} - {{ $chatbot->name }} - {{ __('Edit') }}</title>
@endsection

@section('breadcrumb')
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
            data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
            class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">Chatbot</h1>
            <span class="h-20px border-gray-300 border-start mx-4"></span>
            <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('admin.chatbot.index') }}"
                        class="text-muted text-hover-primary">{{ __('Listado') }}</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-300 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('admin.chatbot.show', $chatbot) }}"
                        class="text-muted text-hover-primary">{{ $chatbot->name }}</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-300 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">{{ __('Edit') }}</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        @livewire('admin.chatbot.chatbot.form', ['chatbot' => $chatbot])
    </div>
    <!--end::Container-->
@endsection
