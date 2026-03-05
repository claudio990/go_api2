<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="bill" class="form-label">{{ __('Bill') }}</label>
            <input type="text" name="bill" class="form-control @error('bill') is-invalid @enderror" value="{{ old('bill', $bill?->bill) }}" id="bill" placeholder="Bill">
            {!! $errors->first('bill', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="concept" class="form-label">{{ __('Concept') }}</label>
            <input type="text" name="concept" class="form-control @error('concept') is-invalid @enderror" value="{{ old('concept', $bill?->concept) }}" id="concept" placeholder="Concept">
            {!! $errors->first('concept', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>