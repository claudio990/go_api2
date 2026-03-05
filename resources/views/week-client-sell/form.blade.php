<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="id_client" class="form-label">{{ __('Id Client') }}</label>
            <input type="text" name="id_client" class="form-control @error('id_client') is-invalid @enderror" value="{{ old('id_client', $weekClientSell?->id_client) }}" id="id_client" placeholder="Id Client">
            {!! $errors->first('id_client', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="id_week" class="form-label">{{ __('Id Week') }}</label>
            <input type="text" name="id_week" class="form-control @error('id_week') is-invalid @enderror" value="{{ old('id_week', $weekClientSell?->id_week) }}" id="id_week" placeholder="Id Week">
            {!! $errors->first('id_week', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="payed" class="form-label">{{ __('Payed') }}</label>
            <input type="text" name="payed" class="form-control @error('payed') is-invalid @enderror" value="{{ old('payed', $weekClientSell?->payed) }}" id="payed" placeholder="Payed">
            {!! $errors->first('payed', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>