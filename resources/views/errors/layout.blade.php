<!DOCTYPE html>
<html lang="vi" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('code') - @yield('title') | {{ get_setting('site_name', 'VietTin Mart') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        * {
            font-family: 'Be Vietnam Pro', sans-serif !important;
            font-style: normal !important;
        }
        body {
            background-color: #f8fafc;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            overflow: hidden;
        }
        .error-card {
            background: white;
            padding: 5rem;
            border-radius: 40px;
            box-shadow: 0 40px 100px rgba(15, 23, 42, 0.08);
            border: 1px solid #f1f5f9;
            text-align: center;
            max-width: 600px;
            width: 90%;
            position: relative;
            z-index: 10;
        }
        .error-code {
            font-size: 130px;
            font-weight: 900;
            line-height: 1;
            background: linear-gradient(135deg, @yield('color-start', '#3b82f6'), @yield('color-end', '#1d4ed8'));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 2rem;
            letter-spacing: -0.1em;
            display: inline-block;
        }
        .error-title {
            font-size: 24px;
            font-weight: 800;
            color: #1e293b;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }
        .error-message {
            font-size: 15px;
            color: #64748b;
            line-height: 1.8;
            margin-bottom: 3rem;
            font-weight: 500;
        }
        .btn-home {
            background: #0f172a;
            color: white;
            padding: 18px 48px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.2);
        }
        .btn-home:hover {
            transform: translateY(-5px);
            background: #1e293b;
            box-shadow: 0 30px 60px rgba(15, 23, 42, 0.3);
        }
        .decoration-circle {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, @yield('color-start', '#3b82f6')10, @yield('color-end', '#1d4ed8')10);
            z-index: 1;
        }
    </style>
</head>
<body>
    <div class="decoration-circle" style="width: 500px; height: 500px; top: -100px; left: -100px;"></div>
    <div class="decoration-circle" style="width: 300px; height: 300px; bottom: -50px; right: -50px;"></div>

    <div class="error-card">
        <div class="error-code">@yield('code')</div>
        <h1 class="error-title">@yield('title')</h1>
        <p class="error-message">@yield('message')</p>
        <a href="/" class="btn-home">
            Quay về trang chủ
        </a>
    </div>

    <div style="position: fixed; bottom: 30px; text-align: center; width: 100%;">
        <p style="font-size: 10px; font-weight: 800; color: #cbd5e1; text-transform: uppercase; letter-spacing: 0.3em;">
            &copy; {{ date('Y') }} {{ get_setting('site_name', 'VietTin Mart') }} - Ultimate Reliability
        </p>
    </div>
</body>
</html>
