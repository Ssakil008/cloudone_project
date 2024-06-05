<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advertisement;

class AdvertisementController extends Controller
{
    public function store_advertises(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image_name' => 'nullable|string|max:255',
            'text_content' => 'nullable|string',
            'start_date' => 'required|date',
            'duration' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'details' => 'nullable|string',
            'created_by' => 'required|integer',
            'updated_by' => 'required|integer',
        ]);

        $advertisement = Advertisement::create($validatedData);

        return response()->json(['success' => true, 'data' => $advertisement]);
    }

    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png|max:1024',
        ]);

        $file = $request->file('file');
        $path = $file->store('advertises', 'public');

        return response()->json(['success' => true, 'file_path' => $path]);
    }
}

