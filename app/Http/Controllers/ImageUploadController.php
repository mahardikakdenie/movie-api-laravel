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
        // Validate when file in the requiest
        if (!$request->hasFile('image')) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        // get file uploaded
        $file = $request->file('image');

        // read file content
        $imageContent = file_get_contents($file->getPathname());

        // Prepare form-data for upload to imgbb
        $response = Http::asMultipart()->post('https://api.imgbb.com/1/upload', [
            'key' => env('IMGBB_API_KEY'), // Pastikan API Key disertakan di .env
            'image' => base64_encode($imageContent), // Konversi gambar ke base64
            'name' => $file->getClientOriginalName(),
        ]);

        $data = json_encode($response->json());

        $media = new Media();
        $media->data = $data;
        $media->save();

        // Check the request is success
        if ($response->successful()) {
            return response()->json([
                'message' => 'Image uploaded successfully',
                'data' => $media,
            ]);
        }

        // when it fails, return a error message
        return response()->json(['error' => 'Failed to upload image'], 500);
    }
}
