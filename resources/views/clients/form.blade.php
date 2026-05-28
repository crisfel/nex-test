@extends('layouts.app')

@section('title', isset($client) ? 'Editar cliente' : 'Nuevo cliente')

@section('content')
    <div id="app"
         data-page="clients-form"
         data-props='@json(['clientId' => $client->id ?? null])'>
    </div>
@endsection
