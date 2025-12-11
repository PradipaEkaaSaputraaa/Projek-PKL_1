@extends('layouts.admin')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md mb-8">
    <h2 class="text-2xl font-semibold mb-4 text-gray-700">Upload Poster Baru</h2>
    
    <form action="{{ route('admin.posters.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label for="posters" class="block text-sm font-medium text-gray-700 mb-2">Pilih Gambar Poster (JPG, JPEG, PNG, Multiple Upload)</label>
            <input type="file" name="posters[]" id="posters" multiple 
                   class="block w-full text-sm text-gray-500
                   file:mr-4 file:py-2 file:px-4
                   file:rounded-full file:border-0
                   file:text-sm file:font-semibold
                   file:bg-indigo-50 file:text-indigo-700
                   hover:file:bg-indigo-100"
                   required>
            @error('posters')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            @error('posters.*')
                <p class="text-red-500 text-xs mt-1">Pastikan semua file berformat JPG, JPEG, atau PNG dan ukuran tidak lebih dari 10MB.</p>
            @enderror
        </div>
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-200">
            Upload Poster
        </button>
    </form>
</div>

<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-4 text-gray-700">Daftar Poster (Total: {{ count($poster->images ?? []) }})</h2>
    
    @if (!empty($poster->images))
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach ($poster->images as $path)
                <div class="relative group border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                    <div class="w-full h-0 pb-[177.77%] relative"> 
                        <img src="{{ Storage::url($path) }}" alt="Poster" 
                             class="absolute top-0 left-0 w-full h-full object-cover">
                    </div>
                    <form action="{{ route('admin.posters.destroy') }}" method="POST" 
                          class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition duration-300">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="image_path" value="{{ $path }}">
                        <button type="submit" onclick="return confirm('Anda yakin ingin menghapus poster ini?')"
                                class="bg-red-600 hover:bg-red-700 text-white p-1.5 rounded-full shadow-lg transition duration-200"
                                title="Hapus Poster">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500">Belum ada poster yang diupload.</p>
    @endif
</div>
@endsection