<?php
 
namespace App\Http\Controllers;
 
use App\Models\Obat;
use App\Models\Categories;
use Illuminate\Http\Request;
 
class ObatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $obats = Obat::with('category')->get();
        return view('obats.index', compact('obats'));
    }
 
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Categories::all();
        return view('obats.create', compact('categories'));
    }
 
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'barcode_sku' => 'required|string|max:255|unique:obats,barcode_sku',
            'nama_obat' => 'required|string|max:255',
            'nama_generik' => 'required|string|max:255',
            'categories_id' => 'required|exists:categories,id',
            'satuan_kemasan' => 'required|string|max:255',
            'harga_jual_umum' => 'required|integer|min:0',
            'harga_jual_medis' => 'required|integer|min:0',
            'batas_stok_minimum' => 'nullable|integer|min:0',
        ], [
            'barcode_sku.required' => 'Barcode/SKU wajib diisi.',
            'barcode_sku.unique' => 'Barcode/SKU sudah terdaftar.',
            'nama_obat.required' => 'Nama obat wajib diisi.',
            'nama_generik.required' => 'Nama generik wajib diisi.',
            'categories_id.required' => 'Kategori wajib dipilih.',
            'categories_id.exists' => 'Kategori tidak valid.',
            'satuan_kemasan.required' => 'Satuan kemasan wajib diisi.',
            'harga_jual_umum.required' => 'Harga jual umum wajib diisi.',
            'harga_jual_umum.integer' => 'Harga jual umum harus berupa angka.',
            'harga_jual_medis.required' => 'Harga jual medis wajib diisi.',
            'harga_jual_medis.integer' => 'Harga jual medis harus berupa angka.',
            'batas_stok_minimum.integer' => 'Batas stok minimum harus berupa angka.',
        ]);
 
        $store = Obat::create([
            'barcode_sku' => $request->barcode_sku,
            'nama_obat' => $request->nama_obat,
            'nama_generik' => $request->nama_generik,
            'categories_id' => $request->categories_id,
            'satuan_kemasan' => $request->satuan_kemasan,
            'harga_jual_umum' => $request->harga_jual_umum,
            'harga_jual_medis' => $request->harga_jual_medis,
            'batas_stok_minimum' => $request->batas_stok_minimum ?? 10,
        ]);
 
        if($store){
            return redirect()->route('obats.index')->with('success', 'Obat berhasil ditambahkan.');
        } else {
            return redirect()->route('obats.index')->with('error', 'Obat gagal ditambahkan.');
        }
    }
 
    /**
     * Display the specified resource.
     */
    public function show(Obat $obat)
    {
        $obat->load('category');
        return view('obats.show', compact('obat'));
    }
 
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Obat $obat)
    {
        $categories = Categories::all();
        return view('obats.edit', compact('obat', 'categories'));
    }
 
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Obat $obat)
    {
        $request->validate([
            'barcode_sku' => 'required|string|max:255|unique:obats,barcode_sku,' . $obat->id,
            'nama_obat' => 'required|string|max:255',
            'nama_generik' => 'required|string|max:255',
            'categories_id' => 'required|exists:categories,id',
            'satuan_kemasan' => 'required|string|max:255',
            'harga_jual_umum' => 'required|integer|min:0',
            'harga_jual_medis' => 'required|integer|min:0',
            'batas_stok_minimum' => 'nullable|integer|min:0',
        ], [
            'barcode_sku.required' => 'Barcode/SKU wajib diisi.',
            'barcode_sku.unique' => 'Barcode/SKU sudah terdaftar.',
            'nama_obat.required' => 'Nama obat wajib diisi.',
            'nama_generik.required' => 'Nama generik wajib diisi.',
            'categories_id.required' => 'Kategori wajib dipilih.',
            'categories_id.exists' => 'Kategori tidak valid.',
            'satuan_kemasan.required' => 'Satuan kemasan wajib diisi.',
            'harga_jual_umum.required' => 'Harga jual umum wajib diisi.',
            'harga_jual_umum.integer' => 'Harga jual umum harus berupa angka.',
            'harga_jual_medis.required' => 'Harga jual medis wajib diisi.',
            'harga_jual_medis.integer' => 'Harga jual medis harus berupa angka.',
            'batas_stok_minimum.integer' => 'Batas stok minimum harus berupa angka.',
        ]);
 
        $update = $obat->update([
            'barcode_sku' => $request->barcode_sku,
            'nama_obat' => $request->nama_obat,
            'nama_generik' => $request->nama_generik,
            'categories_id' => $request->categories_id,
            'satuan_kemasan' => $request->satuan_kemasan,
            'harga_jual_umum' => $request->harga_jual_umum,
            'harga_jual_medis' => $request->harga_jual_medis,
            'batas_stok_minimum' => $request->batas_stok_minimum ?? 10,
        ]);
 
        if($update){
            return redirect()->route('obats.index')->with('success', 'Obat berhasil diupdate.');
        } else {
            return redirect()->route('obats.index')->with('error', 'Obat gagal diupdate.');
        }
    }
 
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Obat $obat)
    {
        $delete = $obat->delete();
 
        if($delete){
            return redirect()->route('obats.index')->with('success', 'Obat berhasil dihapus.');
        } else {
            return redirect()->route('obats.index')->with('error', 'Obat gagal dihapus.');
        }
    }
}
