<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
    {
        $query = Categories::query();
            if ($request->filled('search')) {
                $query->where('name_category', 'like', '%' . $request->search . '%');
            }
        $categories = $query->get();
        return view('categories.index', compact('categories'));

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
    public function store(Request $request)
    {
        $request->validate([
            'name_category' => 'required|string|max:100'
        ],[
            'name_category.required' => 'Nama kategori wajib diisi.',
            'name_category.string' => 'Nama kategori harus berupa string.',
            'name_category.max' => 'Nama kategori tidak boleh lebih dari 100 karakter.'
         ]);
             
         $store = Categories::create([
            'name_category' => $request->name_category
         ]);

         if($store){
            return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
         } else {
            return redirect()->route('categories.index')->with('error', 'Kategori gagal ditambahkan.');
         }
    }

    /**
     * Display the specified resource.
     */
    public function show(Categories $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categories $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categories $category)
    {
        $request->validate([
            'name_category' => 'required|string|max:100'
        ],[
            'name_category.required' => 'Nama kategori wajib diisi.',
            'name_category.string' => 'Nama kategori harus berupa string.',
            'name_category.max' => 'Nama kategori tidak boleh lebih dari 100 karakter.'
         ]);
             
         $update = $category->update([
            'name_category' => $request->name_category
         ]);

         if($update){
            return redirect()->route('categories.index')->with('success', 'Kategori berhasil diupdate.');
         } else {
            return redirect()->route('categories.index')->with('error', 'Kategori gagal diupdate.');
         }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categories $category)
    {
        $delete = $category->delete();

        if($delete){
            return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
         } else {
            return redirect()->route('categories.index')->with('error', 'Kategori gagal dihapus.');
         }
    }
}
