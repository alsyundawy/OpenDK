@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('informasi.potensi.index') }}">Daftar Potensi</a></li>
            <li class="active">{{ $page_description ?? '' }}</li>
        </ol>
    </section>
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <a href="{{ route('informasi.potensi.index') }}">
                            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i>&nbsp;
                                Kembali</button>
                        </a>
                        <a href="{!! route('informasi.potensi.edit', $potensi->id) !!}" class="btn btn-sm btn-primary" title="Ubah" data-button="edit"><i class="fa fa-edit"></i>&nbsp; Ubah</a>

                        <a href="javascript:void(0)" class="" title="Hapus" data-href="{!! route('informasi.potensi.destroy', $potensi->id) !!}" data-button="delete" id="deleteModal">
                            <button type="button" class="btn btn-icon btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp; Hapus</button>
                        </a>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <!-- form start -->
                        <div class="row overflow-x">
                            <div class="col-md-12">
                                <img src="{{ asset($potensi->file_gambar) }}" width="100%">
                            </div>
                            <div class="col-md-12">
                                <h3>{{ $potensi->nama_potensi }}</h3>
                                <p>{{ $potensi->deskripsi }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    @include('forms.delete-modal')
@endpush
