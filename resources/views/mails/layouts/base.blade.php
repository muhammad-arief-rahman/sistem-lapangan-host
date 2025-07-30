<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        {{ config('app.name') }}
    </title>

    @include('mails.layouts.partials.head-data')
</head>

<body>
    <main>
        @yield('content')
    </main>
    <br>
    <footer>
        <p style="font-size: 0.875rem; color: #6b7280;">
            Email Otomatis Dari: {{ config('app.name') }}. {{ date('Y') }}.
        </p>
    </footer>
</body>

</html>
