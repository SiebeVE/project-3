@extends('layouts.app')

@section('header_left')
    <a href="/">
        <i class="fa fa-chevron-left" aria-hidden="true"></i>
        Home
    </a>
@endsection

@section('content')

    <div class="container">

        @include('partials.status')

        @include('partials.errors')

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Edit your information
                </h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="{{ route('user.update') }}">

                    {{ method_field('put') }}

                    {{ csrf_field() }}

                    <div class="row">
                        <div class="col-sm-6">
                            <!-- firstname input -->
                            <div class="form-group">
                                <label class="control-label" for="firstname">First name</label>
                                <input id="firstname" name="firstname" type="text" required autofocus value="{{ old('firstname', $user->firstname) }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!-- lastName input -->
                            <div class="form-group">
                                <label class="control-label" for="lastname">Last name</label>
                                <input id="lastname" name="lastname" type="text" required autofocus value="{{ old('lastname', $user->lastname) }}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Email input -->
                    <div class="form-group">
                        <label class="control-label" for="name">Email</label>
                        <input id="email" name="email" type="email" required value="{{ old('email', $user->email) }}" placeholder="random&commat;stuff.com" class="form-control">
                    </div>

                    <div class="row">
                        <div class="col-xs-8">
                            <!-- Address input -->
                            <div class="form-group">
                                <label class="control-label" for="street">Street</label>
                                <input id="street" name="street" type="text" required value="{{ old('street', $user->street) }}" placeholder="Line 1" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <!-- housenumber input -->
                            <div class="form-group">
                                <label class="control-label" for="number">Number</label>
                                <input id="number" name="number" type="text" value="{{ old('number', $user->number) }}" placeholder="Line 2" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-8">
                            <!-- City input -->
                            <div class="form-group">
                                <label class="control-label" for="city">City</label>
                                <input id="city" name="city" type="text" required value="{{ old('city', $user->city) }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <!-- Postal code input -->
                            <div class="form-group">
                                <label class="control-label" for="name">Postal</label>
                                <input id="postal" name="postal" type="text" required value="{{ old('postal', $user->postal) }}" class="form-control">
                            </div>
                        </div>
                    </div>


                    <!-- Country input -->
                    <div class="form-group">
                        <label class="control-label" for="country">Country</label>
                        <input id="country" name="country" type="text" required value="{{ old('country', $user->country) }}" class="form-control">
                    </div>

                    <input type="submit" class="btn btn-primary" value="Save information">

                </form>
            </div>
        </div>
    </div>

@endsection