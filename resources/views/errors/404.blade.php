<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 - Not Found</title>
    @vite(['resources/css/app.css'])
</head>

<body class="flex items-center justify-center min-h-screen bg-brand-light text-brand-ink">

    <div class="text-center space-y-4">
        <h1 class="text-6xl font-bold text-brand-primary">404</h1>

        <p class="text-lg text-brand-secondary">
            The page you are looking for does not exist.
        </p>

        <a href="{{ route('home') }}"
           class="inline-block mt-4 px-6 py-3 bg-brand-primary text-white rounded-xl">
            Go Home
        </a>
    </div>

</body>
</html>