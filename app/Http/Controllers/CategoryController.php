<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\DataTables;
use App\Imports\CategoriesImport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::all();
            return DataTables::of($data)
                ->addColumn('actions', function ($data) {
                    return view('categories.partials.actions', ['id' => $data->id]);
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {

        $data = $request->all();
        if ($request->hasFile('image')) {
            $uploadPath = public_path('/storage/categorias/');
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $uuid = Str::uuid(4);
            $fileName = $uuid . '.' . $extension;
            $file->move($uploadPath, $fileName);
            $url = '/storage/categorias/'.$fileName;
            $foto = $url;
            $data['image'] = $url;
        }

        Category::create($data);
        return redirect()->route('categorias.index')->with('success', 'Categoría creada con exito');

    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($category)
    {
        $category = Category::find($category);
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, $category)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            $uploadPath = public_path('/storage/categorias/');
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $uuid = Str::uuid(4);
            $fileName = $uuid . '.' . $extension;
            $file->move($uploadPath, $fileName);
            $url = '/storage/categorias/'.$fileName;
            $foto = $url;
            $data['image'] = $url;
        }

        $category = Category::find($category);
        $category->update($data);
        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada con exito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($category)
    {
        $data = Category::find($category);
        $data->delete();
        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada con exito');
    }

    /**
     * Renders the view for importing categories.
     *
     */
    public function view_import()
    {
        return view('categories.import');
    }

    public function import(Request $request)
    {
        Excel::import(new CategoriesImport, $request->file('file'));
        return redirect()->route('categorias.index')->with('success', 'Categorías importadas con exito');
    }

    public function productcategory($category)
    {
        $products = Product::where('category_id', $category)->get();
        return Pdf::loadView('pdfs.porcategoria', compact('products'))
                ->stream(''.config('app.name', 'Laravel').' - Listado de Productos.pdf');
    }
}
