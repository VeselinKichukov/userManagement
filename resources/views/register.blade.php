@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-2">
                @if($registered !== true)
                    <form action="{{url('/registrations/register?register=true')}}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg">Register</button>
                    </form>
                @else
                    <form action="{{url('/registrations/register?register=false') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-lg">Unregister</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
