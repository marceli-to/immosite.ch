{{-- Die XML-Deklaration wird zusammengesetzt, damit "<?" nie woertlich in der
     Datei steht: Blade liest es bei short_open_tag=On als PHP-Tag. --}}
{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($pages as $page)
  <url>
    <loc>{{ route($page['route']) }}</loc>
    <changefreq>{{ $page['sitemap'][0] }}</changefreq>
    <priority>{{ $page['sitemap'][1] }}</priority>
  </url>
@endforeach
</urlset>
