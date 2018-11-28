@extends('layouts.app', ['title' => 'Create An Address'])
@section('content')
    <create-address :address-id="{{ $edit ? $address->id : 'null' }}" />
@endsection