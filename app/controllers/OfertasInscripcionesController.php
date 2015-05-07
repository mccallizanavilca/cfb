<?php

class OfertasInscripcionesController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($oferta_id) {
        //busco la oferta en la BD
        $oferta = Oferta::findOrFail($oferta_id);
        //me fijo si viene con un parametro mas en la url
        $exp = Request::get('exp');
        
        //traigo todos (pre-inscriptos e inscriptos) para ver en la vista
        $inscripciones = $oferta->inscripciones->all();
        
        //Busco el usuario actual en la BD
        $userName = Auth::user()->username;
        $NomYApe = Auth::user()->nombreyapellido;
        $perfil = Auth::user()->perfil;
        $userId = Auth::user()->id;

        if (!empty($exp)) {
            switch ($exp) {
                case parent::EXPORT_XLSP:
                    //traigo solos los preinscriptos para exportar a excel
                    $inscripciones = $oferta->preinscriptosOferta->all();
                    return $this->exportarXLS($oferta->nombre."_preinscriptos", $inscripciones, 'inscripciones.'.$oferta->view.'.excel');
                case parent::EXPORT_XLSI:
                    //traigo solos los inscriptos para exportar a excel
                    $inscripciones = $oferta->inscriptosOferta->all();
                    return $this->exportarXLS($oferta->nombre."_inscriptos", $inscripciones, 'inscripciones.'.$oferta->view.'.excel');
                case parent::EXPORT_PDFP:
                    //traigo solos los preinscriptos para exportar a pdf
                    $inscripciones = $oferta->preinscriptosOferta->all();
                    return $this->exportarPDF($oferta->nombre."_preinscriptos", $inscripciones, 'inscripciones.'.$oferta->view.'.excel');
                case parent::EXPORT_PDFI:
                    //traigo solos los inscriptos para exportar a pdf
                    $inscripciones = $oferta->inscriptosOferta->all();
                    return $this->exportarPDF($oferta->nombre."_inscriptos", $inscripciones, 'inscripciones.'.$oferta->view.'.excel');
                case parent::EXPORT_CSV:
                    //traigo solos los inscriptos para exportar a cvs
                    $inscripciones = $oferta->inscriptosOferta->all();
                    return $this->exportarCSV($oferta->nombre."_inscriptos", $inscripciones, 'inscripciones.'.$oferta->view.'.csv');
            }
        } else {
            return View::make('inscripciones.'.$oferta->view.'.index', compact('inscripciones'))->withoferta($oferta)->with('userName',$userName)->with('nomyape',$NomYApe)->with('perfil',$perfil);
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($oferta_id) {
        $oferta = Oferta::find($oferta_id);
        if (!$oferta) {
            return View::make('errors.oferta_inexistente');
        } elseif(!$oferta->permite_inscripciones) {
            return View::make('inscripciones.cerradas')->withoferta($oferta);
        }

        return View::make('inscripciones.'.$oferta->view.'.create')->withoferta($oferta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($oferta_id) {
        $oferta = Oferta::findOrFail($oferta_id);
        $inscripto = $oferta->inscripcionModel;
        
        $input = Input::all();
        
        $input_db = Input::except($inscripto::$rules_virtual);

        $validation = $inscripto->validarNuevo($input);

        if ($validation->passes()) {
            
            $insc = $inscripto->create($input_db);

            try {
                Mail::send($oferta->getVistaMail(), compact('oferta'), function($message) use($oferta, $insc) {
                    $message
                            ->to($insc->correo, $insc->inscripto)
                            ->subject('UDC:: Recibimos tu inscripción a ' . $oferta->nombre);
                });
            } catch (Swift_TransportException $e) {
                Log::info("No se pudo enviar correo a " . $insc->inscripto . " <" . $insc->correo . ">");
            }

            return Redirect::to('/inscripcion_ok');
        }
        //dd($validation);
        return Redirect::route('ofertas.inscripciones.nueva', $oferta_id)
                        ->withOferta($oferta)
                        ->withInput()
                        ->withErrors($validation)
                        ->with('message', 'Error al guardar.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($oferta_id, $id) {
        $oferta = Oferta::findorFail($oferta_id);
        $insc_class = $oferta->inscripcionModelClass;
        $inscripcion = $insc_class::findOrFail($id);

        if (is_null($inscripcion)) {
            return Redirect::route('ofertas.inscripciones.index');
        }

        $requisitos = $oferta->requisitos;

        $presentados = $inscripcion->requisitospresentados;

        return View::make('inscripciones.'.$oferta->view.'.edit', compact('inscripcion', 'oferta', 'requisitos', 'presentados'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($oferta_id, $id) {
        $oferta = Oferta::findorFail($oferta_id);
        $insc_class = $oferta->inscripcionModelClass;
        $inscripcion = $insc_class::findOrFail($id);

        if (is_null($inscripcion)) {
            return Redirect::route('ofertas.inscripciones.index');
        }
        
        $input = Input::all();

        $input_db = Input::except($inscripcion::$rules_virtual);
                
        $validation = $inscripcion->validarExistente($input);

        if ($validation->passes()) {
            $inscripcion->update($input_db);

            return Redirect::route('ofertas.inscripciones.index', array($oferta_id));
        }

        return Redirect::route('ofertas.inscripciones.edit', array($oferta_id, $id))
                        ->withInput()
                        ->withErrors($validation)
                        ->with('message', 'Ocurrieron errores al guardar.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($oferta_id, $id) {
        $oferta = Oferta::findorFail($oferta_id);
        $insc_class = $oferta->inscripcionModelClass;
        $inscripcion = $insc_class::findOrFail($id);
        
        $inscripcion->delete();

        return Redirect::route('ofertas.inscripciones.index', array($oferta->id))
                        ->withoferta($oferta)
                        ->with('message', 'Se eliminó el registro correctamente.');
    }

    /**
     * Guarda la presentación de un requisito en la inscripción
     *
     * @return Response
     */
    public function presentarRequisito($oferta_id, $id) {
        $oferta = Oferta::findorFail($oferta_id);
        $insc_class = $oferta->inscripcionModelClass;
        $inscripcion = $insc_class::findOrFail($id);
        
        $input = Input::all();
        $reglas = RequisitoPresentado::$rules;

        $input['inscripto_id']   = $inscripcion->id;
        $input['inscripto_type'] = $insc_class;

        $validation = Validator::make($input, $reglas);

        if ($validation->passes()) {
            $requisito = RequisitoPresentado::create($input);
            $presentados = $inscripcion->requisitospresentados;
            
            return View::make('requisitos.itempresentado', compact('oferta', 'requerimiento', 'inscripcion', 'presentados', 'requisito'));
        } else {
            return Response::json(array('error' => 'Error al guardar', 'messages' => $validation->messages()), 400);
        }
    }

    public function borrarRequisito($oferta_id, $inscripto_id, $requisito_id) {
        $req =  RequisitoPresentado::findOrFail($requisito_id);
        $req->delete();

        return Response::make('', 200);
    }
    
    public function imprimir($oferta_id, $id)
    {
        $oferta = Oferta::findorFail($oferta_id);
        $insc_class = $oferta->inscripcionModelClass;
        $inscripcion = $insc_class::find($id);

        if (is_null($inscripcion)) {
            return Redirect::route('ofertas.inscripciones.index');
        }
        
        $archivo = sprintf("inscrip_%s_%s", $inscripcion->apellido, $oferta->nombre);
        
        return View::make('inscripciones.carreras.form_pdf', compact('inscripcion', 'oferta'));
        //return $this->exportarFormPDF($archivo , compact('inscripcion', 'oferta'), 'inscripciones.'.$oferta->view.'.form_pdf');
    }
    
    public function cambiarEstado($oferta_id, $id) {
        //busco el inscripto ($id) segun la oferta ($oferta_id)
        $oferta = Oferta::findorFail($oferta_id);
        $insc_class = $oferta->inscripcionModelClass;
        $inscripcion = $insc_class::findOrFail($id);
               
        if($inscripcion->getEsInscripto()){
            $inscripcion->setEstadoInscripcion(0);
            $inscripcion->vaciarCorreoInstitucional();
            $inscripcion->save();
        }else{            
            $inscripcion->setEstadoInscripcion(1);
            $inscripcion->crearCorreoInstitucional();
            $inscripcion->save();
        }
        
        return Redirect::route('ofertas.inscripciones.index', array($oferta_id));
    }
    
    public function enviarMailInstitucional($oferta_id, $id){
        //busco el inscripto ($id) segun la oferta ($oferta_id)
        $oferta = Oferta::findorFail($oferta_id);
        $insc_class = $oferta->inscripcionModelClass;
        $inscripcion = $insc_class::findOrFail($id);
        
        
        //########################################################################
        //$oferta = Oferta::findOrFail($oferta_id);
        //$inscripto = $oferta->inscripcionModel;
        
        //$input = Input::all();
        
        //$input_db = Input::except($inscripto::$rules_virtual);

        //$validation = $inscripto->validarNuevo($input);

        //if ($validation->passes()) {
            
            //$insc = $inscripto->create($input_db);        

            try {
                Mail::send('emails.ofertas.notificacion_correo_udc', compact('inscripcion'), function($message) use($inscripcion){
                    $message
                            ->to($inscripcion->email, $inscripcion->apellido.','.$inscripcion->nombre)
                            ->subject('Correo Institucional creado');
                });
            } catch (Swift_TransportException $e) {
                Log::info("No se pudo enviar correo a " . $inscripcion->apellido.','.$inscripcion->nombre." <" . $inscripcion->email.">");
            }

            //return Redirect::to('/ofertas');
        //}
        
                
            // incremento la cantidad de veces que se notifico al inscripto
            $inscripcion->seEnvioNotificacion();
            
            return Redirect::route('ofertas.inscripciones.index', array($oferta_id));
    }
}
