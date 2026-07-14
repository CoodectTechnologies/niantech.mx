@props(['title' => ''])
<tr style="display: flex; justify-content: center; margin:0 60px 35px 60px">
   <td align="start" valign="start" style="padding-bottom: 10px;">
        <p style="color:#181C32; font-size: 18px; font-weight: 600; margin-bottom:13px">
            {{ $title }}
        </p>
        <div style="background: #F9F9F9; border-radius: 12px; padding:35px 30px">
            {!! $slot !!}
        </div>
   </td>
</tr>
