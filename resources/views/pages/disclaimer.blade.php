@extends('app')
@section('meta_title', 'Disclaimer')
@section('meta_description', 'Haftungsausschluss und Nutzungsbedingungen dieser Website.')

@section('content')

  <x-sections.text>
    <x-headings.h1>Disclaimer</x-headings.h1>

    <x-headings.h3>Haftungsausschluss</x-headings.h3>
    <p>
      Für die Richtigkeit der veröffentlichten Informationen wird keine Gewähr übernommen. Die enthaltenen
      Visualisierungen und Pläne dienen der allgemeinen Information und erfolgen ohne Gewähr. Sie bilden nicht
      integrierenden Bestandteil einer vertraglichen Vereinbarung und es können keinerlei Ansprüche abgeleitet
      werden. Ausführungsbedingte Änderungen und Anpassungen bleiben vorbehalten. Preisänderungen und
      Zwischenvermietung bleiben vorbehalten.
    </p>

    <x-headings.h3>Urheber- und Nutzungsrecht</x-headings.h3>
    <p>
      Die auf dieser Website publizierten Bilder und Texte dürfen ohne unsere Zustimmung weder ganz noch
      teilweise kopiert, bearbeitet oder sonst wie verwendet werden. Ausgeschlossen ist insbesondere auch eine
      Einspeisung auf Online-Dienste, unabhängig davon, ob Bilder und Texte zu diesem Zweck bearbeitet werden
      oder nicht. Jeder Verstoss gegen dieses Verbot wird vom Urheber rechtlich verfolgt.
    </p>
  </x-sections.text>

@endsection
