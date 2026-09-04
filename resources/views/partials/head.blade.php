<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}" />

<title>
    {{ filled($title ?? null) ? $title.' - '.config('app.name', 'Laravel') : config('app.name', 'Laravel') }}
</title>

<link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
<link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
<link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Pinyon+Script&family=Caveat:wght@600;700&display=swap" rel="stylesheet">

@fonts

@livewireStyles
@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
