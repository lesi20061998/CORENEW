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
        $folderId = $request->get('folder_id');
        $currentFolder = $folderId ? \App\Models\MediaFolder::find($folderId) : null;
        
        $folders = \App\Models\MediaFolder::where('parent_id', $folderId)->orderBy('sort_order')->orderBy('name')->get();
        $media   = $this->mediaService->getPaginated(40, $folderId);
        $allFolders = \App\Models\MediaFolder::all();
        
        $breadcrumbs = [];
        if ($currentFolder) {
            $temp = $currentFolder;
            while($temp) {
                array_unshift($breadcrumbs, $temp);
                $temp = $temp->parent;
            }
        }

        return view('admin.media.index', compact('media', 'folders', 'currentFolder', 'breadcrumbs', 'allFolders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file'      => 'nullable|file|max:10240',
            'files'     => 'nullable|array',
            'files.*'   => 'file|max:10240',
            'alt'       => 'nullable|string|max:255',
            'folder_id' => 'nullable|integer|exists:media_folders,id',
        ]);

        $folderId = $request->input('folder_id');
        if ($folderId === '') $folderId = null;
        
        $results = [];

        // Single file upload
        if ($request->hasFile('file')) {
            $media = $this->mediaService->upload($request->file('file'), $folderId);
            if ($request->filled('alt')) {
                $media->update(['alt' => $request->input('alt')]);
            }
            $results[] = $media;
        }

        // Multiple file upload
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $results[] = $this->mediaService->upload($file, $folderId);
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'items'   => collect($results)->map(fn($m) => [
                    'id'   => $m->id, 
                    'url'  => $m->url, 
                    'name' => $m->name
                ])
            ]);
        }

        return redirect()->route('admin.media.index', ['folder_id' => $folderId])
            ->with('success', 'Tải lên thành công ' . count($results) . ' tệp.');
    }

    public function moveFiles(Request $request)
    {
        $request->validate([
            'ids'       => 'required|array',
            'ids.*'     => 'integer',
            'folder_id' => 'nullable|integer|exists:media_folders,id',
        ]);

        $count = $this->mediaService->moveToFolder($request->input('ids'), $request->input('folder_id'));

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
        $folderId = $request->get('folder_id'); // integer id
        $search   = $request->get('search', '') ?? '';
        
        $media    = $this->mediaService->getForPicker($folderId, $search);
        $folders  = \App\Models\MediaFolder::where('parent_id', $folderId)->orderBy('sort_order')->orderBy('name')->get();
        $roots    = \App\Models\MediaFolder::whereNull('parent_id')->orderBy('sort_order')->get();

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
            'folders' => $folders->map(fn($f) => [
                'id'    => $f->id,
                'name'  => $f->name,
                'icon'  => $f->icon,
                'color' => $f->color
            ]),
            'roots'   => $roots->map(fn($r) => [
                'id'   => $r->id,
                'name' => $r->name
            ])
        ]);
    }

    public function createFolder(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'parent_id' => 'nullable|integer|exists:media_folders,id'
        ]);

        $folder = \App\Models\MediaFolder::create([
            'name'      => trim($request->input('name')),
            'parent_id' => $request->input('parent_id'),
            'icon'      => 'fa-folder',
            'color'     => '#64748b'
        ]);

        return response()->json(['success' => true, 'folder' => $folder]);
    }

    public function renameFile(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required|string|max:100'
        ]);

        $media = Media::findOrFail($id);
        $media->update(['name' => trim($request->input('name'))]);

        return response()->json(['success' => true]);
    }

    public function renameFolder(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required|string|max:100'
        ]);

        $folder = \App\Models\MediaFolder::findOrFail($id);
        $folder->update(['name' => trim($request->input('name'))]);

        return response()->json(['success' => true]);
    }

    public function deleteFolder(int $id)
    {
        $folder = \App\Models\MediaFolder::findOrFail($id);
        // Recursive delete is handled by DB cascade (media are set to null)
        $folder->delete();

        return response()->json(['success' => true]);
    }

    public function getFolderParent(int $id)
    {
        $folder = \App\Models\MediaFolder::findOrFail($id);
        return response()->json(['parent_id' => $folder->parent_id]);
    }
}
