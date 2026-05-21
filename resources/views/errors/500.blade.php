<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>500 - Server Error</title>
    @vite(['resources/css/app.css'])
</head>

<body class="flex items-center justify-center min-h-screen bg-brand-light text-brand-ink">

    <div class="text-center space-y-4">
        <h1 class="text-6xl font-bold text-red-600">500</h1>

        <p class="text-lg text-brand-secondary">
            Something went wrong on our side.
        </p>

        <a href="{{ route('home') }}"
           class="inline-block mt-4 px-6 py-3 bg-brand-primary text-white rounded-xl">
            Go Home
        </a>
    </div>

</body>
</html>