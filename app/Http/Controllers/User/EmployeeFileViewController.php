<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UploadedFile;
use Illuminate\Http\Request;

class EmployeeFileViewController extends Controller
{
    public function index()
    {
        $files = UploadedFile::latest()->get();
        return view('User.view_files', compact('files'));
    }
}
