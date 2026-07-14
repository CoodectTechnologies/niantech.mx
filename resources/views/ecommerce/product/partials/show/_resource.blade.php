<div class="tab tab-vertical tab-nav-outline3 show-code-action">
    <ul class="nav nav-tabs" role="tablist">
        @foreach($cloudResources as $key => $value)
            <li class="nav-item">
                <a class="nav-link {{ $loop->iteration == 1 ? 'active' : '' }}" href="#tab-{{ $key }}">{{ __($value['alias']) }}</a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach($cloudResources as $key => $value)
            <div class="tab-pane {{ $loop->iteration == 1 ? 'active' : '' }}" id="tab-{{ $key }}">
                <div class="row">
                    @foreach($value['data'] as $resource)
                        <div class="col-lg-12">
                            <p class="text-start">
                                <a class="text-general" href="{{ $resource }}" target="_blank" rel="noopener noreferrer">
                                    <img width="60" class="mb-3" src="{{ $this->getFileImg($resource) }}" alt="{{ $this->getFileName($resource) }}">
                                    {{ $this->getFileName($resource) }}
                                </a>
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
