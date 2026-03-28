@extends('admin.layouts.app')
@section('title', 'Quản lý bản dịch')
@section('page-title', 'Quản lý bản dịch')
@section('page-subtitle', 'Dịch trực tiếp nội dung sang các ngôn ngữ khác')

@section('content')

{{-- Bộ lọc --}}
<form method="GET" class="card mb-4">
    <div class="card-body" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
        <div>
            <label style="font-size:12px;font-weight:600;color:#64748b;display:block;margin-bottom:4px;">Ngôn ngữ</label>
            <select name="locale" onchange="this.form.submit()"
                    style="padding:7px 10px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;background:#fff;outline:none;">
                @foreach($languages as $lang)
                <option value="{{ $lang->code }}" {{ $locale === $lang->code ? 'selected' : '' }}>
                    {{ $lang->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div style="flex:1;min-width:200px;">
            <label style="font-size:12px;font-weight:600;color:#64748b;display:block;margin-bottom:4px;">Tìm kiếm</label>
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Tìm theo field hoặc nội dung..."
                   style="width:100%;padding:7px 10px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;outline:none;">
        </div>
        <input type="hidden" name="locale" value="{{ $locale }}">
        <button type="submit" class="btn btn-primary" style="padding:7px 16px;">
            <i class="fa-solid fa-magnifying-glass"></i> Tìm
        </button>
    </div>
</form>

@if($translations->isEmpty())
<div class="card">
    <div class="card-body" style="text-align:center;padding:60px 20px;color:#94a3b8;">
        <i class="fa-solid fa-language" style="font-size:40px;opacity:.3;display:block;margin-bottom:12px;"></i>
        <p style="font-size:14px;font-weight:600;color:#64748b;">Chưa có bản dịch nào cho ngôn ngữ này</p>
        <p style="font-size:13px;margin-top:6px;">Bản dịch được tạo tự động khi bạn lưu nội dung có hỗ trợ đa ngôn ngữ.</p>
    </div>
</div>
@else
<form action="{{ route('admin.translations.bulk') }}" method="POST">
    @csrf
    <div class="card" style="overflow:hidden;">
        <div class="card-header" style="justify-content:space-between;">
            <p style="font-size:13px;font-weight:700;color:#374151;">
                Bản dịch — <span style="color:#2563eb;">{{ strtoupper($locale) }}</span>
                <span style="background:#f1f5f9;color:#64748b;font-size:11px;font-weight:600;padding:2px 8px;border-radius:20px;margin-left:6px;">{{ $translations->total() }}</span>
            </p>
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-floppy-disk"></i> Lưu tất cả
            </button>
        </div>
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc;border-bottom:1.5px solid #f1f5f9;">
                    <th class="tbl-th" style="width:160px;">Loại</th>
                    <th class="tbl-th" style="width:140px;">Field</th>
                    <th class="tbl-th">Nội dung gốc</th>
                    <th class="tbl-th">Bản dịch ({{ strtoupper($locale) }})</th>
                </tr>
            </thead>
            <tbody>
                @foreach($translations as $tr)
                <tr class="tbl-tr">
                    <td class="tbl-td">
                        <span class="badge badge-blue" style="font-size:11px;">
                            {{ class_basename($tr->translatable_type) }}
                            @if($tr->translatable_id)
                                <span style="opacity:.6;">#{{ $tr->translatable_id }}</span>
                            @endif
                        </span>
                    </td>
                    <td class="tbl-td">
                        <code style="font-size:12px;color:#64748b;">{{ $tr->field }}</code>
                    </td>
                    <td class="tbl-td" style="color:#64748b;font-size:13px;max-width:240px;">
                        @php
                            $original = $tr->translatable?->getRawOriginal($tr->field) ?? '—';
                        @endphp
                        <span style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                            {{ $original }}
                        </span>
                    </td>
                    <td class="tbl-td">
                        <textarea name="translations[{{ $tr->id }}]"
                                  rows="2"
                                  style="width:100%;padding:6px 10px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;resize:vertical;outline:none;font-family:inherit;"
                                  onfocus="this.style.borderColor='#3b82f6'"
                                  onblur="this.style.borderColor='#e2e8f0'">{{ $tr->value }}</textarea>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($translations->hasPages())
        <div style="padding:14px 16px;border-top:1.5px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
            {{ $translations->links() }}
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-floppy-disk"></i> Lưu tất cả
            </button>
        </div>
        @endif
    </div>
</form>
@endif
@endsection
