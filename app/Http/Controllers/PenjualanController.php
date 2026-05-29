<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class PenjualanController extends Controller
{
    /**
     * Tampilkan halaman utama Kasir.
     */
    public function index()
    {
        // Tampilkan 12 obat pertama secara default
        $obats = Obat::with('category')->take(12)->get();
        return view('kasir.index', compact('obats'));
    }

    /**
     * Endpoint pencarian produk obat secara realtime (AJAX).
     */
    public function search(Request $request)
    {
        $query = Obat::with('category');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($w) use ($q) {
                $w->where('nama_obat', 'like', "%{$q}%")
                  ->orWhere('nama_generik', 'like', "%{$q}%")
                  ->orWhere('barcode_sku', 'like', "%{$q}%")
                  ->orWhereHas('category', function($cat) use ($q) {
                      $cat->where('name_category', 'like', "%{$q}%");
                  });
            });
        }

        $results = $query->get();
        return response()->json($results);
    }

    /**
     * Proses penyimpanan transaksi penjualan (AJAX POST).
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipe_pelanggan' => 'required|in:umum,medis',
            'nama_instansi' => 'required_if:tipe_pelanggan,medis|nullable|string|max:255',
            'total_harga' => 'required|integer|min:0',
            'bayar' => 'required|integer|min:0',
            'kembali' => 'required|integer|min:0',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:obats,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|integer|min:0',
            'items.*.subtotal' => 'required|integer|min:0',
        ]);

        try {
            $response = DB::transaction(function () use ($request) {
                // Generate Nomor Invoice Unik (INV-YYYYMMDD-XXXX)
                $today = date('Ymd');
                $count = Penjualan::whereDate('created_at', today())->count() + 1;
                $no_invoice = 'INV-' . $today . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

                // Buat Master Penjualan
                $penjualan = Penjualan::create([
                    'no_invoice' => $no_invoice,
                    'tipe_pelanggan' => $request->tipe_pelanggan,
                    'nama_instansi' => $request->tipe_pelanggan === 'medis' ? $request->nama_instansi : null,
                    'total_harga' => $request->total_harga,
                    'bayar' => $request->bayar,
                    'kembali' => $request->kembali,
                ]);

                // Detail Penjualan & Pengurangan Stok
                foreach ($request->items as $item) {
                    $obat = Obat::findOrFail($item['id']);

                    // Periksa ketersediaan stok
                    if ($obat->stok < $item['qty']) {
                        throw new Exception("Stok obat '{$obat->nama_obat}' tidak mencukupi. Sisa stok: {$obat->stok}.");
                    }

                    // Kurangi stok obat
                    $obat->decrement('stok', $item['qty']);

                    // Buat Detail
                    DetailPenjualan::create([
                        'penjualan_id' => $penjualan->id,
                        'obat_id' => $obat->id,
                        'qty' => $item['qty'],
                        'harga_satuan' => $item['harga'],
                        'subtotal' => $item['subtotal'],
                    ]);
                }

                return [
                    'no_invoice' => $no_invoice,
                    'total_harga' => $penjualan->total_harga,
                    'bayar' => $penjualan->bayar,
                    'kembali' => $penjualan->kembali,
                    'tipe_pelanggan' => $penjualan->tipe_pelanggan,
                    'nama_instansi' => $penjualan->nama_instansi,
                    'tanggal' => $penjualan->created_at->format('d M Y, H:i'),
                ];
            });

            // Ambil rincian item dengan detail nama obat untuk struk pembayaran
            $invoiceDetails = Penjualan::with('details.obat')->where('no_invoice', $response['no_invoice'])->first();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan.',
                'invoice' => $response,
                'details' => $invoiceDetails->details
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
