<div>
    <ul class="mobile-menu">
        @foreach($categories as $category)
            <li>
                <a href="{{ route('ecommerce.product.index', ['category' => $category->slug]) }}">
                    @if($category->imageIcon)
                        <img class="mr-2" src="{{ $category->imageIcon }}" width="20" alt="{{ $category->name }}">
                    @endif
                    {{ $category->name }}
                </a>
                @if(isset($category->childrens) && count($category->childrens))
                    @include('ecommerce.layouts.menu-mobile.partials._category', ['category' => $category])
                @endif
            </li>
        @endforeach
    </ul>
</div>
