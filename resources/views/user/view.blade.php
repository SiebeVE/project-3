@extends('layouts.app')

@section('header_left')
    <a href="/">
        <i class="fa fa-chevron-left" aria-hidden="true"></i>
        Back
    </a>
@endsection

@section('content')

    <div class="container">

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="button-group pull-right">
                            <a href="{{route('user.edit')}}" class="btn btn-lg btn-primary">Edit information</a>
                        </div>

                        <h1>{{$user->fullName}}</h1>

                        <p class="lead">
                            {{$user->street}} {{$user->number}}<br>
                            {{$user->postal}} {{$user->city}}<br>
                            {{$user->country}}
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection