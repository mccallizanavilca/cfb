@extends('layouts.base')
@section('title', 'Inscripción en: '.$oferta->nombre.' - Universidad del Chubut')
@section('main')
    @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                {{ implode('', $errors->all('<li class="error">:message</li>')) }}
            </ul>
            </div>
    @endif
@include('inscripciones.'.$oferta->view.'.form', array('obj' => null, 'oferta' => $oferta))
@stop