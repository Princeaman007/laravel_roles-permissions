@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow rounded-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Nous contacter</h4>
                </div>
                <div class="card-body">

                    @if(Session::has('message'))
                        <div class="alert alert-success text-center">
                            {{ Session::get('message') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nom</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" required value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse e-mail</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" required value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea name="message" id="message" rows="5" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success">
                                <i class="btn btn-success btn-lg"></i> Envoyer le message
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
