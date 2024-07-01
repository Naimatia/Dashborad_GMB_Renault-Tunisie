@extends('layouts.master')

@section('title')
    Établissement
@stop

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <style>
        /* Hover effect on table rows */
        table tr:hover {
            background-color: #f5f5f5;
            /* Change the background color on hover */
        }
    </style>
@endsection

@section('title_head')
    {{ count($locations) }} Établissement
@endsection



@section('title_page1')
    Accueil
@endsection

@section('title_page2')
    Établissements
@endsection

@section('content')

   
@endsection

@section('scripts')
@endsection
