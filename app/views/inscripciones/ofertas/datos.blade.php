<br>
@if (count($preinscripciones))
  <fieldset>
    <div id="datosPreinscriptos">
        <input class="search" placeholder="Buscar por Nro. o Apellido" id="inputBuscar" onchange="verificarListaCompleta()"/>
        <button class="sort" data-sort="nrodat" >Por Nro.</button>
        <button class="sort" data-sort="apellidodat" >Por Apellido</button>
	<table class="table" style="border-top: 2px black solid; border-bottom: 2px black solid">
            <thead>
                <tr>
                    <th>Nro.</th>
                    <th>Apellidos y Nombres</th>
                    @if($perfil != "Colaborador")
                        <th>Documento</th>
                    @endif
                    <th>Localidad</th>
                    <th>E-mails</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody class="list">
                   @foreach ($preinscripciones as $inscripcion)
                    <tr>
                        <td class="nrodat">{{ $inscripcion->id }}</td>
                        <td class="apellidodat">{{ $inscripcion->apellido }}, {{ $inscripcion->nombre }}</td>
                        @if($perfil != "Colaborador")
                            <td>{{ $inscripcion->tipoydoc }}</td>
                        @endif
                        <td>{{ $inscripcion->localidad->la_localidad }}</td>
                        <td>
                            <p>{{ $inscripcion->email }}</p>
                            @if($perfil != "Colaborador")
                                <p>{{ $inscripcion->email_institucional }}</p>
                            @endif
                        </td>
                        <td>
                            {{ link_to_route('ofertas.inscripciones.edit', '', array($oferta->id, $inscripcion->id), array('class' => 'btn btn-xs btn-info glyphicon glyphicon-edit', 'title'=>'Editar datos del inscripto')) }}
                            @if($perfil != "Colaborador")
                                {{ Form::open(array('id'=>'formBorrarInscripto','class' => 'confirm-delete', 'style' => 'display: inline-block;', 'method' => 'DELETE', 'route' => array('ofertas.inscripciones.destroy', $oferta->id, $inscripcion->id))) }}
                                    {{ Form::submit('Borrar', array('class' => 'btn btn-xs btn-danger','title'=>'Eliminar Inscripto')) }}
                                {{ Form::close() }}
                            @endif
                        </td>
                    </tr>                    
		@endforeach
		</tbody>
	</table>
    </div>
  </fieldset>
@else
    <h2>Aún no hay inscriptos en esta oferta.</h2>
    <p><a href="{{ URL::action('ofertas.inscripciones.create', $oferta->id) }}" class="btn-btn-link">Formulario de inscripción</a> | <a href="{{ URL::route('ofertas.index') }}">Lista de ofertas</a></p>
@endif

<script>
    var options = {
      valueNames: [ 'apellidodat', 'nrodat' ]
    };
    var datosPreinscriptosList = new List('datosPreinscriptos', options);
    
</script>