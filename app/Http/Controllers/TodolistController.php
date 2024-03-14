<?php

namespace App\Http\Controllers;

use App\Models\Todolist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TodolistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      // raw sql berhasil menurunkan respon time 10k data dari 1250ms => 720ms
      $data = DB::select("SELECT * FROM todolists ORDER BY created_at DESC");
      return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
          return view('layouts.tombol')->with('data', $row);
        })
        ->make(true);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
      $data = request()->validate([
        'name' => 'required',
        'todo' => 'required'
      ]);
      Todolist::create($data);
      return response()->json(['success' => "Berhasil menyimpan data"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Todolist $todolist)
    {
      
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
      $data = Todolist::where('id', $id)->first();
        return response()->json(['result' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
      $data = [
        'name' => $request->name,
        'todo' => $request->todo
      ];
      Todolist::where('id', $id)->update($data);
      return response()->json(['success' => "berhasil update"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
      Todolist::where('id', $id)->delete();
    }
}
