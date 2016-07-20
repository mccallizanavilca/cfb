@if(count($preinscripciones))
    <div class="divTotales">
        <div><h4>Total: {{ count($preinscripciones) }}</h4></div>
        <div> (
            <a href="{{ URL::Route('ofertas.inscripciones.index', array('oferta_id' => $oferta->id, 'exp' => 'xlsp')) }}" target="_blank" title="Exportar listado de todos los Pre-Inscriptos a Excel"><i class="fa fa-file-excel-o fa-3"></i></a>
            <a href="{{ URL::Route('ofertas.inscripciones.index', array('oferta_id' => $oferta->id, 'exp' => 'pdfp')) }}" target="_blank" title="Exportar listado de todos los Pre-inscriptos a PDF"><i class="fa fa-file-pdf-o fa-3"></i></a>
         )</div>
    </div>
@endif
    @if (count($preinscripciones))
    <div id="preinscriptos">
        <input class="search" placeholder="Buscar por Nro. o Apellido" id="inputBuscarCarrPrinscrIndex" onchange="verificarListaCompleta('inputBuscarCarrPrinscrIndex','btnSubmitFormCarrPreinscrIndex')"/>
        <button class="sort" data-sort="nro" >Por Nro.</button>
        <button class="sort" data-sort="apellido" >Por Apellido</button>
        <?php $listaIdPreinscriptos = array();?>
        {{ Form::open(array(
                    'method' => 'POST',
                    'action' => array('OfertasInscripcionesController@cambiarInscripciones', $oferta->id))) }}    
	<table class="table table-striped" style="border-top: 2px black solid; border-bottom: 2px black solid">
            <thead>
                <tr>
                    <th>Nro.</th>
                    <th>Apellidos</th>
                    <!-- <th>Nombre</th> -->
                    @if($perfil != "Colaborador")
                        <th>Documento</th>
                    @endif
                    <th>Localidad</th>
                    <th>Correos</th>
                    @if(!$oferta->estaFinalizada())
                        <!--<th>Email UDC</th>-->
                        <th>Inscripto ({{ count($inscriptos) }})</th>
                        <th>Notificado/a</th>
                    @endif
                    <!--<th>Acciones</th>-->
                </tr>
            </thead>
            <tbody class="list">
               <?php $i = 1; ?>
               @foreach ($preinscripciones as $inscripcion)
                    <?php $listaIdPreinscriptos[] = $inscripcion->id; ?>
                    <tr>
                        <td class="nro">{{ $inscripcion->id }}</td>
                        <td class="apellido">{{ $inscripcion->apellido }}, {{ $inscripcion->nombre }}</td>
                        <!-- <td>{{ $inscripcion->nombre }}</td> -->
                        @if($perfil != "Colaborador")
                            <td>{{{ $inscripcion->tipoydoc }}}</td>
                        @endif
                        <td>{{{ $inscripcion->localidad->la_localidad }}}</td>
                        <td>
                            <p>{{ $inscripcion->email }}</p>
                            <p style="color: blue">{{ $inscripcion->email_institucional }}</p>
                        </td>
                        @if(!$oferta->estaFinalizada())
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
                        <!--<td>
                            {{ link_to_route('ofertas.inscripciones.edit', '', array($oferta->id, $inscripcion->id), array('class' => 'btn btn-xs btn-info glyphicon glyphicon-edit', 'title'=>'Editar datos del inscripto')) }}
                            <a href="{{route('ofertas.inscripciones.imprimir', [$oferta->id, $inscripcion->id])}}" class="btn btn-xs btn-default" title="Imprimir formulario de inscripcion"><i class="fa fa-file-pdf-o"></i></a>
                            @if($perfil != "Colaborador")
                                {{ Form::open(array('class' => 'confirm-delete', 'style' => 'display: inline-block;', 'method' => 'DELETE', 'route' => array('ofertas.inscripciones.destroy', $oferta->id, $inscripcion->id))) }}
                                    {{ Form::submit('Borrar', array('class' => 'btn btn-xs btn-danger','title'=>'Eliminar Inscripto')) }}
                                {{ Form::close() }}
                            @endif
                        </td>-->
                    </tr>
                    <?php $i++;?>
                @endforeach
		</tbody>
	</table>
        <?php $listaEnString = implode('-',$listaIdPreinscriptos); ?>
        <input type="hidden" id="listaIdPreinscriptos" name="listaIdPreinscriptos" value="<?php echo $listaEnString ?>">
        @if(!$oferta->estaFinalizada())
            {{ Form::submit('Guardar cambios', array('class' => 'btn btn-success', 'style'=>'float: right', 'title'=>'Guardar cambios.', 'id'=>'btnSubmitFormCarrPreinscrIndex')) }}
            {{ Form::reset('Descartar cambios', ['class' => 'form-button btn btn-warning', 'style'=>'float: right' ])}}
            {{ Form::close() }}
        @endif
    </div>
    @else
        <br>
        <h2>Aún no hay inscriptos en esta oferta.</h2>
        <p><a href="{{ URL::action('ofertas.inscripciones.create', $oferta->id) }}" class="btn-btn-link">Formulario de inscripción</a> | <a href="{{ URL::route('ofertas.index') }}">Lista de ofertas</a></p>
    @endif
    
<script>
    var options = {
      valueNames: [ 'apellido', 'nro' ]
    };

    var preinscriptosList = new List('preinscriptos', options);
</script>
