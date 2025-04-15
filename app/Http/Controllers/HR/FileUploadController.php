<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{

    public function create()
    {
        $files = UploadedFile::latest()->paginate(10); // Adjust number as needed
        return view('HR.upload_file', compact('files'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,doc,docx,xls,xlsx|max:20480', // 20MB max
        ]);

        $file = $request->file('file');

        $originalName = $file->getClientOriginalName();
        $fileName = time() . '_' . $originalName;
        $filePath = $file->storeAs('hr_uploads', $fileName, 'public');

        // Save file info to DB
        UploadedFile::create([
            'original_name' => $originalName,
            'file_path'     => $filePath,
            'file_type'     => $file->getClientMimeType(),
            'file_size'     => $file->getSize(),
        ]);

        return back()->with('success', 'File uploaded and saved to database!');
    }

    public function destroy($id)
    {
        $file = UploadedFile::findOrFail($id);

        // Delete the file from storage
        Storage::disk('public')->delete($file->file_path);

        // Delete record from the database
        $file->delete();

        return redirect()->route('hr.upload.form')->with('success', 'File deleted successfully!');
    }
}
