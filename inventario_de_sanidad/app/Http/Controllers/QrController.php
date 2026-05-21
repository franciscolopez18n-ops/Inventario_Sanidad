<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Storage;

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
}