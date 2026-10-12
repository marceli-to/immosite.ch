<!DOCTYPE html>
<html lang="de-CH">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>@yield('title') – {{ config('site.name') }} Verwaltung</title>
@vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-surface-alt">
  <header class="bg-surface">
    <x-layout.inner class="flex items-center justify-between py-16">
      <p class="font-bold">{{ config('site.name') }} · Verwaltung</p>
      @auth
        <form method="post" action="{{ route('admin.logout') }}">
          @csrf
          <button type="submit" class="cursor-pointer text-sm text-primary hover:underline">Abmelden ({{ auth()->user()->name }})</button>
        </form>
      @endauth
    </x-layout.inner>
  </header>
  <main>
    <x-layout.inner class="py-32 lg:py-48">
      @yield('content')
    </x-layout.inner>
  </main>
</body>
</html>
