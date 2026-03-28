<!DOCTYPE html>
<html lang="vi" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập — {{ get_setting('site_name', 'Kalles Store') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>body { font-family: 'Inter', 'Segoe UI', sans-serif; }</style>
</head>
<body class="h-full bg-slate-950 flex items-center justify-center p-4">

<div class="w-full max-w-sm">

    {{-- Logo --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl shadow-2xl shadow-blue-600/40 mb-4">
            <i class="fa-solid fa-store text-white text-2xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-white">{{ get_setting('site_name', 'Kalles Store') }}</h1>
        <p class="text-slate-400 text-sm mt-1">Đăng nhập vào trang quản trị</p>
    </div>

    {{-- Form card --}}
    <div class="bg-slate-900 rounded-2xl p-8 border border-slate-800 shadow-2xl">

        @if($errors->any())
            <div class="flex items-start gap-3 bg-red-500/10 border border-red-500/30 text-red-400 text-sm px-4 py-3 rounded-xl mb-6">
                <i class="fa-solid fa-circle-xmark mt-0.5 flex-shrink-0"></i>
                <span>@foreach($errors->all() as $e){{ $e }}@endforeach</span>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">
                    <i class="fa-solid fa-envelope text-slate-500 mr-1.5"></i>Địa chỉ email
                </label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    placeholder="admin@example.com"
                    class="w-full bg-slate-800 border border-slate-700 text-white text-sm rounded-xl px-4 py-3
                           placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">
                    <i class="fa-solid fa-lock text-slate-500 mr-1.5"></i>Mật khẩu
                </label>
                <input type="password" name="password" required placeholder="••••••••"
                    class="w-full bg-slate-800 border border-slate-700 text-white text-sm rounded-xl px-4 py-3
                           placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember"
                    class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-blue-600 focus:ring-blue-500">
                <label for="remember" class="text-sm text-slate-400 cursor-pointer">Ghi nhớ đăng nhập</label>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-sm py-3 rounded-xl
                       transition-colors duration-150 flex items-center justify-center gap-2 shadow-lg shadow-blue-600/30">
                <i class="fa-solid fa-right-to-bracket"></i> Đăng nhập
            </button>
        </form>
    </div>

    <p class="text-center text-xs text-slate-600 mt-6">
        © {{ date('Y') }} {{ get_setting('site_name', 'Kalles Store') }}. Bảo lưu mọi quyền.
    </p>
</div>

</body>
</html>
