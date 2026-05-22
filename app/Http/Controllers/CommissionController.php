<?php
namespace App\Http\Controllers;

use App\Models\commission;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function index()
    {
        $getCommissions = commission::all();
        return view('admin.hr.commission.manage_commission', compact('getCommissions'));
    }

    public function create()
    {
        return view('admin.hr.commission.add_commission');
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_price' => 'required|numeric|min:0',
            'commission' => 'required|numeric|min:0|max:100',
        ]);

        commission::create([
            'sale_price'            => $request->input('sale_price'),
            'commission_parcentage' => $request->input('commission'),
        ]);

        return redirect()->route('commission.index')->with('success', 'Commission record added successfully.');
    }

    public function edit($id)
    {
        $commission = commission::findOrFail($id);
        return view('admin.hr.commission.edit_commission', compact('commission'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sale_price' => 'required|numeric|min:0',
            'commission' => 'required|numeric|min:0|max:100',
        ]);

        $commission = commission::findOrFail($id);
        $commission->update([
            'sale_price'            => $request->input('sale_price'),
            'commission_parcentage' => $request->input('commission'),
        ]);

        return redirect()->route('commission.index')->with('success', 'Commission record updated successfully.');
    }

    public function destroy($id)
    {
        $commission = commission::findOrFail($id);
        $commission->delete();
        return redirect()->route('commission.index')->with('success', 'Commission record deleted successfully.');
    }

}
