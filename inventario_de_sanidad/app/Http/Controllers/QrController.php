<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Storage;
use App\Constants\FlashType;
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

    public function downloadZip() {
        $storages = Storage::with('material')->get();

        $zip = new \ZipArchive;

        $zipName = 'qrcodes_' . time() . '_' . uniqid() . '.zip';
        $zipPath = storage_path('app/' . $zipName); 

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            $filesAdded = 0;

            foreach ($storages as $storage) {
                if (!$storage->qr_path) continue;

                $path = storage_path('app/qrcodes/' . basename($storage->qr_path));

                if (file_exists($path)) {
                    $zip->addFile($path, basename($path));
                    $filesAdded++;
                }
            }

            $zip->close();

            if ($filesAdded === 0) {
                if (file_exists($zipPath)) unlink($zipPath);
                return back()->withPush(FlashType::ERROR, 'No hay códigos QR disponibles para descargar');
            }

        } else {
            return back()->withPush(FlashType::ERROR, 'No se pudo crear el ZIP');
        }

        return response()->download($zipPath, 'qrcodes.zip')->deleteFileAfterSend(true);
    }


    public function print() {
        $storages = Storage::with('material')->get();

        return view('qrcodes.print', compact('storages'));
    }
}

