@extends('layouts.app')

@section('graph')
    <div style="margin-left: 100px;  width:80%;">
        {!! $chartjs->render() !!}
    </div>
@endsection
