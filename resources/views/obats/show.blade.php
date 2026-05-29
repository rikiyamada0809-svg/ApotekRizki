<div class="table-responsive">
    <table class="table table-striped table-bordered detail-table">
        <tbody>
            <tr>
                <th style="width: 35%;" class="fw-bold text-heading">Barcode / SKU</th>
                <td><span class="badge bg-label-info">{{ $obat->barcode_sku }}</span></td>
            </tr>
            <tr>
                <th class="fw-bold text-heading">Nama Obat</th>
                <td><span class="fw-semibold text-dark">{{ $obat->nama_obat }}</span></td>
            </tr>
            <tr>
                <th class="fw-bold text-heading">Nama Generik</th>
                <td>{{ $obat->nama_generik }}</td>
            </tr>
            <tr>
                <th class="fw-bold text-heading">Kategori</th>
                <td>
                    @if ($obat->category)
                        <span class="badge bg-label-primary">{{ $obat->category->name_category }}</span>
                    @else
                        <span class="badge bg-label-secondary">-</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th class="fw-bold text-heading">Satuan Kemasan</th>
                <td><span class="badge bg-label-success">{{ $obat->satuan_kemasan }}</span></td>
            </tr>
            <tr>
                <th class="fw-bold text-heading">Harga Jual Umum</th>
                <td class="text-success fw-semibold">Rp {{ number_format($obat->harga_jual_umum, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th class="fw-bold text-heading">Harga Jual Medis</th>
                <td class="text-primary fw-semibold">Rp {{ number_format($obat->harga_jual_medis, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th class="fw-bold text-heading">Batas Stok Minimum</th>
                <td><span class="badge bg-label-warning">{{ $obat->batas_stok_minimum }}</span></td>
            </tr>
            <tr>
                <th class="fw-bold text-heading">Tanggal Dibuat</th>
                <td>{{ $obat->created_at ? $obat->created_at->format('d M Y, H:i') : '-' }}</td>
            </tr>
            <tr>
                <th class="fw-bold text-heading">Pembaruan Terakhir</th>
                <td>{{ $obat->updated_at ? $obat->updated_at->format('d M Y, H:i') : '-' }}</td>
            </tr>
        </tbody>
    </table>
</div>
<div class="text-end pt-3">
    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tutup</button>
</div>
