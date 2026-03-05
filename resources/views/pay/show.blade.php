@extends('layouts.app')

@section('template_title')
    {{ $pay->name ?? __('Show') . " " . __('Pay') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Pay</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('pays.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Client:</strong>
                                    {{ $pay->id_client }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Week:</strong>
                                    {{ $pay->id_week }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Pay:</strong>
                                    {{ $pay->pay }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Concept:</strong>
                                    {{ $pay->concept }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
