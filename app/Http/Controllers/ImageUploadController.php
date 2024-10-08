<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    public function uploadImage(Request $request)
    {
        // Validasi jika file ada dalam request
        if (!$request->hasFile('image')) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        // Dapatkan file yang diupload
        $file = $request->file('image');

        // Baca konten file
        $imageContent = file_get_contents($file->getPathname());

        // Siapkan form-data untuk diupload ke imgbb
        $response = Http::asMultipart()->post('https://api.imgbb.com/1/upload', [
            'key' => env('IMGBB_API_KEY'), // Pastikan API Key disertakan di .env
            'image' => base64_encode($imageContent), // Konversi gambar ke base64
            'name' => $file->getClientOriginalName(),
        ]);

        $data = json_encode($response->json());

        $media = new Media();
        $media->data = $data;
        $media->save();

        // Cek apakah request berhasil
        if ($response->successful()) {
            return response()->json([
                'message' => 'Image uploaded successfully',
                'data' => $media,
            ]);
        }

        // Jika gagal, kembalikan error
        return response()->json(['error' => 'Failed to upload image'], 500);
    }
}
