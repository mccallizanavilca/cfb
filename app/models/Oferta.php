<?php

use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;

class Oferta extends Eloquent implements StaplerableInterface {
    use EloquentTrait;

	protected $guarded  = array();
            
    protected $table = 'oferta_formativa';
    protected $dates = array('inicio', 'fin');
    public $timestamps = false;

	public static $rules = array(
		'nombre'        => 'required',
		'anio'          => 'required|digits:4',
        'inicio'        => 'date_format:d/m/Y',
        'fin'           => 'date_format:d/m/Y',
        'cupo_maximo'   => 'integer|min:0',
        'tipo_oferta'   => 'required|exists:tipo_oferta_formativa,id',
	);

    public function __construct(array $attributes = array()) {
        $this->hasAttachedFile('mail_bienvenida');

        parent::__construct($attributes);
    }
        
    public function inscripciones()
    {
        return $this
            ->hasMany('Inscripcion', 'oferta_formativa_id')
            ->with('localidad', 'rel_como_te_enteraste')
            ->orderBy('apellido')
            ->orderBy('nombre');
    }
    
    public function requisitos()
    {
        return $this->hasMany('Requisito', 'oferta_id');
    }

    public function tipo_oferta_formativa()
    {
        return $this->belongsTo('TipoOferta', 'tipo_oferta');
    }
    
    public function getInscriptosAttribute()
    {
        return $this->inscripciones->count();
    }
    
    public function getInicioAttribute($fecha)
    {
        return \Carbon\Carbon::parse($fecha)->format('d/m/Y');
    }
    
    public function setInicioAttribute($fecha)
    {
         $this->attributes['inicio'] = ModelHelper::getFechaISO($fecha);
    }
    
    public function getFinAttribute($fecha)
    {
        return \Carbon\Carbon::parse($fecha)->format('d/m/Y');
    }
    
    public function setFinAttribute($fecha)
    {
         $this->attributes['fin'] = ModelHelper::getFechaISO($fecha);
    }
         
    public function getPermiteInscripcionesAttribute()
    {
        return ModelHelper::trueOrNull($this->attributes['permite_inscripciones']);
    }

    public function chequearDisponibilidad()
    {
        if(is_null($this->cupo_maximo) || $this->cupo_maximo == 0) return;
        
        if($this->inscripciones->count() >= (int)$this->cupo_maximo)
        {
            $this->permite_inscripciones = false;
            $this->save();
        }    
    }

    public function getVistaMail()
    {
        return empty($this->mail_bienvenida_file_name) ? 'emails.ofertas.bienvenida_generico' : 'emails.ofertas.bienvenida_oferta';
    }
        
}
