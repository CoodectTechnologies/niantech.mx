@props(['title' => ''])
<tr>
    <td align="center" valign="center" style="text-align:center; padding-bottom: 10px">
        <div style="text-align:center; margin:0 60px 34px 60px">
            <div style="margin-bottom: 15px">
                <h3 style="text-align:left; color:#181C32; font-size: 18px; font-weight:600; margin-bottom: 22px">{{ $title }}</h3>
                <div style="padding-bottom:9px">
                    {!! $slot !!}
                </div>
            </div>
        </div>
    </td>
</tr>
