@extends('layouts.scaffold')
@section('main')

<style type="text/css">
  #divIrAbajo{
      position: fixed;
      right: 5px;;
      width: 80px;
      bottom: 100px;
      text-align: center;      
  }
  #divIrAbajo a{
      text-decoration: none;
      color: white;
      background-color: black;
      padding: 7px;
      border: 1px solid graytext;
  }
  #divIrAbajo i{
      size: 20px;
  }
</style>

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
    <?php 
        $colorInscriptos = "#A9F5D0";
        $colorListaDeEspera = "#F5D0A9";
    ?>    
        <div id='contenedor'>
            @if(count($inscripciones))
                <div style="display:inline;width: 40%;">
                    <h3><p style="float: left">Total: {{ count($inscripciones) }}</p></h3>
                    <p style="padding: 3px;border: 1px solid black; float: left;margin-left: 20px;background-color: <?php echo $colorInscriptos ?>"> Preinscriptos</p>
                    <p style="padding: 3px;border: 1px solid black; float: left;margin-left: 20px;background-color: <?php echo $colorListaDeEspera ?>">En lista de Espera</p>
                </div>
            @endif
        </div>
    @if (count($inscripciones))
	<table class="table table-condensed" style="border-top: 2px black solid; border-bottom: 2px black solid">
            <thead>
                <tr>
                    <th>Nro.</th>
                    <th>Apellido</th>
                    <th>Nombre</th>
                    @if($perfil != "Colaborador")
                        <th>Documento</th>
                    @endif
                    <th>Localidad</th>
                    <th>Email Personal</th>
                    @if($perfil != "Colaborador")
                        <th>Email UDC</th>
                        <th>Inscripto?</th>
                        <th>Notificado/a</th>
                    @endif
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                @foreach ($inscripciones as $inscripcion)
                    <?php if($i <= $oferta->cupo_maximo){
                              $colorBackground = 'style="background-color: '.$colorInscriptos.' !important"';
                          }else{
                              $colorBackground = 'style="background-color: '.$colorListaDeEspera.' !important"';
                          }
                    ?>
                    <tr>
                        <td <?php echo $colorBackground ?>>{{ $i }}</td>
                        <td>{{{ $inscripcion->apellido }}}</td>
                        <td>{{ $inscripcion->nombre }}</td>
                        @if($perfil != "Colaborador")
                            <td>{{{ $inscripcion->tipoydoc }}}</td>
                        @endif
                        <td>{{{ $inscripcion->localidad->la_localidad }}}</td>
                        <td>{{{ $inscripcion->email }}}</td>
                        @if($perfil != "Colaborador")
                            <td>{{{ $inscripcion->email_institucional }}}</td>
                            <td>
                                @if ($inscripcion->getEsInscripto())
                                   {{ link_to_route('ofertas.inscripciones.cambiarEstado', '', array($oferta->id, $inscripcion->id), array('class' => 'btn btn-success glyphicon glyphicon-ok-sign')) }}
                                @else
                                   {{ link_to_route('ofertas.inscripciones.cambiarEstado', '', array($oferta->id, $inscripcion->id), array('class' => 'btn btn-danger glyphicon glyphicon-remove-sign')) }}
                                @endif
                            </td>                        
                            <td>
                                @if ($inscripcion->getEsInscripto())
                                    @if ($inscripcion->getCantNotificaciones() > 0)
                                       {{ link_to_route('ofertas.inscripciones.enviarMailInstitucional', $inscripcion->getCantNotificaciones().' veces', array($oferta->id, $inscripcion->id), array('class' => 'btn btn-success')) }}
                                    @else
                                       {{ link_to_route('ofertas.inscripciones.enviarMailInstitucional', $inscripcion->getCantNotificaciones().' veces', array($oferta->id, $inscripcion->id), array('class' => 'btn btn-danger')) }}
                                    @endif
                                @else
                                    <button class="btn btn-block glyphicon glyphicon-remove-sign disable" title="No Corresponde"></button>
                                @endif
                            </td>
                        @endif
                        <td>
                            {{ link_to_route('ofertas.inscripciones.edit', '', array($oferta->id, $inscripcion->id), array('class' => 'btn btn-info glyphicon glyphicon-edit', 'title'=>'Editar datos del inscripto')) }}
                            <!-- <a href="{{route('ofertas.inscripciones.imprimir', [$oferta->id, $inscripcion->id])}}" class="btn btn-default" title="Imprimir formulario de inscripcion"><i class="fa fa-file-pdf-o"></i></a> -->
                            @if($perfil != "Colaborador")
                                {{ Form::open(array('class' => 'confirm-delete', 'style' => 'display: inline-block;', 'method' => 'DELETE', 'route' => array('ofertas.inscripciones.destroy', $oferta->id, $inscripcion->id))) }}
                                    {{ Form::submit('Borrar', array('class' => 'btn btn-danger','title'=>'Eliminar Inscripto')) }}
                                {{ Form::close() }}
                            @endif
                        </td>
                    </tr>                  
                    <?php $i++;?>
		@endforeach
		</tbody>
	</table>
        <br>
    @else
        <br>
        <h2>Aún no hay inscriptos en esta oferta.</h2>
        <p><a href="{{ URL::action('ofertas.inscripciones.create', $oferta->id) }}" class="btn-btn-link">Formulario de inscripción</a> | <a href="{{ URL::route('ofertas.index') }}">Lista de ofertas</a></p>
    @endif
    <div id="fondo">
        <a class='btn btn-primary' href="{{ URL::route('ofertas.index') }}" title="Volver al listado de Ofertas" >Volver</a>
    </div>
</div>
@stop