@extends('main::Layouts.main')
@section('content')

    <div class="content">
        <div class="card">
            <div class="card-body">
                <h1><strong>Subject:</strong> {{$contact->subject}}</h1>
                <hr>
                <p><strong>Name:</strong> {{$contact->name}}</p>
                <p><strong>E-Mail:</strong> {{$contact->email}}</p>
                <p><strong>Phone:</strong> {{$contact->phone}}</p>
                <p><strong>Message:</strong> {{$contact->message}}</p>
            </div>
        </div>
    </div>

@endsection
