@extends('layouts.dashboard_template')

@section('content')
    <section class="content-header block-breadcrumb">
        <h1>
            {{ $page_title ?? 'Page Title' }}
            <small>{{ $page_description ?? '' }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('data.aki-akb.index') }}">Data AKI & AKB</a></li>
            <li class="active">{{ $page_title }}</li>
        </ol>
    </section>
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                @include('partials.flash_message')

                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Oops!</strong> Ada kesalahan pada inputan Anda..<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>
                @endif

                <!-- form start -->
                {!! Form::model($epidemi, [
                    'route' => ['data.epidemi-penyakit.update', $epidemi->id],
                    'method' => 'put',
                    'id' => 'form-akib',
                    'class' => 'form-horizontal form-label-left',
                ]) !!}

                <div class="box-body">

                    @include('data.epidemi_penyakit.form_edit')

                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    @include('partials.button_reset_submit')
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        </div>
    </section>
@endsection
