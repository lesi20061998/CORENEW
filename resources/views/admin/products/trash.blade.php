@extends('admin.layouts.app')
@section('title', 'Thùng rác sản phẩm')
@section('page-title', 'Thùng rác sản phẩm')
@section('page-subtitle', 'Khôi phục hoặc xóa vĩnh viễn các sản phẩm đã xóa tạm')
@section('page-actions')
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left"></i> QUAY LẠI
    </a>
@endsection

@section('content')
    <div class="card shadow-sm border-slate-200 overflow-hidden">
        <div class="card-header bg-white border-b border-slate-100 flex items-center justify-between py-5 px-6">
            <div class="flex items-center gap-6">
                 {{-- Tabs Like Image --}}
                <div class="flex items-center gap-2">
                    {{-- Tab All --}}
                    <a href="{{ route('admin.products.index') }}" title="Đang bán" class="relative w-12 h-12 flex items-center justify-center rounded-2xl transition-all duration-300 {{ !request()->routeIs('*.trash') ? 'bg-blue-600 shadow-xl shadow-blue-500/30' : 'bg-white border border-slate-100 shadow-sm' }}">
                        <i class="fa-solid fa-pencil text-sm {{ !request()->routeIs('*.trash') ? 'text-white' : 'text-slate-400' }}"></i>
                        <span class="absolute -top-2 -right-2 bg-slate-100 text-slate-900 text-[10px] font-black px-2 py-0.5 rounded-full border-2 border-white shadow-sm">{{ $counts['all'] }}</span>
                    </a>

                    <div class="w-0.5 h-8 bg-blue-600/30 rounded-full mx-1"></div>

                    {{-- Tab Trash --}}
                    <a href="{{ route('admin.products.trash') }}" title="Thùng rác" class="relative w-12 h-12 flex items-center justify-center rounded-2xl transition-all duration-300 {{ request()->routeIs('*.trash') ? 'bg-blue-600 shadow-xl shadow-blue-500/30' : 'bg-white border border-slate-100 shadow-sm' }}">
                        <i class="fa-solid fa-trash-can text-sm {{ request()->routeIs('*.trash') ? 'text-white' : 'text-slate-400' }}"></i>
                        <span class="absolute -top-2 -right-2 bg-slate-100 text-slate-900 text-[10px] font-black px-2 py-0.5 rounded-full border-2 border-white shadow-sm">{{ $counts['trashed'] }}</span>
                    </a>
                </div>
            </div>
            <div class="px-4 py-2 bg-rose-50 text-rose-600 rounded-xl border border-rose-100 text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
               <i class="fa-solid fa-trash-can"></i> Đang xem thùng rác
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/50">
                    <tr class="border-b border-slate-100">
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest w-[420px]">Thông tin mặt hàng</th>
                        <th class="px-4 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Loại hình</th>
                        <th class="px-4 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest w-40">Hạch toán giá</th>
                        <th class="px-4 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest w-28">Lưu kho</th>
                        <th class="px-4 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest w-40">Ngày xóa</th>
                        <th class="px-6 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest w-48">Quản lý</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($products as $product)
                        <tr class="hover:bg-rose-50/30 transition-all group">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-[24px] border border-slate-100 overflow-hidden bg-white shadow-sm flex-shrink-0 opacity-50 grayscale">
                                        @if($product->image)
                                            <img src="{{ $product->image }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-200 bg-slate-50">
                                                <i class="fa-solid fa-panorama text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 overflow-hidden">
                                        <span class="text-sm font-black text-slate-400 line-through block leading-tight mb-1 truncate">{{ $product->name }}</span>
                                        <span class="text-[10px] font-black text-slate-300 font-mono tracking-tighter uppercase">{{ $product->sku ?: 'NO-SKU' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-5 text-xs font-bold text-slate-500">
                                @if($product->categories->count() > 0)
                                    {{ $product->categories->first()->name }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-5">
                                <span class="text-sm font-black text-slate-400 line-through">{{ number_format((float) $product->price, 0, ',', '.') }}đ</span>
                            </td>
                            <td class="px-4 py-5">
                                <span class="text-xs font-black text-slate-400">Tồn: {{ $product->stock }}</span>
                            </td>
                            <td class="px-4 py-5 text-center">
                                <span class="text-[11px] font-black text-slate-500">{{ $product->deleted_at->format('d/m/Y') }}</span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.products.restore', $product->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="w-10 h-10 rounded-2xl bg-white border border-emerald-100 text-emerald-500 flex items-center justify-center hover:bg-emerald-500 hover:text-white transition-all shadow-sm" title="Khôi phục">
                                            <i class="fa-solid fa-rotate-left text-sm"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.products.force-delete', $product->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('XÓA VĨNH VIỄN sản phẩm này?')" class="w-10 h-10 rounded-2xl bg-white border border-rose-100 text-rose-500 flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all shadow-sm" title="Xóa vĩnh viễn">
                                            <i class="fa-solid fa-trash-can text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-24 text-center">
                                <div class="flex flex-col items-center gap-4">
                                     <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-200">
                                        <i class="fa-solid fa-trash-can text-4xl"></i>
                                    </div>
                                    <p class="text-sm font-black text-slate-300 uppercase tracking-widest">Thùng rác sản phẩm đang trống</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="px-8 py-6 border-t border-slate-100 bg-slate-50/50">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection
