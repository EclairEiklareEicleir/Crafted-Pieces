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
        code="500"
        title="Something went wrong"
        message="Our server hit an unexpected issue. Please try again in a moment."
        primary-label="Back Home"
        secondary-label="Retry"
    />
</body>
</html>