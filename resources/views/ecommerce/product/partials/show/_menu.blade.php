<li class="nav-item">
    <a href="#product-tab-description" class="nav-link active text-uppercase">{{ __('Overview') }}</a>
</li>
<li class="nav-item">
    <a href="#product-tab-characteristic" class="nav-link text-uppercase">{{ __('Tech specs') }}</a>
</li>
@if($product->iframe_url)
    <li class="nav-item">
        <a href="#product-tab-video" class="nav-link text-uppercase">{{ __('Video') }}</a>
    </li>
@endif
@php
    $isResourceVisible = false;
    foreach($cloudResources as $key => $value):
        if(count($value['data'])):
            $isResourceVisible = true;
            break;
        endif;
    endforeach;
@endphp
@if($isResourceVisible)
    <li class="nav-item">
        <a href="#product-tab-resources" class="nav-link text-uppercase">{{ __('Downloads') }}</a>
    </li>
@endif
<li class="nav-item">
    <a href="#product-tab-reviews" class="nav-link text-uppercase">{{ __('Comments') }}</a>
</li>
