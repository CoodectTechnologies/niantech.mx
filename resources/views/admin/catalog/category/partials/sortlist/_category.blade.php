<li data-id="{{ $category['id'] }}" data-parent-id="{{ $category['parent_id'] }}">
    <div @click="open['{{ $category['id'] }}'] = !open['{{ $category['id'] }}']">
        {{ $category['name'] }}
        @if(isset($category['childrens']) && count($category['childrens']))
            <span x-show="open['{{ $category['id'] }}']">[-]</span>
            <span x-show="!open['{{ $category['id'] }}']">[+]</span>
        @endif
    </div>
    @if(isset($category['childrens']) && count($category['childrens']))
        <ul x-show="open['{{ $category['id'] }}']">
            @foreach($category['childrens'] as $child)
                @include('admin.catalog.category.partials.sortlist._category', ['category' => $child])
            @endforeach
        </ul>
    @endif
</li>
