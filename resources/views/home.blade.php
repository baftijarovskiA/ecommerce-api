@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <h6>Welcome, {{ Auth::user()->name }}!</h6>
                    <p>You have nothing to do here, click the button to continue.</p>
                    <a href="http://localhost:8080/" class="btn btn-primary">Continue</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
