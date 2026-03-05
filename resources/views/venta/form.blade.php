<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="id_client" class="form-label">{{ __('Id Client') }}</label>
            <input type="text" name="id_client" class="form-control @error('id_client') is-invalid @enderror" value="{{ old('id_client', $venta?->id_client) }}" id="id_client" placeholder="Id Client">
            {!! $errors->first('id_client', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="product" class="form-label">{{ __('Product') }}</label>
            <input type="text" name="product" class="form-control @error('product') is-invalid @enderror" value="{{ old('product', $venta?->product) }}" id="product" placeholder="Product">
            {!! $errors->first('product', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $venta?->description) }}" id="description" placeholder="Description">
            {!! $errors->first('description', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="price" class="form-label">{{ __('Price') }}</label>
            <input type="text" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $venta?->price) }}" id="price" placeholder="Price">
            {!! $errors->first('price', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="id_week" class="form-label">{{ __('Id Week') }}</label>
            <input type="text" name="id_week" class="form-control @error('id_week') is-invalid @enderror" value="{{ old('id_week', $venta?->id_week) }}" id="id_week" placeholder="Id Week">
            {!! $errors->first('id_week', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>