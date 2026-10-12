@php($hosting = config('site.hosting'))

<x-headings.h3 class="mt-32">Hosting Provider &amp; Server-Logfiles</x-headings.h3>
<p>
  Der Provider der Seiten erhebt und speichert automatisch Informationen in so genannten Server-Logfiles,
  die Ihr Browser automatisch an uns übermittelt. Dies sind:
</p>
<ul>
  <li>IP-Adresse</li>
  <li>Browsertyp und Browserversion</li>
  <li>verwendetes Betriebssystem</li>
  <li>Referrer URL</li>
  <li>Hostname des zugreifenden Rechners</li>
  <li>Uhrzeit der Serveranfrage</li>
</ul>
<p>
  Diese Daten können nicht direkt bestimmten Personen zugeordnet werden. Eine Zusammenführung dieser Daten
  mit anderen Datenquellen wird nicht vorgenommen. Wir behalten uns vor, diese Daten nachträglich zu
  prüfen, wenn uns konkrete Anhaltspunkte für eine rechtswidrige Nutzung bekannt werden.
</p>
<p>
  Diese Daten sowie alle Daten dieser Website werden bei unserem Hosting-Provider {{ $hosting['name'] }}
  gespeichert, deren Datenschutzerklärung Sie
  <a href="{{ $hosting['privacy_url'] }}" target="_blank" rel="noopener noreferrer">hier</a> finden.
</p>
