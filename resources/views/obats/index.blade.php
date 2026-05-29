@extends('layouts.app')
@section('title', 'Inventory Obat')

@push('style')
<style>
    /* Tabel inventori: compact, bersih, tidak overflow */
    #obatsTable th {
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #6b7280;
        white-space: nowrap;
        vertical-align: middle;
    }
    #obatsTable td {
        vertical-align: middle;
        font-size: 0.875rem;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }
    /* Badge stok yang lebih ramping */
    #obatsTable .stok-badge {
        font-size: 0.78rem;
        padding: 0.3rem 0.65rem;
        border-radius: 20px;
        font-weight: 600;
        white-space: nowrap;
    }
    /* Harga tidak melipat */
    #obatsTable .harga-col {
        white-space: nowrap;
        font-weight: 500;
    }
    /* Aksi tombol horizontal */
    #obatsTable .aksi-col {
        white-space: nowrap;
    }
</style>
@endpush

@section('content')

    <div class="card">
        <div class="card-datatable table-responsive pt-0">
            <table class="table border-top" id="obatsTable">
                <thead>
                    <tr>
                        <th style="width: 45px;">#</th>
                        <th style="min-width: 130px;">Barcode/SKU</th>
                        <th style="min-width: 150px;">Nama Obat</th>
                        <th style="min-width: 110px;">Kategori</th>
                        <th style="min-width: 120px; text-align: center;">Stok</th>
                        <th style="min-width: 110px;">Harga Jual</th>
                        <th style="width: 120px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($obats as $o)
                        <tr>
                            <td class="text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <span class="badge bg-label-info" style="font-size:0.75rem; letter-spacing:0.02em;">
                                    {{ $o->barcode_sku }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-semibold text-heading" style="font-size:0.875rem;">{{ $o->nama_obat }}</span>
                            </td>
                            <td>
                                @if ($o->category)
                                    <span class="badge bg-label-primary">{{ $o->category->name_category }}</span>
                                @else
                                    <span class="badge bg-label-secondary">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($o->stok <= 0)
                                    <span class="stok-badge badge bg-label-danger">Habis</span>
                                @elseif ($o->stok <= $o->batas_stok_minimum)
                                    <span class="stok-badge badge bg-label-warning">{{ $o->stok }} {{ $o->satuan_kemasan }}</span>
                                @else
                                    <span class="stok-badge badge bg-label-success">{{ $o->stok }} {{ $o->satuan_kemasan }}</span>
                                @endif
                            </td>
                            <td class="harga-col text-success">
                                Rp {{ number_format($o->harga_jual_umum, 0, ',', '.') }}
                            </td>
                            <td class="text-center aksi-col">
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    <a href="#" class="btn btn-sm btn-icon btn-label-info btn-show" data-url="{{ route('obats.show', $o->id) }}" title="Detail">
                                        <i class="ti ti-info-circle"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-icon btn-label-primary btn-edit" data-url="{{ route('obats.edit', $o->id) }}" title="Edit">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <form action="{{ route('obats.destroy', $o->id) }}" method="POST" class="d-inline-block m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-icon btn-label-danger btn-delete" title="Hapus">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL TAMBAH & EDIT DATA --}}
    <div class="modal fade" id="obatmodal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titlemodal"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="loadForm">
                    <!-- Modal body content goes here -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        $(function(){
            // Trigger SweetAlert2 popup if success message exists in Laravel Session
            @if (session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
            @endif

            // Trigger SweetAlert2 popup if error message exists in Laravel Session
            @if (session('error'))
                Swal.fire({
                    title: 'Gagal!',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
            @endif

            // Initialize DataTable
            var dt = $('#obatsTable').DataTable({
                dom: '<"card-header flex-column flex-md-row p-3 d-flex justify-content-between align-items-center"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row mx-2"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row mx-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                buttons: [
                    {
                        text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Tambah Obat</span>',
                        className: 'create-new btn btn-primary waves-effect waves-light',
                        attr: {
                            'id': 'btnAdd'
                        }
                    }
                ],
                language: {
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
                    infoFiltered: '(disaring dari _MAX_ total data)',
                    zeroRecords: 'Tidak ada data yang cocok ditemukan',
                    paginate: {
                        next: '<i class="ti ti-chevron-right"></i>',
                        previous: '<i class="ti ti-chevron-left"></i>'
                    }
                }
            });
            
            // Set header label title
            $('div.head-label').html('<h5 class="card-title mb-0">Daftar Stok & Inventory Obat</h5>');

            // Handle Add 
            $(document).on('click', '#btnAdd', function(e){
                e.preventDefault();
                $('#titlemodal').text('Tambah Obat Baru');
                $('#loadForm').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                $('#loadForm').load("{{ route('obats.create') }}");
                $('#obatmodal').modal('show');
            });

            // Handle Detail Button 
            $(document).on('click', '.btn-show', function(e){
                e.preventDefault();
                var url = $(this).data('url');
                $('#titlemodal').text('Informasi Detail Obat');
                $('#loadForm').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                $('#loadForm').load(url);
                $('#obatmodal').modal('show');
            });

            // Handle Edit Button 
            $(document).on('click', '.btn-edit', function(e){
                e.preventDefault();
                var url = $(this).data('url');
                $('#titlemodal').text('Edit Data Obat');
                $('#loadForm').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                $('#loadForm').load(url);
                $('#obatmodal').modal('show');
            });

            // Handle Delete 
            $(document).on('click', '.btn-delete', function(e){
                e.preventDefault();
                var form = $(this).closest('form');
                
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data obat yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: 'btn btn-danger me-3',
                        cancelButton: 'btn btn-label-secondary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
