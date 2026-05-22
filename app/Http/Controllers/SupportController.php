<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\supportImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SupportController extends Controller
{
    public function index()
    {
        return view('admin.import_Form');
    }

    public function store(Request $request)
    {
        // Validate file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        // Import Excel file
        Excel::import(new supportImport, $request->file('file'));

        return redirect()->back()->with('success', 'Excel file imported successfully!');
    }

}
