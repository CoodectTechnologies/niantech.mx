<div class="">
    <div class="mb-5">
        <h3 class="">{{ __('Summary') }}</h3>
        <div class="order-summary table-responsive">
            <table class="order-table">
                <tbody>
                    @foreach($order->orderProducts as $orderProduct)
                        @php
                            $product = $orderProduct->product;
                            $variant = $orderProduct->productVariant;
                            $image = $variant ? $variant->imagePreview() : ($product ? $product->imagePreview() : null);
                        @endphp
                        <tr class="bb-no">
                            <td class="pr-2">
                                <a href="{{ route('ecommerce.product.show', $orderProduct->product) }}">
                                    <img width="80" src="{{ $image }}" alt="{{ $orderProduct->product->getName() }}" style="width: 52px; height: 60px; object-fit: cover; border-radius: 4px;">
                                </a>
                            </td>
                            <td class="">
                                <a href="{{ route('ecommerce.product.show', $orderProduct->product) }}">
                                    {{ $orderProduct->product->getName() }}
                                </a>
                                <i class="fas fa-times"></i>
                                <span class="product-quantity">
                                    @if(!$orderProduct->product->getIsInStock())
                                        <div class="badge badge-warning mb-2" role="alert">{{ __('Out stock') }}</div> <br>
                                    @else
                                        {{ $orderProduct->quantity }} <br>
                                    @endif
                                </span>
                                @if($orderProduct->type == 'Digital')
                                    <br>
                                    {{ __('Type') }}: {{ $orderProduct->type }}
                                @endif
                                @if($orderProduct->productVariant)
                                    @foreach($orderProduct->productVariant->productOptionValues as $optionValue)
                                        {{ $productOption->productOption->name }}: {{ $optionValue->value }} <br>
                                    @endforeach
                                @endif
                            </td>
                            <td class="product-total">
                                {{ $order->currency }} {{ number_format($orderProduct->subtotal, 2) }}
                            </td>
                        </tr>
                        {{-- <tr class="bb-no">
                            <td class="product-name">
                                <a href="{{ route('ecommerce.product.show', $orderProduct->product) }}">{{ $orderProduct->product->getName() }}</a>
                                <i class="fas fa-times"></i>
                                <span class="product-quantity">
                                    {{ $orderProduct->quantity }}
                                </span> <br>
                                @if($orderProduct->type == 'Digital')
                                    {{ __('Type') }}: {{ $orderProduct->type }} <br>
                                @endif
                                @if($orderProduct->productVariant)
                                    @foreach($orderProduct->productVariant->productOptionValues as $optionValue)
                                        <span class="text-muted">
                                            {{ $optionValue->productOption->name }}: {{ $optionValue->value }}
                                        </span><br>
                                    @endforeach
                                @endif
                            </td>
                            <td class="product-total">
                                ${{ number_format($orderProduct->subtotal, 2) }}
                                {{ $order->currency }}
                            </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
