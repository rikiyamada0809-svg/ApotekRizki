<form action="{{ route('obats.update', $obat->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <!-- Barcode / SKU -->
        <div class="col-md-6 mb-3">
            <label class="form-label" for="barcode_sku">Barcode / SKU <span class="text-danger">*</span></label>
            <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="ti ti-barcode"></i></span>
                <input type="text" id="barcode_sku" name="barcode_sku" class="form-control" value="{{ old('barcode_sku', $obat->barcode_sku) }}" placeholder="Contoh: 899991234567" required />
            </div>
        </div>

        <!-- Nama Obat -->
        <div class="col-md-6 mb-3">
            <label class="form-label" for="nama_obat">Nama Obat <span class="text-danger">*</span></label>
            <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="ti ti-pill"></i></span>
                <input type="text" id="nama_obat" name="nama_obat" class="form-control" value="{{ old('nama_obat', $obat->nama_obat) }}" placeholder="Contoh: Paracetamol 500mg" required />
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Nama Generik -->
        <div class="col-md-6 mb-3">
            <label class="form-label" for="nama_generik">Nama Generik <span class="text-danger">*</span></label>
            <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="ti ti-activity"></i></span>
                <input type="text" id="nama_generik" name="nama_generik" class="form-control" value="{{ old('nama_generik', $obat->nama_generik) }}" placeholder="Contoh: Paracetamol" required />
            </div>
        </div>

        <!-- Kategori -->
        <div class="col-md-6 mb-3">
            <label class="form-label" for="categories_id">Kategori <span class="text-danger">*</span></label>
            <select id="categories_id" name="categories_id" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('categories_id', $obat->categories_id) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name_category }}
                    </option>
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
                @php
                    $units = ['Tablet', 'Kapsul', 'Kaplet', 'Strip', 'Botol', 'Tube', 'Box', 'Pcs', 'Sachet', 'Ampul', 'Vial'];
                    $currentUnit = old('satuan_kemasan', $obat->satuan_kemasan);
                @endphp
                @foreach ($units as $unit)
                    <option value="{{ $unit }}" {{ $currentUnit == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                @endforeach
                @if ($currentUnit && !in_array($currentUnit, $units))
                    <option value="{{ $currentUnit }}" selected>{{ $currentUnit }}</option>
                @endif
            </select>
        </div>

        <!-- Batas Stok Minimum -->
        <div class="col-md-6 mb-3">
            <label class="form-label" for="batas_stok_minimum">Batas Stok Minimum <span class="text-danger">*</span></label>
            <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="ti ti-alert-triangle"></i></span>
                <input type="number" id="batas_stok_minimum" name="batas_stok_minimum" class="form-control" value="{{ old('batas_stok_minimum', $obat->batas_stok_minimum) }}" min="0" required />
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Harga Jual Umum -->
        <div class="col-md-6 mb-3">
            <label class="form-label" for="harga_jual_umum">Harga Jual Umum <span class="text-danger">*</span></label>
            <div class="input-group input-group-merge">
                <span class="input-group-text">Rp</span>
                <input type="number" id="harga_jual_umum" name="harga_jual_umum" class="form-control" value="{{ old('harga_jual_umum', $obat->harga_jual_umum) }}" placeholder="0" min="0" required />
            </div>
        </div>

        <!-- Harga Jual Medis -->
        <div class="col-md-6 mb-3">
            <label class="form-label" for="harga_jual_medis">Harga Jual Medis <span class="text-danger">*</span></label>
            <div class="input-group input-group-merge">
                <span class="input-group-text">Rp</span>
                <input type="number" id="harga_jual_medis" name="harga_jual_medis" class="form-control" value="{{ old('harga_jual_medis', $obat->harga_jual_medis) }}" placeholder="0" min="0" required />
            </div>
        </div>
    </div>

    <div class="text-end pt-3">
        <button type="button" class="btn btn-label-secondary me-2" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </div>
</form>
