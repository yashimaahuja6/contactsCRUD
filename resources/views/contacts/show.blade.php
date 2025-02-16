@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Contact Details</h2>

        <div class="card">
            <div class="card-body">
                <p><strong>Name:</strong> {{ $contact->name }}</p>
                <p><strong>Phone Number:</strong> {{ $contact->phone_number }}</p>
            </div>
        </div>
    </div>
@endsection
