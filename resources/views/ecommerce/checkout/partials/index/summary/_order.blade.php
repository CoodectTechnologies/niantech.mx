<div>
    <h3 class="subtitle text-uppercase ls-10 mt-2">
        {{ __('Summary') }}
    </h3>
    <div class="order-summary table-responsive mb-2">
        <table class="order-table">
            <tbody>
                @foreach(Cart::instance('default')->content() as $item)
                    <tr class="bb-no">
                        <td class="pr-2">
                            <a href="{{ route('ecommerce.product.show', $item->model) }}">
                                <img width="80" src="{{ $item->options->image }}" alt="{{ $item->naame }}" style="width: 52px; height: 60px; object-fit: cover; border-radius: 4px;">
                            </a>
                        </td>
                        <td class="">
                            <a href="{{ route('ecommerce.product.show', $item->model) }}">
                                {{ $item->name }}
                            </a>
                            <i class="fas fa-times"></i>
                            <span class="product-quantity">
                                @if(!$item->model->getIsInStock())
                                    <div class="badge badge-warning mb-2" role="alert">{{ __('Out stock') }}</div> <br>
                                @else
                                    {{ $item->qty }} <br>
                                @endif
                            </span>
                            @if($item->options->type == 'Digital')
                                <br>
                                {{ __('Type') }}: {{ $item->options->type }}
                            @endif
                            @if(isset($item->options['variant']))
                                @foreach($item->options['variant']['options'] as $option)
                                    {{ $option['option_name'] }}: {{ $option['option_value'] }} <br>
                                @endforeach
                            @endif
                        </td>
                        <td class="product-total">{{ currencySymbol() }}{{ number_format($item->subtotal, 2) }}
                            {{ $item->options->currency }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>