@extends('layouts.app')

@section('template_title')
    {{ $weekClientSell->name ?? __('Show') . " " . __('Week Client Sell') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Week Client Sell</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('week-client-sells.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Client:</strong>
                                    {{ $weekClientSell->id_client }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Week:</strong>
                                    {{ $weekClientSell->id_week }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Payed:</strong>
                                    {{ $weekClientSell->payed }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
