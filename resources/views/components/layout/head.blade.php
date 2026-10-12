@php
  $siteName = config('site.name');
  $pageTitle = trim($__env->yieldContent('meta_title'));
  $fullTitle = $pageTitle !== '' ? $pageTitle.' – '.$siteName : config('site.title');
  $description = trim($__env->yieldContent('meta_description', config('site.description')));
  $ogImage = asset(config('site.og_image'));
  $ogImageAlt = config('site.og_image_alt');
  $currentUrl = url()->current();
@endphp
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $fullTitle }}</title>
<meta name="description" content="{{ $description }}">
<link rel="canonical" href="{{ $currentUrl }}">
<meta name="theme-color" content="{{ config('site.theme_color') }}">

{{-- Favicons --}}
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<link rel="icon" href="/favicon.ico" sizes="32x32">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<meta name="apple-mobile-web-app-title" content="{{ $siteName }}">

{{-- Open Graph --}}
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:title" content="{{ $fullTitle }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ $currentUrl }}">
<meta property="og:locale" content="de_CH">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="{{ $ogImageAlt }}">

{{-- Twitter --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $fullTitle }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $ogImage }}">
<meta name="twitter:image:alt" content="{{ $ogImageAlt }}">

@if ($fonts = config('site.fonts.stylesheet'))
<link rel="preconnect" href="{{ parse_url($fonts, PHP_URL_SCHEME).'://'.parse_url($fonts, PHP_URL_HOST) }}" crossorigin>
<link rel="stylesheet" href="{{ $fonts }}">
@endif

@vite(['resources/css/app.css', 'resources/js/app.js'])

@stack('head')

{{-- Google Analytics (nur wenn GOOGLE_ANALYTICS_ID gesetzt ist) --}}
@if ($gaId = config('services.google_analytics.id'))
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '{{ $gaId }}');
</script>
@endif
</head>
