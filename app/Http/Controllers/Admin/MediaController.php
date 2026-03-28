<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function __construct(protected MediaService $mediaService) {}

    public function index(Request $request)
    {
        $folder       = $request->get('folder', 'Chung');
        $media        = $this->mediaService->getPaginated(24, $folder);
        $folderCounts = $this->mediaService->getFolderCounts();
        $rootFolders  = Media::$rootFolders;

        return view('admin.media.index', compact('media', 'folder', 'folderCounts', 'rootFolders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file'   => 'required|file|max:10240',
            'alt'    => 'nullable|string|max:255',
            'folder' => 'nullable|string|max:100',
        ]);

        $folder = $request->input('folder', 'Chung');
        $media  = $this->mediaService->upload($request->file('file'), $folder);

        if ($request->filled('alt')) {
            $media->update(['alt' => $request->input('alt')]);
        }

        if ($request->expectsJson()) {
            return response()->json(['id' => $media->id, 'url' => $media->url, 'name' => $media->name]);
        }

        return redirect()->route('admin.media.index', ['folder' => $folder])
            ->with('success', 'Tải lên thành công.');
    }

    public function moveFiles(Request $request)
    {
        $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'integer',
            'folder' => 'required|string|max:100',
        ]);

        $count = $this->mediaService->moveToFolder($request->input('ids'), $request->input('folder'));

        return response()->json(['moved' => $count]);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer',
        ]);

        $count = $this->mediaService->deleteMany($request->input('ids'));

        if ($request->expectsJson()) {
            return response()->json(['deleted' => $count]);
        }

        return redirect()->route('admin.media.index')->with('success', "Đã xóa {$count} tệp.");
    }

    public function destroy(int $id)
    {
        $this->mediaService->delete($id);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.media.index')->with('success', 'Đã xóa tệp.');
    }

    public function picker(Request $request)
    {
        $folder  = $request->get('folder', '') ?? '';
        $search  = $request->get('search', '') ?? '';
        $media   = $this->mediaService->getForPicker($folder, $search);
        $folders = $this->mediaService->getAllFolders();

        return response()->json([
            'items'   => $media->map(fn($m) => [
                'id'   => $m->id,
                'url'  => $m->url,
                'name' => $m->name,
                'size' => $m->size,
                'mime' => $m->mime_type,
                'w'    => $m->width,
                'h'    => $m->height,
            ]),
            'folders' => $folders,
        ]);
    }

    public function createFolder(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);
        $name = trim($request->input('name'));

        return response()->json(['folder' => $name]);
    }
}
