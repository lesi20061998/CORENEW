@extends('admin.layouts.app')
@section('title', 'Bài viết')
@section('page-title', 'Danh sách bài viết')
@section('page-subtitle', 'Quản lý tất cả bài viết và tin tức')
@section('page-actions')
    <a href="{{ route('admin.posts.trash') }}" class="btn-outline-danger" style="margin-right:12px;border:1.5px solid #ef4444;color:#ef4444;padding:8px 16px;border-radius:12px;text-decoration:none;font-size:13px;font-weight:700;display:inline-flex;align-items:center;gap:8px;transition:all .15s;">
        <i class="fa-solid fa-trash-can"></i> Thùng rác
    </a>
    <a href="{{ route('admin.posts.create') }}" class="btn-primary">
        <i class="fa-solid fa-plus"></i> Thêm bài viết
    </a>
@endsection

@section('content')
<div class="card" style="overflow:hidden;">
    <div class="card-header" style="padding:16px 20px;display:flex;align-items:center;justify-content:space-between;">
        <div style="display:flex;align-items:center;">
            {{-- Tabs Like Image --}}
            <div style="display:flex;align-items:center;gap:10px;">
                {{-- Tab All --}}
                <a href="{{ route('admin.posts.index') }}" title="Tất cả bài viết" style="position:relative;width:40px;height:40px;display:flex;align-items:center;justify-content:center;border-radius:10px;text-decoration:none;transition:all .2s;{{ !request()->routeIs('*.trash') ? 'background:#3b82f6;box-shadow:0 4px 12px rgba(59,130,246,0.3);' : 'background:#fff;border:1px solid #e2e8f0;' }}">
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
            @if(request()->routeIs('*.trash'))
                <span style="font-size:12px;font-weight:700;color:#991b1b;background:#fef2f2;padding:6px 12px;border-radius:8px;border:1px solid #fee2e2;">
                    <i class="fa-solid fa-trash-can mr-1"></i> Đang xem thùng rác
                </span>
            @endif
        </div>
    </div>
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#f8fafc;border-bottom:1.5px solid #f1f5f9;">
                <th class="tbl-th">Bài viết</th>
                <th class="tbl-th">Danh mục</th>
                <th class="tbl-th">Trạng thái</th>
                <th class="tbl-th">Ngày tạo</th>
                <th class="tbl-th" style="text-align:right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts as $post)
            <tr class="tbl-tr">
                <td class="tbl-td">
                    <div style="display:flex;align-items:center;gap:12px;">
                        @if($post->thumbnail)
                            <img src="{{ $post->thumbnail }}" style="width:42px;height:42px;border-radius:10px;object-fit:cover;border:1.5px solid #f1f5f9;" alt="">
                        @else
                            <div style="width:42px;height:42px;border-radius:10px;background:#eff6ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fa-solid fa-newspaper" style="color:#93c5fd;font-size:14px;"></i>
                            </div>
                        @endif
                        <div>
                            <p style="font-size:13.5px;font-weight:600;color:#0f172a;">{{ $post->title }}</p>
                            <p style="font-size:11.5px;color:#94a3b8;font-family:monospace;margin-top:2px;">{{ $post->slug }}</p>
                        </div>
                    </div>
                </td>
                <td class="tbl-td" style="color:#64748b;font-size:13px;">{{ $post->category?->name ?? '—' }}</td>
                <td class="tbl-td" x-data="{ 
                    status: '{{ $post->status }}', 
                    isFeatured: {{ $post->is_featured ? 'true' : 'false' }},
                    async toggleFeatured() {
                        this.isFeatured = !this.isFeatured;
                        await this.update({ is_featured: this.isFeatured });
                    },
                    async updateStatus(newStatus) {
                        this.status = newStatus;
                        await this.update({ status: this.status });
                    },
                    async update(data) {
                        try {
                            const response = await fetch('{{ route('admin.posts.quick-update', $post->id) }}', {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify(data)
                            });
                            const res = await response.json();
                            if(!res.success) alert(res.message);
                        } catch(e) { alert('Lỗi hệ thống!'); }
                    }
                }">
                    <div style="display:flex;align-items:center;gap:6px;">
                        {{-- Status Quick Switch --}}
                        <div class="status-selector" style="position:relative;" x-data="{ open: false }" @click.away="open = false">
                            <span @click="open = !open" :class="{
                                'badge-green': status === 'published',
                                'badge-blue': status === 'scheduled',
                                'badge-yellow': status === 'draft'
                            }" class="badge cursor-pointer" style="display:inline-flex;align-items:center;gap:4px;user-select:none;">
                                <template x-if="status === 'published'"><i class="fa-solid fa-circle" style="font-size:6px;"></i></template>
                                <template x-if="status === 'scheduled'"><i class="fa-solid fa-clock" style="font-size:9px;"></i></template>
                                <template x-if="status === 'draft'"><i class="fa-solid fa-circle" style="font-size:6px;"></i></template>
                                <span x-text="status === 'published' ? 'Đã xuất bản' : (status === 'scheduled' ? 'Lên lịch' : 'Bản nháp')"></span>
                                <i class="fa-solid fa-chevron-down" style="font-size:8px;opacity:.5;margin-left:2px;"></i>
                            </span>
                            
                            <div x-show="open" x-transition class="dropdown-menu" style="position:absolute;top:100%;left:0;z-index:50;background:white;border:1px solid #e2e8f0;border-radius:8px;box-shadow:0 10px 15px -3px rgba(0,0,0,0.1);padding:4px;margin-top:4px;min-width:120px;">
                                <div @click="updateStatus('published'); open = false" class="dropdown-item" style="padding:6px 10px;font-size:12px;cursor:pointer;border-radius:4px;hover:background:#f8fafc;">Đã xuất bản</div>
                                <div @click="updateStatus('scheduled'); open = false" class="dropdown-item" style="padding:6px 10px;font-size:12px;cursor:pointer;border-radius:4px;hover:background:#f8fafc;">Lên lịch</div>
                                <div @click="updateStatus('draft'); open = false" class="dropdown-item" style="padding:6px 10px;font-size:12px;cursor:pointer;border-radius:4px;hover:background:#f8fafc;">Bản nháp</div>
                            </div>
                        </div>

                        {{-- Featured Toggle --}}
                        <span @click="toggleFeatured()" :class="isFeatured ? 'badge-orange' : 'badge-gray'" class="badge cursor-pointer" style="display:inline-flex;align-items:center;gap:4px;transition:all .2s;user-select:none;">
                            <i class="fa-solid fa-star" :style="isFeatured ? 'color:#f59e0b' : 'color:#cbd5e1'" style="font-size:9px;"></i>
                            <span x-text="isFeatured ? 'Nổi bật' : 'Thường'"></span>
                        </span>
                    </div>
                </td>
                <td class="tbl-td" style="color:#94a3b8;font-size:12.5px;">{{ $post->created_at->format('d/m/Y') }}</td>
                <td class="tbl-td" style="text-align:right;">
                    <div style="display:flex;align-items:center;justify-content:flex-end;gap:4px;">
                        <a href="{{ route('admin.posts.edit', $post) }}" class="act-btn edit" title="Chỉnh sửa">
                            <i class="fa-solid fa-pencil"></i>
                        </a>
                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Xóa bài viết này?')" class="act-btn del" title="Xóa">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:60px 20px;text-align:center;color:#94a3b8;">
                    <i class="fa-solid fa-newspaper" style="font-size:40px;opacity:.3;display:block;margin-bottom:12px;"></i>
                    <p style="font-size:14px;font-weight:600;color:#64748b;">Chưa có bài viết nào</p>
                    <a href="{{ route('admin.posts.create') }}" style="font-size:13px;color:#2563eb;margin-top:6px;display:inline-block;">Viết bài đầu tiên <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($posts->hasPages())
        <div style="padding:14px 16px;border-top:1.5px solid #f1f5f9;">{{ $posts->links() }}</div>
    @endif
</div>

<style>
    .cursor-pointer { cursor: pointer; }
    .badge-gray { background: #f1f5f9; color: #64748b; border: 1.5px solid #e2e8f0; }
    .dropdown-item:hover { background: #f8fafc; color: #3b82f6; }
</style>
@endsection
