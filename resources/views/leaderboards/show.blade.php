@extends('layouts.master')

@section('content')
    <div id="app"
         page="leaderboardsShowPage"
         menu="leaderboardsPage"
         data="{{ $data }}"
    ></div>
@stop