<form action="{{ route('obats.store') }}" method="POST">
    @csrf
    <div class="row">
        <!-- Barcode / SKU -->
        <div class="col-md-6 mb-3">
            <label class="form-label" for="barcode_sku">Barcode / SKU <span class="text-danger">*</span></label>
            <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="ti ti-barcode"></i></span>
                <input type="text" id="barcode_sku" name="barcode_sku" class="form-control" placeholder="Contoh: 899991234567" required />
            </div>
        </div>

        <!-- Nama Obat -->
        <div class="col-md-6 mb-3">
            <label class="form-label" for="nama_obat">Nama Obat <span class="text-danger">*</span></label>
            <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="ti ti-pill"></i></span>
                <input type="text" id="nama_obat" name="nama_obat" class="form-control" placeholder="Contoh: Paracetamol 500mg" required />
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Nama Generik -->
        <div class="col-md-6 mb-3">
            <label class="form-label" for="nama_generik">Nama Generik <span class="text-danger">*</span></label>
            <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="ti ti-activity"></i></span>
                <input type="text" id="nama_generik" name="nama_generik" class="form-control" placeholder="Contoh: Paracetamol" required />
            </div>
        </div>

        <!-- Kategori -->
        <div class="col-md-6 mb-3">
            <label class="form-label" for="categories_id">Kategori <span class="text-danger">*</span></label>
            <select id="categories_id" name="categories_id" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name_category }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <!-- Satuan Kemasan -->
        <div class="col-md-6 mb-3">
            <label class="form-label" for="satuan_kemasan">Satuan Kemasan <span class="text-danger">*</span></label>
            <select id="satuan_kemasan" name="satuan_kemasan" class="form-select" required>
                <option value="">-- Pilih Satuan Kemasan --</option>
                <option value="Tablet">Tablet</option>
                <option value="Kapsul">Kapsul</option>
                <option value="Kaplet">Kaplet</option>
                <option value="Strip">Strip</option>
                <option value="Botol">Botol</option>
                <option value="Tube">Tube</option>
                <option value="Box">Box</option>
                <option value="Pcs">Pcs</option>
                <option value="Sachet">Sachet</option>
                <option value="Ampul">Ampul</option>
                <option value="Vial">Vial</option>
            </select>
        </div>

        <!-- Batas Stok Minimum -->
        <div class="col-md-6 mb-3">
            <label class="form-label" for="batas_stok_minimum">Batas Stok Minimum <span class="text-danger">*</span></label>
            <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="ti ti-alert-triangle"></i></span>
                <input type="number" id="batas_stok_minimum" name="batas_stok_minimum" class="form-control" value="10" min="0" required />
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Harga Jual Umum -->
        <div class="col-md-6 mb-3">
            <label class="form-label" for="harga_jual_umum">Harga Jual Umum <span class="text-danger">*</span></label>
            <div class="input-group input-group-merge">
                <span class="input-group-text">Rp</span>
                <input type="number" id="harga_jual_umum" name="harga_jual_umum" class="form-control" placeholder="0" min="0" required />
            </div>
        </div>

        <!-- Harga Jual Medis -->
        <div class="col-md-6 mb-3">
            <label class="form-label" for="harga_jual_medis">Harga Jual Medis <span class="text-danger">*</span></label>
            <div class="input-group input-group-merge">
                <span class="input-group-text">Rp</span>
                <input type="number" id="harga_jual_medis" name="harga_jual_medis" class="form-control" placeholder="0" min="0" required />
            </div>
        </div>
    </div>

    <div class="text-end pt-3">
        <button type="button" class="btn btn-label-secondary me-2" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Data</button>
    </div>
</form>
