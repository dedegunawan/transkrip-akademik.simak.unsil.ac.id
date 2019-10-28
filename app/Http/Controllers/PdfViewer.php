<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PdfViewer extends Controller
{
    public function index()
    {
        return view('pdf_viewer');
    }
}
