@extends('layouts.app')

@section('template_title')
    {{ $venta->name ?? __('Show') . " " . __('Venta') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Venta</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('ventas.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Client:</strong>
                                    {{ $venta->id_client }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Product:</strong>
                                    {{ $venta->product }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Description:</strong>
                                    {{ $venta->description }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Price:</strong>
                                    {{ $venta->price }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Week:</strong>
                                    {{ $venta->id_week }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
