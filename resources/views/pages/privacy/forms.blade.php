<x-headings.h3 class="mt-32">Kontakt- und Anmeldeformular</x-headings.h3>
<p class="font-bold">Umfang, Zweck und Rechtsgrundlage der Datenverarbeitung</p>
<p>
  Auf unserer Internetseite ist ein Formular vorhanden, welches für die elektronische Kontaktaufnahme
  beziehungsweise Anmeldung genutzt werden kann. Nehmen Sie diese Möglichkeit wahr, so werden die in der
  Eingabemaske eingegebenen Daten an uns übermittelt und gespeichert. Diese Daten sind:
</p>
<ul>
  <li>Name und Vorname</li>
  <li>Adresse, sofern angegeben</li>
  <li>Telefonnummer, sofern angegeben</li>
  <li>E-Mail-Adresse</li>
  <li>Nachricht und Auswahl, sofern angegeben</li>
</ul>
<p>Im Zeitpunkt der Absendung werden zudem Datum und Uhrzeit gespeichert.</p>
<p>
  Für die Verarbeitung der Daten wird im Rahmen des Absendevorgangs Ihre Einwilligung eingeholt und auf
  diese Datenschutzerklärung verwiesen. Die Daten dienen allein der Bearbeitung Ihrer Anfrage beziehungsweise
  Anmeldung und werden an die mit der Vermarktung beauftragte {{ config('site.marketer.name') }} weitergegeben.
</p>
@if (config('services.turnstile.site_key'))
  <p>
    Zum Schutz vor automatisierten Anfragen verwenden wir Cloudflare Turnstile (Cloudflare, Inc., 101 Townsend
    St, San Francisco, CA 94107, USA). Dabei werden technische Daten Ihres Browsers an Cloudflare übermittelt.
  </p>
@endif
<p class="font-bold">Dauer der Speicherung und Widerspruchsrecht</p>
<p>
  Sie haben jederzeit die Möglichkeit, Ihre Einwilligung zur Verarbeitung der personenbezogenen Daten per
  E-Mail an <a href="mailto:{{ config('site.marketer.email') }}">{{ config('site.marketer.email') }}</a>
  zu widerrufen. Alle personenbezogenen Daten, die im Zuge der Kontaktaufnahme gespeichert wurden, werden in
  diesem Fall gelöscht.
</p>
