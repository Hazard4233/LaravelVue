@php
$config = [
    'appName' => config('app.name'),
    'centralDomain' => data_get(config('tenancy.central_domains'),'0'),
    'isTenant' => tenancy()->initialized,
    'domainStart' => data_get(
      explode('.', request()->route()->domain()),
      0
    ),
    'tenantDomain' => !tenancy()->initialized ? null : tenancy()->tenant->domains()->first()->domain,
    'locale' => $locale = app()->getLocale(),
    'locales' => config('app.locales'),
    'githubAuth' => config('services.github.client_id'),
];
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>{{ config('app.name') }}</title>

  <link rel="stylesheet" href="{{ mix('dist/css/app.css') }}">
</head>
<body>
  <div id="app"></div>

  {{-- Global configuration object --}}
  <script>
    window.config = @json($config);
  </script>

  {{-- Load the application scripts --}}
  <script src="{{ mix('dist/js/app.js') }}"></script>
</body>
</html>
