@extends('layouts.app')

@section('title', $client->name)

@section('content')
    <div id="app"
         data-page="clients-detail"
         data-props='@json(['clientId' => $client->id])'>
    </div>
@endsection
