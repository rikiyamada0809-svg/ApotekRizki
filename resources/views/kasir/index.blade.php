@extends('layouts.app')
@section('title', 'Kasir Penjualan')

@push('style')
    <!-- CSS Tambahan untuk Kasir Premium -->
    <style>
        .price-mode-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .price-mode-card.active {
            background-color: #0f766e !important; /* teal-700 */
            color: #ffffff !important;
            border-color: #0f766e;
            box-shadow: 0 4px 12px rgba(15, 118, 110, 0.3);
        }
        .price-mode-card.active i, .price-mode-card.active h6, .price-mode-card.active span {
            color: #ffffff !important;
        }
        .obat-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .obat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1) !important;
        }
        .cart-container {
            max-height: 380px;
            overflow-y: auto;
        }
        .btn-quick-cash {
            font-size: 0.8rem;
            padding: 0.3rem 0.5rem;
        }
        #reader {
            border: none !important;
            border-radius: 8px;
            overflow: hidden;
        }
        #reader__scan_region {
            background: #223;
        }
    </style>
@endpush

@section('content')
<div class="row">
    <!-- KOLOM KIRI: Barcode & Pencarian Produk -->
    <div class="col-lg-8 col-md-12 mb-4">
        <!-- Card 1: Pemindai Barcode -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title fw-bold mb-3 d-flex align-items-center">
                    <i class="ti ti-barcode text-teal me-2 fs-3"></i> Pemindai Barcode
                </h5>
                <form id="barcodeForm" onsubmit="event.preventDefault(); handleBarcodeSubmit();">
                    <div class="d-flex gap-2">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ti ti-barcode"></i></span>
                            <input type="text" id="barcode_input" class="form-control form-control-lg" placeholder="Pindai atau ketik barcode obat..." autofocus autocomplete="off" />
                        </div>
                        <button type="button" id="btnScanCamera" class="btn btn-teal-accent text-white px-4 d-flex align-items-center gap-2" style="background-color: #64ad9f;">
                            <i class="ti ti-camera fs-4"></i> <span class="d-none d-sm-inline">Pindai</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card 2: Pencarian & Daftar Produk -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
                    <h5 class="card-title fw-bold mb-0 d-flex align-items-center">
                        <i class="ti ti-search text-teal me-2 fs-3"></i> Cari Produk Obat
                    </h5>
                    <!-- Input Cari -->
                    <div class="input-group input-group-merge style-search" style="max-width: 320px;">
                        <span class="input-group-text"><i class="ti ti-search"></i></span>
                        <input type="text" id="product_search" class="form-control" placeholder="Ketik nama, generik, kategori..." />
                    </div>
                </div>

                <!-- Grid Produk Obat -->
                <div class="row row-cols-1 row-cols-md-3 g-3" id="product_grid">
                    <!-- Data diload via JavaScript -->
                    <div class="text-center py-5 w-100">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KOLOM KANAN: Mode Harga & Keranjang Belanja -->
    <div class="col-lg-4 col-md-12">
        <!-- Card 3: Mode Harga -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title fw-bold mb-3 d-flex align-items-center">
                    <i class="ti ti-coins text-warning me-2 fs-3"></i> Mode Harga
                </h5>
                <div class="row g-2">
                    <div class="col-6">
                        <div id="mode_umum" class="card price-mode-card p-3 text-center border shadow-none" onclick="setPriceMode('umum')" style="background-color: #0f766e !important; color: #ffffff !important; border-color: #0f766e !important; cursor: pointer;">
                            <i class="ti ti-users fs-3 mb-2" style="color: #ffffff !important;"></i>
                            <h6 class="mb-0 fw-bold" style="color: #ffffff !important;">Pelanggan Umum</h6>
                        </div>
                    </div>
                    <div class="col-6">
                        <div id="mode_medis" class="card price-mode-card p-3 text-center border shadow-none" onclick="setPriceMode('medis')" style="background-color: #f8f9fa !important; border-color: #d9dade !important; cursor: pointer;">
                            <i class="ti ti-building-hospital fs-3 mb-2 text-secondary"></i>
                            <h6 class="mb-0 fw-bold text-secondary">Instansi Medis</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Keranjang Belanja -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-3">
                <h5 class="card-title fw-bold mb-0 d-flex align-items-center">
                    <i class="ti ti-shopping-cart text-primary me-2 fs-3"></i> Keranjang Belanja
                </h5>
                <span class="badge bg-primary rounded-pill" id="cart_badge">0 item</span>
            </div>
            <div class="card-body p-0">
                <!-- Area List Item Keranjang -->
                <div class="cart-container p-3" id="cart_list">
                    <!-- Tampilan default saat keranjang kosong -->
                    <div class="text-center py-5 text-muted" id="empty_cart_placeholder">
                        <i class="ti ti-shopping-cart-x fs-1 mb-2 opacity-50"></i>
                        <p class="mb-0 fw-semibold">Keranjang Kosong</p>
                        <small>Scan barcode atau cari obat untuk mulai transaksi</small>
                    </div>
                </div>

                <!-- Footer Perhitungan & Pembayaran -->
                <div class="bg-light p-3 border-top rounded-bottom">
                    <!-- Subtotal / Grand Total -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold text-heading">Total Pembayaran</span>
                        <h4 class="fw-bold text-teal mb-0" id="grand_total">Rp 0</h4>
                    </div>

                    <!-- Input Pembayaran -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="nominal_bayar">Nominal Uang Bayar <span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">Rp</span>
                            <input type="number" id="nominal_bayar" class="form-control form-control-lg fw-bold text-teal" placeholder="0" min="0" oninput="calculateChange()" />
                        </div>
                        <!-- Tombol Cepat Bayar -->
                        <div class="d-flex flex-wrap gap-1 mt-2" id="quick_cash_buttons">
                            <button type="button" class="btn btn-xs btn-outline-secondary btn-quick-cash" onclick="setQuickCash(10000)">10k</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary btn-quick-cash" onclick="setQuickCash(20000)">20k</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary btn-quick-cash" onclick="setQuickCash(50000)">50k</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary btn-quick-cash" onclick="setQuickCash(100000)">100k</button>
                            <button type="button" class="btn btn-xs btn-outline-primary btn-quick-cash" id="btn_exact_cash" onclick="setExactCash()">Uang Pas</button>
                        </div>
                    </div>

                    <!-- Kembalian -->
                    <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-2 rounded border border-dashed">
                        <span class="fw-semibold text-secondary">Kembalian</span>
                        <h5 class="fw-bold text-dark mb-0" id="change_amount">Rp 0</h5>
                    </div>

                    <!-- Tombol Aksi Transaksi -->
                    <button type="button" id="btnSubmitTransaction" class="btn btn-primary btn-lg w-100 py-3 fw-bold shadow-sm" onclick="submitTransaction()">
                        <i class="ti ti-wallet me-2"></i> Proses Transaksi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PEMINDAI KAMERA (HTML5-QRCode) -->
<div class="modal fade" id="cameraModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="ti ti-camera me-2"></i> Pindai Barcode Kamera</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="stopCameraScan()"></button>
            </div>
            <div class="modal-body bg-dark text-center p-3">
                <div id="reader" style="width: 100%;"></div>
                <div class="text-white mt-3"><i class="ti ti-info-circle me-1"></i> Arahkan barcode obat ke area kotak kamera</div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal" onclick="stopCameraScan()">Batal</button>
            </div>
        </div>
    </div>
</div>

<!-- AUDIO BEEP UNTUK SCANNER -->
<audio id="beep_sound" src="https://assets.mixkit.co/active_storage/sfx/2568/2568-84.wav" preload="auto"></audio>

@endsection

@push('myscript')
    <!-- Script HTML5-QRCode via CDN -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    
    <script>
        // State Kasir
        let allProducts = [];
        let cart = [];
        let priceMode = 'umum'; // Default: umum
        let html5QrCode = null;

        $(function() {
            // Set mode harga default secara eksplisit agar visual hijau langsung aktif
            setPriceMode('umum');

            // Load obat awal
            loadProducts();

            // Handle event cari input (dengan debounce)
            let searchTimeout = null;
            $('#product_search').on('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    loadProducts($('#product_search').val());
                }, 300);
            });

            // Tombol buka kamera scan
            $('#btnScanCamera').on('click', function() {
                $('#cameraModal').modal('show');
                startCameraScan();
            });
        });

        // Load data obat dari server via AJAX
        function loadProducts(searchQuery = '') {
            $.ajax({
                url: "{{ route('kasir.search') }}",
                type: 'GET',
                data: { q: searchQuery },
                success: function(data) {
                    allProducts = data;
                    renderProductsGrid();
                },
                error: function() {
                    $('#product_grid').html('<div class="text-center py-5 w-100 text-danger"><i class="ti ti-alert-triangle fs-1"></i><p class="mb-0 mt-2 fw-bold">Gagal memuat produk obat.</p></div>');
                }
            });
        }

        // Render produk ke dalam grid
        function renderProductsGrid() {
            let grid = $('#product_grid');
            grid.empty();

            if (allProducts.length === 0) {
                grid.html('<div class="text-center py-5 w-100 text-muted"><i class="ti ti-package-off fs-1"></i><p class="mb-0 mt-2 fw-semibold">Produk tidak ditemukan</p></div>');
                return;
            }

            allProducts.forEach(function(p) {
                // Pilih harga berdasarkan mode harga
                let currentPrice = priceMode === 'umum' ? p.harga_jual_umum : p.harga_jual_medis;
                let formattedPrice = formatRupiah(currentPrice);

                // Styling badge kategori
                let categoryBadgeClass = 'bg-label-primary';
                if (p.category) {
                    let catName = p.category.name_category.toLowerCase();
                    if (catName.includes('keras')) {
                        categoryBadgeClass = 'bg-label-danger';
                    } else if (catName.includes('bebas')) {
                        categoryBadgeClass = 'bg-label-success';
                    } else if (catName.includes('herbal') || catName.includes('jamu')) {
                        categoryBadgeClass = 'bg-label-warning';
                    }
                }

                // Status stok visual
                let isOutOfStock = p.stok <= 0;
                let isLowStock = p.stok <= p.batas_stok_minimum;
                let stockText = `<span class="fw-semibold text-${isOutOfStock ? 'danger' : (isLowStock ? 'warning' : 'success')}">Stok: ${p.stok} ${p.satuan_kemasan}</span>`;

                let cardHtml = `
                    <div class="col">
                        <div class="card h-100 obat-card shadow-sm rounded-3">
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <span class="badge ${categoryBadgeClass} mb-2">${p.category ? p.category.name_category : 'Lain-lain'}</span>
                                    <h6 class="card-title fw-bold text-heading mb-1 text-truncate" title="${p.nama_obat}">${p.nama_obat}</h6>
                                    <p class="card-text text-muted mb-2 text-truncate" style="font-size: 0.8rem;" title="${p.nama_generik}">${p.nama_generik}</p>
                                    <div class="mb-3 d-flex justify-content-between align-items-center" style="font-size: 0.85rem;">
                                        ${stockText}
                                        <small class="text-secondary fw-semibold">${p.barcode_sku}</small>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <h5 class="fw-bold text-teal mb-0">${formattedPrice}</h5>
                                    <button type="button" class="btn btn-sm btn-icon btn-label-teal rounded-circle" onclick="addToCart(${p.id})" ${isOutOfStock ? 'disabled' : ''} title="Tambah ke keranjang">
                                        <i class="ti ti-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                grid.append(cardHtml);
            });
        }

        // Switch mode harga (umum / medis)
        function setPriceMode(mode) {
            priceMode = mode;
            
            if (mode === 'umum') {
                // Set Umum Active (Teal)
                $('#mode_umum').attr('style', 'background-color: #0f766e !important; color: #ffffff !important; border-color: #0f766e !important; cursor: pointer;')
                               .find('i, h6').attr('style', 'color: #ffffff !important;');
                               
                // Set Medis Inactive (Light Gray)
                $('#mode_medis').attr('style', 'background-color: #f8f9fa !important; border-color: #d9dade !important; color: #5d596c !important; cursor: pointer;')
                               .find('i, h6').attr('style', 'color: #5d596c !important;').removeClass('text-secondary');
            } else {
                // Set Medis Active (Teal)
                $('#mode_medis').attr('style', 'background-color: #0f766e !important; color: #ffffff !important; border-color: #0f766e !important; cursor: pointer;')
                               .find('i, h6').attr('style', 'color: #ffffff !important;');
                               
                // Set Umum Inactive (Light Gray)
                $('#mode_umum').attr('style', 'background-color: #f8f9fa !important; border-color: #d9dade !important; color: #5d596c !important; cursor: pointer;')
                               .find('i, h6').attr('style', 'color: #5d596c !important;').removeClass('text-dark');
            }

            // Render ulang produk & keranjang belanja dengan harga baru
            renderProductsGrid();
            updateCartUI();
        }

        // Tambah obat ke dalam keranjang
        function addToCart(productOrId) {
            let product;
            if (typeof productOrId === 'object' && productOrId !== null) {
                product = productOrId;
            } else {
                product = allProducts.find(p => p.id === productOrId);
            }
            
            if (!product) return;

            // Periksa jika stok obat habis
            if (product.stok <= 0) {
                Swal.fire({
                    title: 'Stok Habis!',
                    text: 'Obat ini tidak memiliki sisa stok yang bisa dijual.',
                    icon: 'warning',
                    confirmButtonText: 'Tutup'
                });
                return;
            }

            // Periksa apakah item sudah ada di keranjang
            let cartItem = cart.find(item => item.id === product.id);

            if (cartItem) {
                // Periksa kecukupan stok saat menambahkan qty
                if (cartItem.qty + 1 > product.stok) {
                    Swal.fire({
                        title: 'Batas Stok Tercapai!',
                        text: `Anda tidak dapat menambahkan kuantitas melebihi stok yang tersedia (${product.stok} ${product.satuan_kemasan}).`,
                        icon: 'warning',
                        confirmButtonText: 'Tutup'
                    });
                    return;
                }
                cartItem.qty++;
            } else {
                cart.push({
                    id: product.id,
                    barcode_sku: product.barcode_sku,
                    nama_obat: product.nama_obat,
                    satuan_kemasan: product.satuan_kemasan,
                    harga_jual_umum: product.harga_jual_umum,
                    harga_jual_medis: product.harga_jual_medis,
                    stok: product.stok,
                    qty: 1
                });
            }

            // Mainkan suara beep sukses
            playBeep();

            updateCartUI();
        }

        // Update UI Keranjang Belanja
        function updateCartUI() {
            let cartList = $('#cart_list');
            let cartBadge = $('#cart_badge');
            let grandTotalEl = $('#grand_total');

            cartList.empty();
            let total = 0;
            let totalItems = 0;

            if (cart.length === 0) {
                cartBadge.text('0 item');
                grandTotalEl.text('Rp 0');
                $('#nominal_bayar').val('');
                $('#change_amount').text('Rp 0');
                
                cartList.html(`
                    <div class="text-center py-5 text-muted" id="empty_cart_placeholder">
                        <i class="ti ti-shopping-cart-x fs-1 mb-2 opacity-50"></i>
                        <p class="mb-0 fw-semibold">Keranjang Kosong</p>
                        <small>Scan barcode atau cari obat untuk mulai transaksi</small>
                    </div>
                `);
                return;
            }

            cart.forEach(function(item, index) {
                // Pilih harga berdasarkan tipe pelanggan
                let currentPrice = priceMode === 'umum' ? item.harga_jual_umum : item.harga_jual_medis;
                let subtotal = currentPrice * item.qty;

                total += subtotal;
                totalItems += item.qty;

                let itemHtml = `
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                        <div style="max-width: 60%;">
                            <h6 class="mb-0 fw-bold text-dark text-truncate" title="${item.nama_obat}">${item.nama_obat}</h6>
                            <small class="text-muted">${formatRupiah(currentPrice)} / ${item.satuan_kemasan}</small>
                        </div>
                        <div class="d-flex flex-column align-items-end">
                            <div class="d-flex align-items-center gap-1 mb-1">
                                <button type="button" class="btn btn-xs btn-label-secondary btn-icon rounded" onclick="changeQty(${index}, -1)">
                                    <i class="ti ti-minus"></i>
                                </button>
                                <input type="number" class="form-control text-center p-0 text-dark fw-bold border-0" style="width: 32px; font-size: 0.85rem;" value="${item.qty}" min="1" max="${item.stok}" onchange="updateQty(${index}, this.value)" />
                                <button type="button" class="btn btn-xs btn-label-secondary btn-icon rounded" onclick="changeQty(${index}, 1)">
                                    <i class="ti ti-plus"></i>
                                </button>
                                <button type="button" class="btn btn-xs btn-label-danger btn-icon rounded ms-2" onclick="removeFromCart(${index})">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                            <span class="fw-semibold text-teal" style="font-size: 0.9rem;">${formatRupiah(subtotal)}</span>
                        </div>
                    </div>
                `;
                cartList.append(itemHtml);
            });

            cartBadge.text(`${totalItems} item`);
            grandTotalEl.text(formatRupiah(total));

            // Perbarui tombol uang pas jika nominal pembayaran terisi
            calculateChange();
        }

        // Hapus item dari keranjang
        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCartUI();
        }

        // Ubah kuantitas via tombol tambah/kurang
        function changeQty(index, amount) {
            let item = cart[index];
            let newQty = item.qty + amount;

            if (newQty <= 0) {
                removeFromCart(index);
                return;
            }

            if (newQty > item.stok) {
                Swal.fire({
                    title: 'Batas Stok Tercapai!',
                    text: `Stok hanya bersisa ${item.stok} ${item.satuan_kemasan}.`,
                    icon: 'warning',
                    confirmButtonText: 'Tutup'
                });
                return;
            }

            item.qty = newQty;
            updateCartUI();
        }

        // Update kuantitas via ketik manual
        function updateQty(index, value) {
            let item = cart[index];
            let newQty = parseInt(value) || 1;

            if (newQty <= 0) {
                newQty = 1;
            }

            if (newQty > item.stok) {
                Swal.fire({
                    title: 'Batas Stok Tercapai!',
                    text: `Stok hanya bersisa ${item.stok} ${item.satuan_kemasan}.`,
                    icon: 'warning',
                    confirmButtonText: 'Tutup'
                });
                newQty = item.stok;
            }

            item.qty = newQty;
            updateCartUI();
        }

        // Menghitung uang kembalian
        function calculateChange() {
            let total = getGrandTotal();
            let payAmount = parseInt($('#nominal_bayar').val()) || 0;
            let change = payAmount - total;

            let changeEl = $('#change_amount');

            if (change < 0) {
                changeEl.text('Rp 0').removeClass('text-success').addClass('text-danger');
            } else {
                changeEl.text(formatRupiah(change)).removeClass('text-danger').addClass('text-success');
            }

            // Update status Uang Pas
            $('#btn_exact_cash').text(total > 0 ? `Pas (${formatRupiah(total)})` : 'Uang Pas');
        }

        // Dapatkan Total Belanja
        function getGrandTotal() {
            let total = 0;
            cart.forEach(function(item) {
                let currentPrice = priceMode === 'umum' ? item.harga_jual_umum : item.harga_jual_medis;
                total += currentPrice * item.qty;
            });
            return total;
        }

        // Set uang cepat (10k, 20k, 50k, 100k)
        function setQuickCash(amount) {
            let current = parseInt($('#nominal_bayar').val()) || 0;
            $('#nominal_bayar').val(current + amount);
            calculateChange();
        }

        // Set nominal Bayar Uang Pas
        function setExactCash() {
            let total = getGrandTotal();
            if (total <= 0) return;
            $('#nominal_bayar').val(total);
            calculateChange();
        }

        // Memproses Scan Barcode (manual/ketik)
        function handleBarcodeSubmit() {
            let barcode = $('#barcode_input').val().trim();
            if (!barcode) return;

            // Cari produk berdasarkan Barcode/SKU
            $.ajax({
                url: "{{ route('kasir.search') }}",
                type: 'GET',
                data: { q: barcode },
                success: function(data) {
                    if (data.length > 0) {
                        // Jika obat ditemukan, tambahkan obat pertama yang cocok
                        let foundObat = data.find(o => o.barcode_sku === barcode || o.nama_obat.toLowerCase() === barcode.toLowerCase());
                        if (foundObat) {
                            addToCart(foundObat);
                        } else {
                            addToCart(data[0]);
                        }
                    } else {
                        // Notifikasi jika tidak ditemukan
                        Swal.fire({
                            title: 'Obat Tidak Ditemukan',
                            text: `Barcode SKU "${barcode}" tidak terdaftar di database.`,
                            icon: 'error',
                            confirmButtonText: 'Tutup'
                        });
                    }
                    $('#barcode_input').val('').focus();
                },
                error: function() {
                    $('#barcode_input').val('').focus();
                }
            });
        }

        // Pindai Barcode Kamera menggunakan HTML5-QRCode
        function startCameraScan() {
            html5QrCode = new Html5Qrcode("reader");

            html5QrCode.start(
                { facingMode: "environment" }, 
                {
                    fps: 15,
                    qrbox: function(width, height) {
                        // Kotak pindai yang proporsional untuk barcode 1D
                        let boxWidth = Math.min(width * 0.8, 300);
                        let boxHeight = 120;
                        return { width: boxWidth, height: boxHeight };
                    }
                },
                function(decodedText, decodedResult) {
                    // Berhasil scan barcode!
                    playBeep();
                    $('#barcode_input').val(decodedText);
                    
                    // Stop kamera & tutup modal
                    stopCameraScan();
                    $('#cameraModal').modal('hide');

                    // Proses barcode untuk tambah obat
                    handleBarcodeSubmit();
                },
                function(errorMessage) {
                    // Log error pemindaian (biasanya diabaikan agar kamera terus membaca)
                }
            ).catch(function(err) {
                Swal.fire({
                    title: 'Izin Kamera Ditolak!',
                    text: 'Gagal membuka kamera. Harap izinkan situs ini untuk mengakses kamera Anda.',
                    icon: 'error',
                    confirmButtonText: 'Tutup'
                });
                $('#cameraModal').modal('hide');
            });
        }

        // Menghentikan Kamera Scan
        function stopCameraScan() {
            if (html5QrCode) {
                html5QrCode.stop().then(function() {
                    html5QrCode = null;
                }).catch(function(err) {
                    html5QrCode = null;
                });
            }
        }

        // Mainkan suara beep saat sukses scan
        function playBeep() {
            let sound = document.getElementById('beep_sound');
            if (sound) {
                sound.currentTime = 0;
                sound.play().catch(function(e) {
                    // Abaikan pembatasan browser autoplay
                });
            }
        }

        // Simpan transaksi penjualan ke server via AJAX
        function submitTransaction() {
            let total = getGrandTotal();
            if (cart.length === 0) {
                Swal.fire({
                    title: 'Keranjang Kosong!',
                    text: 'Tambahkan beberapa obat ke keranjang terlebih dahulu.',
                    icon: 'warning',
                    confirmButtonText: 'Tutup'
                });
                return;
            }

            let payAmount = parseInt($('#nominal_bayar').val()) || 0;
            if (payAmount < total) {
                Swal.fire({
                    title: 'Pembayaran Kurang!',
                    text: 'Nominal uang bayar tidak boleh kurang dari total pembayaran.',
                    icon: 'error',
                    confirmButtonText: 'Tutup'
                });
                return;
            }

            // Tampilkan konfirmasi
            Swal.fire({
                title: 'Konfirmasi Transaksi',
                text: `Proses penjualan dengan total pembayaran ${formatRupiah(total)}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Bayar!',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'btn btn-primary me-3',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Loading State
                    Swal.fire({
                        title: 'Menyimpan Transaksi...',
                        html: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Siapkan data item belanja
                    let itemsData = cart.map(item => {
                        let currentPrice = priceMode === 'umum' ? item.harga_jual_umum : item.harga_jual_medis;
                        return {
                            id: item.id,
                            qty: item.qty,
                            harga: currentPrice,
                            subtotal: currentPrice * item.qty
                        };
                    });

                    // AJAX Request
                    $.ajax({
                        url: "{{ route('kasir.store') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            tipe_pelanggan: priceMode,
                            total_harga: total,
                            bayar: payAmount,
                            kembali: payAmount - total,
                            items: itemsData
                        },
                        success: function(response) {
                            Swal.close();
                            if (response.success) {
                                // Tampilkan visual Resi Transaksi dalam popup SweetAlert
                                showInvoiceReceipt(response.invoice, response.details);
                                
                                // Bersihkan State Kasir
                                cart = [];
                                updateCartUI();
                                $('#nominal_bayar').val('');
                                
                                // Reload daftar obat untuk perbarui stok terbaru di grid
                                loadProducts($('#product_search').val());
                            } else {
                                Swal.fire({
                                    title: 'Transaksi Gagal!',
                                    text: response.message,
                                    icon: 'error',
                                    confirmButtonText: 'Tutup'
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.close();
                            let msg = 'Terjadi kesalahan sistem saat memproses transaksi.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                title: 'Gagal!',
                                text: msg,
                                icon: 'error',
                                confirmButtonText: 'Tutup'
                            });
                        }
                    });
                }
            });
        }

        // Tampilkan Struk Pembayaran di Modal SweetAlert dan sediakan Cetak
        function showInvoiceReceipt(invoice, details) {
            let itemsHtml = '';
            details.forEach(item => {
                itemsHtml += `
                    <tr style="font-size: 0.85rem; border-bottom: 1px dashed #ddd;">
                        <td style="padding: 5px 0; text-align: left;">
                            ${item.obat ? item.obat.nama_obat : 'Obat'} <br/>
                            <small>${item.qty} x ${formatRupiah(item.harga_satuan)}</small>
                        </td>
                        <td style="padding: 5px 0; text-align: right; vertical-align: bottom;">
                            ${formatRupiah(item.subtotal)}
                        </td>
                    </tr>
                `;
            });

            let receiptContent = `
                <div id="receipt-print-area" style="font-family: 'Courier New', Courier, monospace; color: #000; width: 100%; max-width: 320px; margin: 0 auto; padding: 15px; border: 1px solid #ddd; background: #fff; line-height: 1.4;">
                    <div style="text-align: center; margin-bottom: 15px;">
                        <h4 style="margin: 0; font-weight: bold; font-size: 1.2rem;">APOTEK RIZKI</h4>
                        <p style="margin: 3px 0 0 0; font-size: 0.8rem;">Jl. Raya Apotek Rizki No. 10</p>
                        <p style="margin: 2px 0 0 0; font-size: 0.75rem;">Telp: (021) 1234-5678</p>
                    </div>
                    <div style="border-top: 1px dashed #000; border-bottom: 1px dashed #000; padding: 8px 0; margin-bottom: 15px; text-align: left; font-size: 0.8rem;">
                        <div>No. Invoice : <b>${invoice.no_invoice}</b></div>
                        <div>Tanggal     : ${invoice.tanggal}</div>
                        <div>Pelanggan   : ${invoice.tipe_pelanggan === 'umum' ? 'Pelanggan Umum' : 'Instansi Medis'}</div>
                    </div>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="font-size: 0.85rem; font-weight: bold; border-bottom: 1px dashed #000;">
                                <th style="text-align: left; padding-bottom: 5px;">Produk</th>
                                <th style="text-align: right; padding-bottom: 5px;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${itemsHtml}
                        </tbody>
                    </table>
                    <div style="border-top: 1px dashed #000; margin-top: 10px; padding-top: 10px; font-size: 0.85rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 3px;">
                            <span>TOTAL HARGA</span>
                            <b>${formatRupiah(invoice.total_harga)}</b>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 3px;">
                            <span>TUNAI / BAYAR</span>
                            <span>${formatRupiah(invoice.bayar)}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; border-top: 1px dashed #ccc; padding-top: 5px; font-weight: bold;">
                            <span>KEMBALIAN</span>
                            <span>${formatRupiah(invoice.kembali)}</span>
                        </div>
                    </div>
                    <div style="text-align: center; margin-top: 25px; font-size: 0.8rem; border-top: 1px dashed #000; padding-top: 10px;">
                        *** TERIMA KASIH *** <br/>
                        Semoga Lekas Sembuh
                    </div>
                </div>
            `;

            Swal.fire({
                title: 'Transaksi Berhasil!',
                html: receiptContent,
                icon: 'success',
                showCancelButton: true,
                confirmButtonText: '<i class="ti ti-printer me-1"></i> Cetak Struk',
                cancelButtonText: 'Tutup',
                customClass: {
                    confirmButton: 'btn btn-primary me-3',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Fungsionalitas print area struk
                    printReceipt();
                }
            });
        }

        // Print Struk ke Printer Kertas
        function printReceipt() {
            let printContent = document.getElementById('receipt-print-area').innerHTML;
            let originalContent = document.body.innerHTML;

            // Buat blank window untuk print rapi tanpa layout admin
            let popupWin = window.open('', '_blank', 'width=350,height=600');
            popupWin.document.open();
            popupWin.document.write(`
                <html>
                <head>
                    <title>Cetak Struk Pembayaran</title>
                    <style>
                        body { margin: 0; padding: 10px; }
                        #receipt-print-area { font-family: 'Courier New', Courier, monospace; width: 100%; max-width: 320px; line-height: 1.4; }
                    </style>
                </head>
                <body onload="window.print(); window.close();">
                    ${printContent}
                </body>
                </html>
            `);
            popupWin.document.close();
        }

        // Format Angka ke mata uang Rupiah
        function formatRupiah(angka) {
            var number_string = angka.toString(),
                sisa = number_string.length % 3,
                rupiah = number_string.substr(0, sisa),
                ribuan = number_string.substr(sisa).match(/\d{3}/g);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            return 'Rp ' + rupiah;
        }
    </script>
@endpush
