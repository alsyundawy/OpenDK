@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('informasi.regulasi.index') }}">Daftar Regulasi</a></li>
            <li class="active">{{ $page_description }}</li>
        </ol>
    </section>
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <a href="{{ route('informasi.regulasi.index') }}">
                            <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i>&nbsp;
                                Kembali</button>
                        </a>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <!-- form start -->
                        <div class="row overflow-x">
                            <div class="col-md-12">
                                <label>Deskripsi : </label>
                                <p>{{ $regulasi->deskripsi }}</p>
                                </hr>
                                @if (isset($regulasi->file_regulasi) && $regulasi->mime_type != 'pdf')
                                    <img src="{{ asset($regulasi->file_regulasi) }}" width="100%">
                                @endif

                                @if (isset($regulasi->file_regulasi) && $regulasi->mime_type == 'pdf')
                                    <object data="@if (isset($regulasi->file_regulasi)) {{ asset($regulasi->file_regulasi . '#toolbar=1') }} @endif" type="application/pdf" class="showpdf" id="showpdf"> </object>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">

                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    @include('forms.delete-modal')
@endpush
