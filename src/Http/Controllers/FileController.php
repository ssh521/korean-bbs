<?php

namespace Ssh521\KoreanBbs\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Ssh521\KoreanBbs\Models\BbsFile;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    public function download(BbsFile $file): StreamedResponse
    {
        $file->loadMissing('post.board');

        Gate::authorize('korean-bbs.download-file', $file);

        $disk = config('korean-bbs.upload.disk');

        abort_unless(Storage::disk($disk)->exists($file->path), 404);

        $file->increment('download_count');

        return Storage::disk($disk)->download($file->path, $file->original_name);
    }
}
