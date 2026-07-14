@props(['url'])
<tr>
    <td style="text-align:center; padding-bottom: 10px">
        <a href="{{ $url }}" style="display: inline-block;">
            <img src="{{ asset(config('app.logo')) }}" style="max-width: 100px" alt="{{ config('app.name') }}">
        </a>
    </td>
    <td style="text-align:center; padding-bottom: 10px">
        {!! $slot !!}
    </td>
</tr>
