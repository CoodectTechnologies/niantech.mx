<div>
    <div>
        <div class="dropdown-box">
            <ul class="megamenu">
                @php $breakBothLoops = false; @endphp
                @foreach($categories as $categoriesChunk)
                    <li>
                        @foreach($categoriesChunk as $category)
                            <ul>
                                <li>
                                    <a href="{{ route('ecommerce.product.index', ['category' => $category['slug']]) }}">
                                        {{ $category['name'] }}
                                    </a>
                                </li>
                            </ul>
                            @if($loop->last && $loop->parent->iteration >= 6)
                                <ul>
                                    <li>
                                        <a href="{{ route('ecommerce.category.index') }}" class="text-uppercase">
                                            {{ __('View more') }}
                                        </a>
                                    </li>
                                </ul>
                                @php $breakBothLoops = true; @endphp
                                @break
                            @endif
                        @endforeach
                    </li>
                    @if($breakBothLoops)
                        @break
                    @endif
                @endforeach
            </ul>
        </div>
    </div>

</div>
