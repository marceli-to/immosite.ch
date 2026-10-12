<x-mail::message>
# Neue Kontaktanfrage

Über das Kontaktformular auf {{ preg_replace('#^https?://#', '', config('app.url')) }}:

<x-mail::table>
| | |
|:--|:--|
@foreach ($rows as $label => $value)
| **{{ $label }}** | {!! nl2br(e(str_replace('|', '/', $value))) !!} |
@endforeach
</x-mail::table>

Mit «Antworten» geht Ihre Antwort direkt an den Absender.
</x-mail::message>
