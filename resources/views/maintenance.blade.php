<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Mode - {{ $settings->site_name ?? ($profile->name ?? 'Portfolio') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full bg-slate-950 text-slate-100 flex items-center justify-center p-6 font-sans">
    <div class="max-w-md text-center space-y-6">
        <div class="w-16 h-16 bg-emerald-500/20 text-emerald-400 rounded-2xl flex items-center justify-center mx-auto text-2xl font-bold">
            🛠️
        </div>
        <h1 class="text-3xl font-extrabold text-white">Under Maintenance</h1>
        <p class="text-slate-400 text-base leading-relaxed">
            {{ $settings->maintenance_message ?? 'We are currently updating our portfolio. Please check back soon!' }}
        </p>
    </div>
</body>
</html>

