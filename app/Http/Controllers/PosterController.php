<?php

namespace App\Http\Controllers;

use App\Models\Poster;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class PosterController extends Controller
{
    // --- Bagian Admin (CRUD) ---

    public function index()
    {
        // Ambil record terakhir (atau buat jika belum ada)
        $poster = Poster::latest()->first();

        if (!$poster) {
            $poster = Poster::create(['images' => []]);
        }

        return view('admin.index', compact('poster'));
    }

    public function store(Request $request)
    {
        // Validasi: array wajib diisi, file harus JPG/JPEG/PNG, maks 10MB
        $request->validate([
            'posters' => 'required|array',
            'posters.*' => 'mimes:jpg,jpeg,png|max:10240', 
        ]);

        $poster = Poster::latest()->first();
        if (!$poster) {
             $poster = Poster::create(['images' => []]);
        }
        
        $currentImages = $poster->images ?? [];
        $uploadedPaths = [];

        foreach ($request->file('posters') as $file) {
            // Simpan ke storage/app/public/posters
            $path = $file->store('posters', 'public');
            $uploadedPaths[] = $path;
        }

        // Gabungkan dan update
        $newImages = array_merge($currentImages, $uploadedPaths);

        // array_values() untuk memastikan array di-reindex setelah merge/upload baru
        $poster->update(['images' => array_values($newImages)]); 

        return redirect()->route('admin.posters.index')
                         ->with('success', 'Poster berhasil diupload.');
    }

    public function destroy(Request $request)
    {
        $request->validate(['image_path' => 'required|string']);

        $poster = Poster::latest()->first();
        
        if (!$poster) {
            return back()->with('error', 'Data poster tidak ditemukan.');
        }

        $imagePath = $request->input('image_path');
        $currentImages = $poster->images ?? [];

        // Hapus path dari array dan file dari storage
        if (($key = array_search($imagePath, $currentImages)) !== false) {
            unset($currentImages[$key]);
            
            Storage::disk('public')->delete($imagePath);

            // Update database
            $poster->update(['images' => array_values($currentImages)]); 

            return back()->with('success', 'Poster berhasil dihapus.');
        }

        return back()->with('error', 'File poster tidak ditemukan dalam database.');
    }
    
    // --- Bagian Display Papan Informasi ---

    public function display()
    {
        $poster = Poster::latest()->first();
        $images = $poster ? $poster->images : [];
        
        $images = array_filter($images);

        // Jika tidak ada gambar, tampilkan halaman kosong
        if (empty($images)) {
            return view('display.empty');
        }

        return view('display.index', compact('images'));
    }
}