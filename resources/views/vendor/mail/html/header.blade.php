@props(['url'])
{{-- Logo as PNG (mail clients don't show SVG): public/img/mail-logo.png,
     about 440 px wide. Without the file, the project name is shown as text. --}}
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (file_exists(public_path('img/mail-logo.png')))
<img src="{{ asset('img/mail-logo.png') }}" alt="{{ trim($slot) }}" width="220" style="display: block; width: 220px; height: auto; border: 0;">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
