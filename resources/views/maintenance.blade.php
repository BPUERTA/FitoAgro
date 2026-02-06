<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ setting('site_title', 'Sitio en mantenimiento') }}</title>
    @if(setting('favicon'))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . setting('favicon')) }}">
    @endif
    <style>
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
            background: #f7f9fb;
            color: #111827;
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .card {
            max-width: 560px;
            background: #fff;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 20px 60px rgba(16, 24, 40, 0.15);
            text-align: center;
        }
        .title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 12px;
        }
        .msg {
            color: #4b5563;
            font-size: 18px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    @php
        $maintenanceImage = setting('maintenance_image');
        $maintenanceLogo = setting('maintenance_logo');
    @endphp
    @if($maintenanceImage)
        <style>
            body {
                background: linear-gradient(180deg, rgba(0,0,0,0.35), rgba(0,0,0,0.35)), url('{{ asset('storage/' . $maintenanceImage) }}');
                background-size: cover;
                background-position: center;
            }
            .card { background: rgba(255,255,255,0.92); }
        </style>
    @endif
    <div class="card">
        @if($maintenanceLogo)
            <div style="margin-bottom: 16px;">
                <img src="{{ asset('storage/' . $maintenanceLogo) }}" alt="Logo" style="width: 100%; height: auto; display: block;">
            </div>
        @endif
        <div class="msg">{{ $message }}</div>
    </div>
</body>
</html>
