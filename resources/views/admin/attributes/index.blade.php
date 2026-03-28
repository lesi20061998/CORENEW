@extends('admin.layouts.app')
@section('title', 'Thuộc tính')
@section('page-title', 'Thuộc tính sản phẩm')
@section('page-subtitle', 'Quản lý màu sắc, kích thước và các thuộc tính lọc')
@section('page-actions')
    <a href="{{ route('admin.attributes.create') }}" class="btn-primary">
        <i class="fa-solid fa-plus"></i> Thêm thuộc tính
    </a>
@endsection

@section('content')
<div class="card" style="overflow:hidden;">
    <div class="card-header">
        <p style="font-size:13px;font-weight:700;color:#374151;">Tất cả thuộc tính</p>
    </div>
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#f8fafc;border-bottom:1.5px solid #f1f5f9;">
                <th class="tbl-th">Tên thuộc tính</th>
                <th class="tbl-th">Loại</th>
                <th class="tbl-th">Có thể lọc</th>
                <th class="tbl-th">Giá trị</th>
                <th class="tbl-th" style="text-align:right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attributes as $attribute)
            <tr class="tbl-tr">
                <td class="tbl-td">
                    <p style="font-size:13.5px;font-weight:600;color:#0f172a;">{{ $attribute->name }}</p>
                    <p style="font-size:11.5px;color:#94a3b8;font-family:monospace;margin-top:2px;">{{ $attribute->slug }}</p>
                </td>
                <td class="tbl-td"><span class="badge badge-blue">{{ ucfirst($attribute->type) }}</span></td>
                <td class="tbl-td">
                    <span class="badge {{ $attribute->is_filterable ? 'badge-green' : 'badge-gray' }}">
                        {{ $attribute->is_filterable ? 'Có' : 'Không' }}
                    </span>
                </td>
                <td class="tbl-td">
                    <div style="display:flex;flex-wrap:wrap;gap:4px;">
                        @foreach($attribute->values->take(6) as $val)
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 8px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;font-size:11.5px;font-weight:500;color:#475569;">
                                @if($val->color_code)
                                    <span style="width:10px;height:10px;border-radius:50%;background:{{ $val->color_code }};border:1px solid rgba(0,0,0,.1);flex-shrink:0;"></span>
                                @endif
                                {{ $val->value }}
                            </span>
                        @endforeach
                        @if($attribute->values->count() > 6)
                            <span style="font-size:11.5px;color:#94a3b8;align-self:center;">+{{ $attribute->values->count() - 6 }}</span>
                        @endif
                    </div>
                </td>
                <td class="tbl-td" style="text-align:right;">
                    <div style="display:flex;align-items:center;justify-content:flex-end;gap:4px;">
                        <a href="{{ route('admin.attributes.show', $attribute) }}" class="act-btn view" title="Xem">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.attributes.edit', $attribute) }}" class="act-btn edit" title="Sửa">
                            <i class="fa-solid fa-pencil"></i>
                        </a>
                        <form action="{{ route('admin.attributes.destroy', $attribute) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Xóa thuộc tính và tất cả giá trị?')" class="act-btn del" title="Xóa">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:60px 20px;text-align:center;color:#94a3b8;">
                    <i class="fa-solid fa-tags" style="font-size:40px;opacity:.3;display:block;margin-bottom:12px;"></i>
                    <p style="font-size:14px;font-weight:600;color:#64748b;">Chưa có thuộc tính nào</p>
                    <a href="{{ route('admin.attributes.create') }}" style="font-size:13px;color:#2563eb;margin-top:6px;display:inline-block;">Tạo thuộc tính đầu tiên <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
