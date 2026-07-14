@if(isset($categoryFhater->childrens) && count($categoryFhater->childrens))
    @foreach($categoryFhater->childrens as $categoryChild)
        <option {{ isset($category) ? ($categoryChild->id == $category->id ? 'disabled' : '') : '' }}
            value="{{ $categoryChild->id }}" style="{{ $style }}">{{ $categoryChild->name }}</option>
        @if(isset($categoryChild->childrens) && count($categoryChild->childrens))
            @include('admin.catalog.category.partials.form._category', [
                'categoryFhater' => $categoryChild,
                'style' => 'padding-left: 30px;',
            ])
        @endif
    @endforeach
@endif
