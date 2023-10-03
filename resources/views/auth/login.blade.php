@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="app row justify-content-center">

            <div class="col-md-8">
                <div style="text-align: center">

                    <h3 style="color:blanchedalmond">GSF EveStuff</h3>
                    <br>
                    <br>

                    <a href="/oauth/login"><img src="{!! asset('svg/logo.svg') !!}" style="width:500px;height:300px;"></a>

                    <br>
                    <br>
                    <h4><a class="btn btn-block btn-primary" href="/oauth/login" role="button">LOGIN</a></h4>

                </div>
            </div>
        </div>
    @endsection
