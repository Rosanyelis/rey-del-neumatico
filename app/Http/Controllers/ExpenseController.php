<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('expenses')
                    ->join('stores', 'expenses.store_id', '=', 'stores.id')
                    ->join('users', 'expenses.user_id', '=', 'users.id')
                    ->select('expenses.*', 'stores.name as store_name', 'users.name as user_name')
                    ->get();
            return DataTables::of($data)
                ->addColumn('actions', function ($data) {
                    return view('expenses.partials.actions', ['id' => $data->id]);
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('expenses.index');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $stores = DB::table('stores')->get();
        return view('expenses.create', compact('stores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseRequest $request)
    {
        $data = $request->all();

        if ($request->hasFile('file')) {
            $uploadPath = public_path('/storage/gastos/');
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $uuid = Str::uuid(4);
            $fileName = $uuid . '.' . $extension;
            $file->move($uploadPath, $fileName);
            $url = '/storage/gastos/'.$fileName;
            $foto = $url;
            $data['file'] = $url;
        }
        $data['store_id'] = 1;
        $data['user_id'] = auth()->user()->id;
        $expense = Expense::create($data);
        return redirect()->route('gastos.index')->with('success', 'Gasto creado satisfactoriamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($expense)
    {
        $data = DB::table('expenses')
                    ->join('stores', 'expenses.store_id', '=', 'stores.id')
                    ->join('users', 'expenses.user_id', '=', 'users.id')
                    ->select('expenses.*', 'stores.name as store_name', 'users.name as user_name')
                    ->where('expenses.id', $expense)
                    ->first();
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($expense)
    {
        $stores = DB::table('stores')->get();
        $expense = Expense::find($expense);
        return view('expenses.edit', compact('stores', 'expense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request, $expense)
    {
        $exp = Expense::find($expense);
        $data = $request->all();
        if ($request->hasFile('file')) {
            $uploadPath = public_path('/storage/gastos/');
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $uuid = Str::uuid(4);
            $fileName = $uuid . '.' . $extension;
            $file->move($uploadPath, $fileName);
            $url = '/storage/gastos/'.$fileName;
            $data['file'] = $url;
        }
        $data['store_id'] = 1;
        $data['user_id'] = auth()->user()->id;
        $exp->update($data);
        return redirect()->route('gastos.index')->with('success', 'Gasto actualizado satisfactoriamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($expense)
    {
        $expense = Expense::find($expense);
        $expense->delete();
        return redirect()->route('gastos.index')->with('success', 'Gasto eliminado satisfactoriamente');

    }
}
