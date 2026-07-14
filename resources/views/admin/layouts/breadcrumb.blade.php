<div id="kt_app_toolbar" class="app-toolbar pt-lg-10 mb-lg-3">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
        @yield('breadcrumb')

        <div class="d-none d-lg-block d-flex align-items-stretch justify-content-between flex-lg-grow-1">

            <!--begin::Toolbar wrapper-->
            <div class="d-flex align-items-stretch flex-shrink-0">
                <!--begin::Visit website-->
                <div class="d-flex align-items-center ms-1 ms-lg-3">
                    <!--begin::Menu-->
                    <div class="menu menu-lg-rounded menu-column menu-lg-row menu-state-bg menu-title-gray-700 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-400 fw-bold my-5 my-lg-0 align-items-stretch"
                        id="#kt_header_menu" data-kt-menu="true">
                        <a href="{{ route('admin.dashboard.general.index') }}"
                            class="menu-item menu-lg-down-accordion me-lg-1">
                            <span class="menu-link py-3">
                                <span class="menu-title">Dashboard</span>
                                <span class="menu-arrow d-lg-none"></span>
                            </span>
                        </a>
                        @if(Route::has('admin.order.index'))
                            @can('ordenes')
                                <a href="{{ route('admin.order.index') }}" class="menu-item menu-lg-down-accordion me-lg-1">
                                    <span class="menu-link py-3">
                                        <span class="menu-title">{{ __('Orders') }}</span>
                                        <span class="menu-arrow d-lg-none"></span>
                                    </span>
                                </a>
                            @endcan
                        @endif
                        @if(Route::has('admin.blog.post.index'))
                            @can('blog')
                                <a href="{{ route('admin.blog.post.index') }}"
                                    class="menu-item menu-lg-down-accordion me-lg-1">
                                    <span class="menu-link py-3">
                                        <span class="menu-title">Blogs</span>
                                        <span class="menu-arrow d-lg-none"></span>
                                    </span>
                                </a>
                            @endcan
                        @endif
                        @if(Route::has('admin.catalog.product.index'))
                            @can('productos')
                                <a href="{{ route('admin.catalog.product.index') }}"
                                    class="menu-item menu-lg-down-accordion me-lg-1">
                                    <span class="menu-link py-3">
                                        <span class="menu-title">{{ __('Products') }}</span>
                                        <span class="menu-arrow d-lg-none"></span>
                                    </span>
                                </a>
                            @endcan
                        @endif
                    </div>
                    <!--end::Menu-->
                    @if(Route::has('web.home.index'))
                        <!--begin::Drawer toggle-->
                        <a href="{{ route('web.home.index') }}" target="_blank" rel="noopener noreferrer">
                            <div class="btn btn-icon btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px"
                                id="kt_activities_toggle">
                                <!--begin::Svg Icon | path: assets/media/icons/duotune/general/gen001.svg-->
                                <span title="WEB" class="svg-icon svg-icon-1"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path
                                            d="M11 2.375L2 9.575V20.575C2 21.175 2.4 21.575 3 21.575H9C9.6 21.575 10 21.175 10 20.575V14.575C10 13.975 10.4 13.575 11 13.575H13C13.6 13.575 14 13.975 14 14.575V20.575C14 21.175 14.4 21.575 15 21.575H21C21.6 21.575 22 21.175 22 20.575V9.575L13 2.375C12.4 1.875 11.6 1.875 11 2.375Z"
                                            fill="gray" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                            </div>
                        </a>
                        <!--end::Drawer toggle-->
                    @endif
                    @if(Route::has('ecommerce.home.index'))
                        <!--begin::Drawer toggle-->
                        <a href="{{ route('ecommerce.home.index') }}" target="_blank" rel="noopener noreferrer">
                            <div class="btn btn-icon btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px"
                                id="kt_activities_toggle">
                                <!--begin::Svg Icon | path: /var/www/preview.keenthemes.com/kt-products/docs/metronic/html/releases/2022-12-26-231111/core/html/src/media/icons/duotune/ecommerce/ecm004.svg-->
                                <span title="E-COMMERCE" class="svg-icon svg-icon-muted svg-icon-2hx"><svg
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3"
                                            d="M18 10V20C18 20.6 18.4 21 19 21C19.6 21 20 20.6 20 20V10H18Z"
                                            fill="currentColor" />
                                        <path opacity="0.3"
                                            d="M11 10V17H6V10H4V20C4 20.6 4.4 21 5 21H12C12.6 21 13 20.6 13 20V10H11Z"
                                            fill="currentColor" />
                                        <path opacity="0.3" d="M10 10C10 11.1 9.1 12 8 12C6.9 12 6 11.1 6 10H10Z"
                                            fill="currentColor" />
                                        <path opacity="0.3" d="M18 10C18 11.1 17.1 12 16 12C14.9 12 14 11.1 14 10H18Z"
                                            fill="currentColor" />
                                        <path opacity="0.3" d="M14 4H10V10H14V4Z" fill="currentColor" />
                                        <path opacity="0.3" d="M17 4H20L22 10H18L17 4Z" fill="currentColor" />
                                        <path opacity="0.3" d="M7 4H4L2 10H6L7 4Z" fill="currentColor" />
                                        <path
                                            d="M6 10C6 11.1 5.1 12 4 12C2.9 12 2 11.1 2 10H6ZM10 10C10 11.1 10.9 12 12 12C13.1 12 14 11.1 14 10H10ZM18 10C18 11.1 18.9 12 20 12C21.1 12 22 11.1 22 10H18ZM19 2H5C4.4 2 4 2.4 4 3V4H20V3C20 2.4 19.6 2 19 2ZM12 17C12 16.4 11.6 16 11 16H6C5.4 16 5 16.4 5 17C5 17.6 5.4 18 6 18H11C11.6 18 12 17.6 12 17Z"
                                            fill="currentColor" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                            </div>
                        </a>
                        <!--end::Drawer toggle-->
                    @endif
                </div>
                <!--end::Visit website-->
            </div>
            <!--end::Toolbar wrapper-->
        </div>
    </div>
</div>
