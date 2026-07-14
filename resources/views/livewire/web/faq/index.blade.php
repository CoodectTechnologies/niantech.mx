<div>
    <div class="service-details__faq-box">
        <div class="accrodion-grp" data-grp-name="faq-one-accrodion">
            @foreach($faqs as $faq)
                <div class="accrodion">
                    <div class="accrodion-title">
                        <h4>{{ $faq->question }}</h4>
                    </div>
                    <div class="accrodion-content">
                        <div class="inner">
                            <p>{{ $faq->answer }}</p>
                        </div><!-- /.inner -->
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
