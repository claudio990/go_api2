@extends('layouts.app')

@section('template_title')
    Pays
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Pays') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('pays.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
									<th >Id Client</th>
									<th >Id Week</th>
									<th >Pay</th>
									<th >Concept</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pays as $pay)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $pay->id_client }}</td>
										<td >{{ $pay->id_week }}</td>
										<td >{{ $pay->pay }}</td>
										<td >{{ $pay->concept }}</td>

                                            <td>
                                                <form action="{{ route('pays.destroy', $pay->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('pays.show', $pay->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('pays.edit', $pay->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $pays->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
