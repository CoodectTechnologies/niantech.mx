@props(['position', 'title'])
<div style="display:flex">
    <div style="display: flex; justify-content: center; align-items: center; width:40px; height:40px; margin-right:13px">
        <span style="position: absolute; color:#50CD89; font-size: 16px; font-weight: 600;">
            {{ $position }}
        </span>
    </div>
    <div>
        <div>
            <a href="#" style="color:#181C32; font-size: 14px; font-weight: 600;font-family:Arial,Helvetica,sans-serif">
                {{ $title }}
            </a>
            <p style="color:#5E6278; font-size: 13px; font-weight: 500; padding-top:3px; margin:0;font-family:Arial,Helvetica,sans-serif">
                {!! $slot !!}
            </p>
        </div>
        <div class="separator separator-dashed" style="margin:17px 0 15px 0"></div>
    </div>
</div>
