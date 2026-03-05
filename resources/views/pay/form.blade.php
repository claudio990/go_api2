<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="id_client" class="form-label">{{ __('Id Client') }}</label>
            <input type="text" name="id_client" class="form-control @error('id_client') is-invalid @enderror" value="{{ old('id_client', $pay?->id_client) }}" id="id_client" placeholder="Id Client">
            {!! $errors->first('id_client', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="id_week" class="form-label">{{ __('Id Week') }}</label>
            <input type="text" name="id_week" class="form-control @error('id_week') is-invalid @enderror" value="{{ old('id_week', $pay?->id_week) }}" id="id_week" placeholder="Id Week">
            {!! $errors->first('id_week', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="pay" class="form-label">{{ __('Pay') }}</label>
            <input type="text" name="pay" class="form-control @error('pay') is-invalid @enderror" value="{{ old('pay', $pay?->pay) }}" id="pay" placeholder="Pay">
            {!! $errors->first('pay', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="concept" class="form-label">{{ __('Concept') }}</label>
            <input type="text" name="concept" class="form-control @error('concept') is-invalid @enderror" value="{{ old('concept', $pay?->concept) }}" id="concept" placeholder="Concept">
            {!! $errors->first('concept', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>