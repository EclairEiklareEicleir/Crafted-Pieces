<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Crafted Pieces') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/crafted-pieces-logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/crafted-pieces-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/crafted-pieces-logo.png') }}">
    @vite(['resources/css/app.css'])
</head>

<body class="text-brand-ink antialiased">
    <x-error-shell
        code="403"
        title="Access denied"
        message="You do not have permission to view this page."
        primary-label="Back Home"
        secondary-label="Go Back"
    />
</body>
</html>