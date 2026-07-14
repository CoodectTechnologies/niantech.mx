<div class="aside-menu flex-column-fluid">
    <!--begin::Aside Menu-->
    <div class="my-0 my-lg-5" id="kt_aside_menu_wrapper">
        <!--begin::Menu-->
        <div class="menu menu-column menu-rounded menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
            id="#kt_aside_menu" data-kt-menu="true" data-kt-menu-expand="false">
            @foreach(config('menu-system') as $menu)
                <div class="pb-0">
                    @if(sectionMenuIsVisible($menu['section']))
                        {{-- Section --}}
                        <div class="menu-item">
                            <div class="menu-content">
                                <span class="menu-section text-muted text-uppercase fs-8 ls-1">{{ __($menu['section']['name']) }}</span>
                            </div>
                        </div>
                        @foreach($menu['section']['modules'] as $module)
                            @include('admin.layouts.menu-module', ['module' => $module])
                        @endforeach
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    <!--end::Aside Menu-->
</div>
