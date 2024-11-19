<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Requests\StoreStoreRequest;
use App\Http\Requests\UpdateStoreRequest;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Store::select('stores.*');
            return DataTables::of($data)
                ->addColumn('actions', function ($data) {
                    return view('stores.partials.actions', ['id' => $data->id]);
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('stores.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('stores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStoreRequest $request)
    {
        $data = $request->all();

        if ($request->hasFile('logo')) {
            $uploadPath = public_path('/storage/logo-tiendas/');
            $file = $request->file('logo');
            $extension = $file->getClientOriginalExtension();
            $uuid = Str::uuid(4);
            $fileName = $uuid . '.' . $extension;
            $file->move($uploadPath, $fileName);
            $url = '/storage/logo-tiendas/'.$fileName;
            $foto = $url;
            $data['logo'] = $url;
        }

        Store::create($data);
        return redirect()->route('tiendas.index')->with('success', 'Tienda creada con exito');
    }

    /**
     * Display the specified resource.
     */
    public function show(Store $store)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($store)
    {
        $store = Store::find($store);
        return view('stores.edit', ['store' => $store]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStoreRequest $request, $store)
    {
        $data = $request->all();

        if ($request->hasFile('logo')) {
            $uploadPath = public_path('/storage/logo-tiendas/');
            $file = $request->file('logo');
            $extension = $file->getClientOriginalExtension();
            $uuid = Str::uuid(4);
            $fileName = $uuid . '.' . $extension;
            $file->move($uploadPath, $fileName);
            $url = '/storage/logo-tiendas/'.$fileName;
            $foto = $url;
            $data['logo'] = $url;
        }

        $store = Store::find($store);
        $store->update($data);
        return redirect()->back()->with('success', 'Empresa actualizada con exito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($store)
    {
        $store = Store::find($store);
        $store->delete();
        return redirect()->route('tiendas.index')->with('success', 'Tienda eliminada con exito');
    }
}
