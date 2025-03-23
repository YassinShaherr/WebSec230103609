<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env("APP_NAME")}}</title>
    @vite(["resources/css/app.css", "resources/js/app.js"])
</head>
<body class="bg-slate-100 text-slate-900 m-0">
    <header class="bg-slate-800 shadow-lg text-white">
        <nav class="flex justify-between items-center px-12 py-4">
            <a href="#" class="text-xl font-medium">Home</a>

            <div class="flex items-center gap-6">
                <a href="#" class="text-xl">Login</a>
                <a href="#" class="text-xl">Register</a>
            </div>
        </nav>
    </header>

    <main class="p-8">
        {{ $slot }}
    </main>
</body>
</html>