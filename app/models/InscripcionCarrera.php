<?php

class InscripcionCarrera extends Eloquent {
    protected $guarded = array();

    protected $table = 'inscripcion_carrera';
    protected $dates = array('fecha_nacimiento');
    public $timestamps = false;

    public static $rules = array(
        'oferta_formativa_id'   => array('required', 'exists:oferta_formativa,id', 'unique_persona' => 'unique_with:inscripcion_oferta,tipo_documento_cod,documento'),
        'tipo_documento_cod' => 'required|exists:repo_tipo_documento,tipo_documento',
        'documento' => 'required|integer|min:1000000|max:99999999',
        'apellido' => 'required',
        'nombre' => 'required',
        'sexo'   => 'required|in:M,F',
        'fecha_nacimiento' => 'required|date_format:d/m/Y',
        'nacionalidad_id'  => 'required|exists:nacionalidad,id',
        'localidad_id' => 'required|exists:repo_localidad,id',
        'localidad_depto' => 'required',
        'localidad_pcia'  => 'required|exists:repo_provincia,id',
        'localidad_pais'  => 'required|exists:repo_pais,id',
        
        'domicilio_procedencia_tipo'  => 'required|in:CASA,DEPTO,PENSION,RESIDENCIA',
        'domicilio_procedencia_calle' => 'required',
        'domicilio_procedencia_nro'   => 'required',
        'domicilio_procedencia_piso'  => 'required',
        'domicilio_procedencia_depto' => 'required',
        'domicilio_procedencia_localidad_id' => 'required|exists:repo_localidad,id',
        'domicilio_procedencia_cp'   => 'required',
        'domicilio_procedencia_pais_id'   => 'required|exists:repo_pais,id',
        'domicilio_procedencia_telefono_fijo'   => 'required',
        'domicilio_procedencia_telefono_celular'   => 'required',
        'domicilio_procedencia_email'   => 'required|email',
        'domicilio_procedencia_cp'   => 'required',
        
        'domicilio_clases_tipo'  => 'required|in:CASA,DEPTO,PENSION,RESIDENCIA',
        'domicilio_clases_calle' => 'required',
        'domicilio_clases_nro'   => 'required',
        'domicilio_clases_piso'  => 'required',
        'domicilio_clases_depto' => 'required',
        'domicilio_clases_localidad_id' => 'required|exists:repo_localidad,id',
        'domicilio_clases_cp'   => 'required',
        'domicilio_clases_pais_id'   => 'required|exists:repo_pais,id',
        'domicilio_clases_telefono_fijo'   => 'required',
        'domicilio_clases_telefono_celular'   => 'required',
        'domicilio_clases_email'   => 'required|email',
        'domicilio_clases_cp'   => 'required',
        'domicilio_clases_con_quien_vive_id'   => 'required|exists:con_quien_vive,id',
        
        'secundario_titulo_obtenido' => 'required',
        'secundario_anio_egreso' => 'required|digits:4|min:1900',
        'secundario_nombre_colegio' => 'required',
        'secundario_localidad_colegio' => 'required|exists:repo_localidad,id',
        'secundario_pcia'  => 'required|exists:repo_provincia,id',
        'secundario_pais'  => 'required|exists:repo_pais,id',
        'secundario_tipo_establecimiento' => 'required|in:ESTATAL,PRIVADO',
        
        'situacion_laboral' => 'required|in:TRABAJA,NO TRABAJA,DESOCUPADO',
        'situacion_laboral_ocupacion' => 'required|in:TEMPORAL,PERMANENTE',
        'situacion_laboral_relacion_trabajo_carrera' => 'required|in:TOTAL,PARCIAL,NINGUNA',
        'situacion_laboral_categoria_ocupacional_id' => 'required|exists:categoria_ocupacional,id',
        'situacion_laboral_detalle_labor' => 'required',
        'situacion_laboral_horas_semana' => 'required|in:MENOS DE 20,ENTRE 21 Y 35,36 O MAS',
        'situacion_laboral_rama_id' => 'required|exists:rama_actividad_laboral,id',
        
        
        'padre_apeynom' => 'required',
        'padre_vive' => 'required|in:SI,NO,NS/NC',
        'padre_estudios_id' => 'required|exists:nivel_estudios,id',
        'padre_categoria_ocupacional_id' => 'required|exists:categoria_ocupacional,id',
        'padre_labor' => 'required',
        'padre_ocupacion' => 'required|in:PERMANENTE,TEMPORARIA',
        
        'madre_apeynom' => 'required',
        'madre_vive' => 'required|in:SI,NO,NS/NC',
        'madre_estudios_id' => 'required|exists:nivel_estudios,id',
        'madre_categoria_ocupacional_id' => 'required|exists:categoria_ocupacional,id',
        'madre_labor' => 'required',
        'madre_ocupacion' => 'required|in:PERMANENTE,TEMPORARIA'
        
    );
    
    public static $enum_tipo_residencia      = array('1' => 'Casa', '2' => 'Depto.', '3' => 'Pensión', '4' => 'Residencia');
    public static $enum_tipo_establecimiento = array('1' => 'Estatal', '2' => 'Privado');
    public static $enum_situacion_laboral    = array('1' => 'Trabaja', '2' => 'No trabaja', '3' => 'Desocupado');
    public static $enum_situacion_laboral_ocupacion  = array('1' => 'Trabajo temporal', '2' => 'Trabajo permanente');
    public static $enum_situacion_laboral_horas_semana = array('1' => 'Menos de 20', '2' => 'Entre 21 y 35', '36 o más');
    public static $enum_situacion_laboral_relacion_trabajo_carrera = array('1' => 'Total', '2' => 'Parcial', '3' => 'Ninguna');
    public static $enum_vive = array('1' => 'SI', '2' => 'NO', '3' => 'NS/NC');
    
    public function oferta()
    {
        return $this->belongsTo('Oferta', 'oferta_formativa_id');
    }
    
    public function requisitospresentados()
    {
        return $this->morphMany('RequisitoPresentado', 'inscripto');
    }
    
    public function tipo_documento()
    {
        return $this->belongsTo('TipoDocumento', 'tipo_documento', 'tipo_documento_cod');
    }
    
    public function localidad()
    {
        return $this->belongsTo('Localidad', 'localidad_id');
    }

    public function nivel_estudios()
    {
        return $this->belongsTo('NivelEstudios', 'nivel_estudios_id');
    }

    public function rel_como_te_enteraste()
    {
        return $this->belongsTo('InscripcionComoTeEnteraste', 'como_te_enteraste');
    }
    
    public function getColumnasCSV()
    {
        return [ 'documento', 'apellido', 'nombre', 'fecha_nacimiento', 'localidad', 'email', 'telefono' ];
    }
    
    public function toCSV()
    {
        $data = [
            'documento'         => $this->documento,
            'apellido'          => $this->apellido,
            'nombre'            => $this->nombre,
            'fecha_nacimiento'  => $this->fecha_nacimiento,
            'localidad'         => $this->localidad->localidad,
            'email'             => $this->email,
            'telefono'          => $this->telefono
        ];
        
        $ftemp = fopen('php://temp', 'r+');
        fputcsv($ftemp, $data, ',', "'");
        rewind($ftemp);
        $fila = fread($ftemp, 1048576);
        fclose($ftemp);
        
        return $fila;
    }
    
    public function getTipoydocAttribute()
    {
        return sprintf("%s %s", $this->tipo_documento, number_format($this->documento, 0, ",", "."));
    }
    
    public function getInscriptoAttribute()
    {
        return sprintf("%s %s", $this->apellido, $this->nombre);
    }

    public function getFechaNacimientoAttribute($fecha)
    {
        return \Carbon\Carbon::parse($fecha)->format('d/m/Y');
    }

    public function setFechaNacimientoAttribute($fecha)
    {
        $this->attributes['fecha_nacimiento'] = ModelHelper::getFechaISO($fecha);
    }
    
    
    public static function boot()
    {
        parent::boot();

        Inscripcion::created(function($inscripcion){
            $inscripcion->oferta->chequearDisponibilidad();
        });

        Inscripcion::updated(function($inscripcion){
            $inscripcion->oferta->chequearDisponibilidad();
        });

        Inscripcion::deleted(function($inscripcion){
            $inscripcion->oferta->chequearDisponibilidad();
        });
    }
}
