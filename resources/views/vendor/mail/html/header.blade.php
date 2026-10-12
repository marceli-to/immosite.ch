@props(['url'])
{{-- Logo als PNG (Mailprogramme zeigen kein SVG): public/img/mail-logo.png,
     etwa 440 px breit. Ohne Datei steht der Projektname als Text. --}}
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
