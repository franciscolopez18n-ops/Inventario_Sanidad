<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Storage;
use ZipArchive;

class QrController extends Controller {
    public function index() {
        $storages = Storage::with('material')->get();
        return view('qrcodes.index', compact('storages'));
    }

    public function show($file) {
        $path = storage_path('app/qrcodes/' . $file);
        
        if (!file_exists($path)) {
            abort(404);
        }
        
        return response()->file($path, ['Content-Type' => 'image/svg+xml']);
    }

    public function descargarZip()
    {
        $storages = Storage::with('material')->get();

        $zip = new \ZipArchive;
        $nombre = 'qrcodes.zip';

        $zipPath = storage_path($nombre);

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {

            foreach ($storages as $storage) {
                if (!$storage->qr_path) continue;

                $path = storage_path('app/qrcodes/' . basename($storage->qr_path));

                if (file_exists($path)) {
                    $zip->addFile($path, basename($path));
                }
            }

            $zip->close();
        } else {
            abort(500, 'No se pudo crear el ZIP');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function print()
    {
        $storages = Storage::with('material')->get();
        return view('qrcodes.print', compact('storages'));
    }
}

