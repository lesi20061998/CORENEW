@extends('admin.layouts.app')
@section('title', 'Thùng rác bài viết')
@section('page-title', 'Thùng rác')
@section('page-subtitle', 'Xem và khôi phục các bài viết đã xóa tạm thời')
@section('page-actions')
    <a href="{{ route('admin.posts.index') }}" style="text-decoration:none;color:#64748b;font-size:13px;font-weight:600;display:flex;align-items:center;gap:6px;">
        <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách
    </a>
@endsection

@section('content')
<div class="card" style="overflow:hidden;border:1.5px solid #fee2e2;">
    <div class="card-header" style="padding:16px 20px;display:flex;align-items:center;justify-content:space-between;background:#fef2f2;border-bottom:1.5px solid #fee2e2;">
        <div style="display:flex;align-items:center;">
            {{-- Tabs Like Image --}}
            <div style="display:flex;align-items:center;gap:10px;">
                {{-- Tab All --}}
                <a href="{{ route('admin.posts.index') }}" title="Tất cả bài viết" style="position:relative;width:40px;height:40px;display:flex;align-items:center;justify-content:center;border-radius:10px;text-decoration:none;transition:all .2s;{{ !request()->routeIs('*.trash') ? 'background:#3b82f6;' : 'background:#fff;border:1px solid #e2e8f0;' }}">
                    <i class="fa-solid fa-pencil" style="{{ !request()->routeIs('*.trash') ? 'color:#fff;' : 'color:#94a3b8;' }}font-size:14px;"></i>
                    <span style="position:absolute;-top:8px;-right:8px;background:#f1f5f9;color:#0f172a;font-size:10px;font-weight:800;padding:2px 6px;border-radius:10px;border:2px solid #fff;box-shadow:0 2px 4px rgba(0,0,0,0.05);">{{ $counts['all'] }}</span>
                </a>

                <div style="width:2px;height:24px;background:#3b82f6;border-radius:2px;opacity:0.8;"></div>

                {{-- Tab Trash --}}
                <a href="{{ route('admin.posts.trash') }}" title="Thùng rác" style="position:relative;width:40px;height:40px;display:flex;align-items:center;justify-content:center;border-radius:10px;text-decoration:none;transition:all .2s;{{ request()->routeIs('*.trash') ? 'background:#3b82f6;box-shadow:0 4px 12px rgba(59,130,246,0.3);' : 'background:#fff;border:1px solid #e2e8f0;' }}">
                    <i class="fa-solid fa-trash-can" style="{{ request()->routeIs('*.trash') ? 'color:#fff;' : 'color:#94a3b8;' }}font-size:14px;"></i>
                    <span style="position:absolute;-top:8px;-right:8px;background:#f1f5f9;color:#0f172a;font-size:10px;font-weight:800;padding:2px 6px;border-radius:10px;border:2px solid #fff;box-shadow:0 2px 4px rgba(0,0,0,0.05);">{{ $counts['trashed'] }}</span>
                </a>
            </div>
        </div>
        <div>
            <span style="font-size:12px;font-weight:700;color:#991b1b;background:#fee2e2;padding:6px 12px;border-radius:8px;">
                <i class="fa-solid fa-trash-can mr-1"></i> Đang xem thùng rác
            </span>
        </div>
    </div>
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#f8fafc;border-bottom:1.5px solid #f1f5f9;">
                <th class="tbl-th">Bài viết</th>
                <th class="tbl-th">Danh mục</th>
                <th class="tbl-th">Ngày xóa</th>
                <th class="tbl-th" style="text-align:right;min-width:180px;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts as $post)
            <tr class="tbl-tr">
                <td class="tbl-td">
                    <div style="display:flex;align-items:center;gap:12px;">
                        @if($post->thumbnail)
                            @php
                                $thumbUrl = $post->thumbnail;
                                if ($thumbUrl && !Str::startsWith($thumbUrl, ['http://', 'https://'])) {
                                    $thumbUrl = Str::startsWith($thumbUrl, 'storage/') ? asset($thumbUrl) : asset('storage/' . $thumbUrl);
                                }
                            @endphp
                            <img src="{{ $thumbUrl }}" style="width:42px;height:42px;border-radius:10px;object-fit:cover;opacity:.6;" alt="">
                        @else
                            <div style="width:42px;height:42px;border-radius:10px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fa-solid fa-newspaper" style="color:#cbd5e1;font-size:14px;"></i>
                            </div>
                        @endif
                        <div>
                            <p style="font-size:13.5px;font-weight:600;color:#64748b;text-decoration:line-through;">{{ $post->title }}</p>
                            <p style="font-size:11.5px;color:#94a3b8;font-family:monospace;margin-top:2px;">{{ $post->slug }}</p>
                        </div>
                    </div>
                </td>
                <td class="tbl-td" style="color:#94a3b8;font-size:13px;">{{ $post->category?->name ?? '—' }}</td>
                <td class="tbl-td" style="color:#94a3b8;font-size:12.5px;">{{ $post->deleted_at->format('d/m/Y H:i') }}</td>
                <td class="tbl-td" style="text-align:right;">
                    <div style="display:flex;align-items:center;justify-content:flex-end;gap:8px;">
                        <form action="{{ route('admin.posts.restore', $post->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="act-btn" style="background:#10b981;color:white;border:none;padding:6px 14px;border-radius:10px;font-size:12px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:6px;">
                                <i class="fa-solid fa-rotate-left"></i> Khôi phục
                            </button>
                        </form>
                        <form action="{{ route('admin.posts.force-delete', $post->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('XÓA VĨNH VIỄN bài viết này? Hành động này không thể hoàn tác!')" class="act-btn" style="background:#ef4444;color:white;border:none;padding:6px 14px;border-radius:10px;font-size:12px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:6px;">
                                <i class="fa-solid fa-circle-xmark"></i> Xóa vĩnh viễn
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding:60px 20px;text-align:center;color:#94a3b8;">
                    <i class="fa-solid fa-trash-can" style="font-size:40px;opacity:.1;display:block;margin-bottom:12px;"></i>
                    <p style="font-size:14px;font-weight:600;color:#cbd5e1;">Thùng rác trống</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($posts->hasPages())
        <div style="padding:14px 16px;border-top:1.5px solid #f1f5f9;">{{ $posts->links() }}</div>
    @endif
</div>
@endsection
