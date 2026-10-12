{{-- XML-Deklaration kommt aus der Route (routes/web.php), siehe dort. --}}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($pages as $page)
  <url>
    <loc>{{ route($page['route']) }}</loc>
    <changefreq>{{ $page['sitemap'][0] }}</changefreq>
    <priority>{{ $page['sitemap'][1] }}</priority>
  </url>
@endforeach
</urlset>
