@extends('layouts.dashboard_template')

@section('title')
    Data Profil
@endsection

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">{{ $page_title }}</li>
        </ol>
    </section>
    <section class="content container-fluid">

        @include('partials.flash_message')

        <div class="box box-primary">
            <div class="box-header with-border">
                @include('forms.btn-social', ['import_url' => route('data.penduduk.import')])
                @include('forms.btn-social', ['export_url' => route('data.penduduk.export-excel')])
            </div>

            <div class="box-body">
                @include('layouts.fragments.list-desa')
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="penduduk-table">
                        <thead>
                            <tr>
                                <th width="80px">Aksi</th>
                                <th>Foto</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>No. KK</th>
                                <th>Desa</th>
                                <th>Alamat</th>
                                <th>Pendidikan dalam KK</th>
                                <th>Umur</th>
                                <th>Pekerjaan</th>
                                <th>Status Kawin</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
@include('partials.asset_select2')
@include('partials.asset_datatables')

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#list_desa').select2();

            var data = $('#penduduk-table').DataTable({
                autoWidth: false,
                processing: true,
                serverSide: true,
                lengthMenu: [
                    [10, 25, 50, 75, 100, 500, -1],
                    [10, 25, 50, 75, 100, 500, "All"]
                ],
                ajax: {
                    url: "{!! route('data.penduduk.getdata') !!}",
                    data: function(d) {
                        d.desa = $('#list_desa').val();
                    }
                },
                columns: [{
                        data: 'aksi',
                        name: 'aksi',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'foto',
                        name: 'foto',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'nik',
                        name: 'nik'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'no_kk',
                        name: 'no_kk'
                    },
                    {
                        data: 'nama_desa',
                        name: 'das_data_desa.nama'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
                    },
                    {
                        data: 'pendidikan',
                        name: 'ref_pendidikan_kk.nama'
                    },
                    {
                        data: 'tanggal_lahir',
                        name: 'tanggal_lahir'
                    },
                    {
                        data: 'pekerjaan',
                        name: 'ref_pekerjaan.nama'
                    },
                    {
                        data: 'status_kawin',
                        name: 'ref_kawin.nama'
                    },
                ],
                order: [
                    [3, 'asc']
                ]
            });

            $('#list_desa').on('select2:select', function(e) {
                data.ajax.reload();
            });
        });
    </script>
    @include('forms.datatable-vertical')
    @include('forms.delete-modal')
@endpush
