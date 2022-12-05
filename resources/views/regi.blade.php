@extends('layouts.guest')
@section('content')

    <div id="wrapper-admin">
        <div class="container">
            <div class="row">
                <div class="offset-md-4 col-md-4">
                    <div class="logo">
                        <img src="{{ asset('images/library.png') }}" alt="">
                    </div>
                    <form class="yourform" action="{{ route('reg') }}" method="post">
                        @csrf
                        <h3 class="heading">Register Admin</h3>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" value="" required>
                        </div>
                        <input type="submit" name="register" class="btn btn-primary" value="register" />
                    </form>
                    @error('name')
                        <div class='alert alert-danger'>{{ $message }}</div>
                    @enderror
                    @error('password')
                        <div class='alert alert-danger'>{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
@endsection
