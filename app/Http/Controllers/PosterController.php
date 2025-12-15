<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PosterController extends Controller
{
    // Path dasar tempat semua poster disimpan (di dalam storage/app/public)
    protected $storagePath = 'public/posters';
    
    // Asumsi: Semua path poster disimpan dalam satu file JSON/database, 
    // atau kita langsung membaca isi folder. Kita asumsikan membaca folder.
    
    // Metode untuk menampilkan halaman Admin Panel
    public function index()
    {
        // Ambil semua file gambar di folder public/posters
        $files = Storage::files($this->storagePath);
        
        // Filter agar hanya menyertakan path yang valid (menghilangkan .gitignore dll)
        $posters = array_filter($files, function($file) {
            return preg_match('/\.(jpe?g|png)$/i', $file);
        });

        // Hapus prefix 'public/' dari path
        $posters = array_map(function($path) {
            return str_replace('public/', '', $path);
        }, $posters);

        return view('admin.posters.index', compact('posters'));
    }

    // Metode untuk mengupload dan menyimpan poster baru (Multiple Upload)
    public function store(Request $request)
    {
        $request->validate([
            'posters' => 'required|array',
            'posters.*' => 'image|mimes:jpeg,png,jpg|max:10240', // 10MB per file
        ]);

        foreach ($request->file('posters') as $poster) {
            $filename = time() . '_' . $poster->getClientOriginalName();
            $poster->storeAs('public/posters', $filename);
        }

        return redirect()->route('admin.posters.index')->with('success', 'Poster berhasil diupload!');
    }

    // Metode untuk menghapus poster
    public function destroy(Request $request)
    {
        $request->validate([
            'image_path' => 'required|string',
        ]);

        $fullPath = 'public/' . $request->image_path;

        if (Storage::exists($fullPath)) {
            Storage::delete($fullPath);
            return redirect()->route('admin.posters.index')->with('success', 'Poster berhasil dihapus!');
        }

        return redirect()->route('admin.posters.index')->with('error', 'File tidak ditemukan.');
    }

    // Metode untuk menampilkan Display Board
    public function display()
    {
        $files = Storage::files($this->storagePath);
        
        // Filter dan hapus prefix 'public/'
        $images = array_map(function($path) {
            return str_replace('public/', '', $path);
        }, array_filter($files, function($file) {
            return preg_match('/\.(jpe?g|png)$/i', $file);
        }));

        return view('display.index', compact('images'));
    }
}