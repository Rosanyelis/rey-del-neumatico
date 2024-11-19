<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Supplier::select('suppliers.*');
            return DataTables::of($data)
                ->addColumn('actions', function ($data) {
                    return view('suppliers.partials.actions', ['id' => $data->id]);
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('suppliers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
        Supplier::create($request->all());
        return redirect()->route('proveedor.index')->with('success', 'Proveedor creado con exito');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($supplier)
    {
        $supplier = Supplier::find($supplier);
        return view('suppliers.edit', ['supplier' => $supplier]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, $supplier)
    {
        $supplier = Supplier::find($supplier);
        $supplier->update($request->all());
        return redirect()->route('proveedor.index')->with('success', 'Proveedor actualizado con exito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($supplier)
    {
        $supplier = Supplier::find($supplier);
        $supplier->delete();
        return redirect()->route('proveedor.index')->with('success', 'Proveedor eliminado con exito');
    }
}
