<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UploadsController extends Controller
{
    public function image(Request $request)
    {
        $request->validate([
            'file' => ['required', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:4096'],
        ]);

        $file = $request->file('file');

        $path = $file->storeAs(
            'uploads/images/' . now()->format('Y/m'),
            Str::uuid()->toString() . '.' . $file->extension(),
            'public',
        );

        return response()->json([
            'location' => asset('storage/' . $path),
        ]);
    }

    public function file(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt', 'max:10240'],
        ]);

        $file = $request->file('file');

        $path = $file->storeAs(
            'uploads/files/' . now()->format('Y/m'),
            Str::uuid()->toString() . '.' . $file->extension(),
            'public',
        );

        return response()->json([
            'location' => asset('storage/' . $path),
        ]);
    }
}

