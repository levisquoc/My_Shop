@extends('main::Layouts.main')
@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Column -->
            <div class="card">
                <div class="card-body">
                    {!! Menu::render() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {!! Menu::scripts() !!}
@endpush