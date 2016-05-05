@extends('layouts.scaffold')
@section('main')

<div id="divIrAbajo">
    <a href="#fondo" title="Ir abajo"><i class="glyphicon glyphicon-chevron-down"></i></a>
    <a href="#arriba" title="Ir arriba"><i class="glyphicon glyphicon-chevron-up"></i></a>
</div>
<div id="arriba" class="container">
    <div class="alert alert-info" align="center">
        <h1>{{ $tipoOferta }}: <strong>"{{ $oferta->nombre }}"</strong></h1>
    </div>
    @if(count($inscripciones))
    <div class="alert alert-warning" style="width: 30%;margin-left: 33%;">
        <table class="tablaExportar">
            <tr>
                <td rowspan="2"><strong>Exportar listado</strong></td>
                <td colspan="2"><strong>Excel</strong></td>
                <td colspan="2"><strong>PDF</strong></td>
                @if($perfil == "Administrador")
                    <td><strong>CSV</strong></td>
                @endif
            </tr>
            <tr>
                <td><a href="{{ URL::Route('ofertas.inscripciones.index', array('oferta_id' => $oferta->id, 'exp' => 'xlsp')) }}" title="Exportar listado de todos los Pre-Inscriptos a Excel"><i class="fa fa-file-excel-o fa-3"></i></a></td>
                <td><a href="{{ URL::Route('ofertas.inscripciones.index', array('oferta_id' => $oferta->id, 'exp' => 'xlsi')) }}" title="Exportar listado solo de Inscriptos a Excel"><i class="fa fa-file-excel-o fa-3"></i></a></td>
                <td><a href="{{ URL::Route('ofertas.inscripciones.index', array('oferta_id' => $oferta->id, 'exp' => 'pdfp')) }}" title="Exportar listado de todos los Pre-inscriptos a PDF"><i class="fa fa-file-pdf-o fa-3"></i></a></td>
                <td><a href="{{ URL::Route('ofertas.inscripciones.index', array('oferta_id' => $oferta->id, 'exp' => 'pdfi')) }}" title="Exportar listado solo de Inscriptos a PDF"><i class="fa fa-file-pdf-o fa-3"></i></a></td>
                @if($perfil == "Administrador")
                    <td><a href="{{ URL::Route('ofertas.inscripciones.index', array('oferta_id' => $oferta->id, 'exp' => 'csv')) }}" title="Exportar listado solo de Inscriptos a CSV"><i class="fa fa-file-text-o"></i></a></td>
                @endif
            </tr>
        </table>
     </div>
    @endif
    <a class='btn btn-primary' href="{{ URL::route('ofertas.index') }}" title="Volver al listado de Ofertas" >Volver</a>
    <hr>
    <h4>
        @if(count($inscripciones))
            Total: {{ count($inscripciones) }}
        @endif
    </h4>
    @if (count($inscripciones))
    <div id="preinscriptos">
        <?php $listaIdPreinscriptos = array();?>
        {{ Form::open(array(
                    'method' => 'POST',
                    'action' => array('OfertasInscripcionesController@cambiarInscripciones', $oferta->id))) }}    
	<table class="table table-striped" style="border-top: 2px black solid; border-bottom: 2px black solid">
            <thead>
                <tr>
                    <th><button class="sort" data-sort="nro">Nro.</button></th>
                    <th><button class="sort" data-sort="apellido">Apellido</button></th>
                    <th>Nombre</th>
                    @if($perfil != "Colaborador")
                        <th>Documento</th>
                    @endif
                    <th>Localidad</th>
                    <th>Correos</th>
                    @if($perfil != "Colaborador")
                        <!--<th>Email UDC</th>-->
                        <th>Inscripto ({{ count($inscriptos) }})</th>
                        <th>Notificado/a</th>
                    @endif
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody class="list">
               <?php $i = 1; ?>
               @foreach ($inscripciones as $inscripcion)
                    <?php $listaIdPreinscriptos[] = $inscripcion->id; ?>
                    <tr>
                        <td class="nro">{{ $i }}</td>
                        <td class="apellido">{{{ $inscripcion->apellido }}}</td>
                        <td>{{{ $inscripcion->nombre }}}</td>
                        @if($perfil != "Colaborador")
                            <td>{{{ $inscripcion->tipoydoc }}}</td>
                        @endif
                        <td>{{{ $inscripcion->localidad->la_localidad }}}</td>
                        <td>
                            <p>{{ $inscripcion->email }}</p>
                            <p style="color: blue">{{ $inscripcion->email_institucional }}</p>
                        </td>
                        @if($perfil != "Colaborador")
                            <!--<td>{{{ $inscripcion->email_institucional }}}</td>-->
                            <td>
                                <div class="slideTwo"><div class="slideTwo">
                                    @if ($inscripcion->getEsInscripto())
                                        <input type="checkbox" name="inscripto[<?php echo $inscripcion->id ?>]" id="slideTwo<?php echo $inscripcion->id ?>" value='1' checked='checked'><label for="slideTwo<?php echo $inscripcion->id ?>"></label>
                                    @else
                                        <input type="checkbox" name="inscripto[<?php echo $inscripcion->id ?>]" id="slideTwo<?php echo $inscripcion->id ?>" value='1'><label for="slideTwo<?php echo $inscripcion->id ?>"></label>
                                    @endif
                                </div>
                            </td>                        
                            <td>
                                @if ($inscripcion->getEsInscripto())
                                    @if ($inscripcion->getCantNotificaciones() > 0)
                                       @if ($inscripcion->getCantNotificaciones() == 1)
                                            {{ link_to_route('ofertas.inscripciones.enviarMailInstitucional', $inscripcion->getCantNotificaciones().' vez', array($oferta->id, $inscripcion->id), array('class' => 'btn btn-xs btn-success')) }}
                                       @else
                                            {{ link_to_route('ofertas.inscripciones.enviarMailInstitucional', $inscripcion->getCantNotificaciones().' veces', array($oferta->id, $inscripcion->id), array('class' => 'btn btn-xs btn-success')) }}
                                       @endif
                                    @else
                                       {{ link_to_route('ofertas.inscripciones.enviarMailInstitucional', 'nunca', array($oferta->id, $inscripcion->id), array('class' => 'btn btn-xs btn-danger')) }}
                                    @endif
                                @else
                                    <button style="width: 50px" class="btn btn-xs btn-block glyphicon glyphicon-remove-sign disable" title="No Corresponde"></button>
                                @endif
                            </td>
                        @endif  
                        <td>
                            {{ link_to_route('ofertas.inscripciones.edit', '', array($oferta->id, $inscripcion->id), array('class' => 'btn btn-xs btn-info glyphicon glyphicon-edit', 'title'=>'Editar datos del inscripto')) }}
                            <a href="{{route('ofertas.inscripciones.imprimir', [$oferta->id, $inscripcion->id])}}" class="btn btn-xs btn-default" title="Imprimir formulario de inscripcion"><i class="fa fa-file-pdf-o"></i></a>
                            @if($perfil != "Colaborador")
                                {{ Form::open(array('class' => 'confirm-delete', 'style' => 'display: inline-block;', 'method' => 'DELETE', 'route' => array('ofertas.inscripciones.destroy', $oferta->id, $inscripcion->id))) }}
                                    {{ Form::submit('Borrar', array('class' => 'btn btn-xs btn-danger','title'=>'Eliminar Inscripto')) }}
                                {{ Form::close() }}
                            @endif
                        </td>
                    </tr>
                    <?php $i++;?>
                @endforeach
		</tbody>
	</table>
        <?php $listaEnString = implode('-',$listaIdPreinscriptos); ?>
        <input type="hidden" id="listaIdPreinscriptos" name="listaIdPreinscriptos" value="<?php echo $listaEnString ?>">
        @if($perfil != "Colaborador")
            {{ Form::submit('Actualizar Inscriptos', array('class' => 'btn btn-success', 'style'=>'float: right', 'title'=>'Actualizar los datos.')) }}            
            {{ Form::close() }}
        @endif
    </div>
    @else
        <br>
        <h2>Aún no hay inscriptos en esta oferta.</h2>
        <p><a href="{{ URL::action('ofertas.inscripciones.create', $oferta->id) }}" class="btn-btn-link">Formulario de inscripción</a> | <a href="{{ URL::route('ofertas.index') }}">Lista de ofertas</a></p>
    @endif
    <div id="fondo">
        <a class='btn btn-primary' href="{{ URL::route('ofertas.index') }}" title="Volver al listado de Ofertas" >Volver</a>
    </div>
</div>

<script>
var options = {
  valueNames: [ 'apellido', 'nro' ]
};

var preinscriptosList = new List('preinscriptos', options);
</script>

@stop