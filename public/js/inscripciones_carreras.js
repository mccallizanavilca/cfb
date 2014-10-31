var InscripcionesCarrerasModule = {
    init: function(oferta_id)
    {
        var self = this;
        self.oferta_id = oferta_id;
        
        self.initIDs();
        self.initDomicilioProcedencia();
        self.initSituacionLaboral();
        self.initDatosPadre();
        self.initDatosMadre();
    },
    
    initIDs: function() {   
        //a cada control le pongo como id su name (a la sf)
        $('input[type=text], input[type=submit], input[type=radio], input[type=checkbox]), select, textarea').each(function(){
          $(this).prop("id", $(this).prop('name'));
        });
        
        //a cada radio o checkbox le pongo como id: name_value (a la sf)
        $('input[type=radio], input[type=checkbox]').each(function(){
          var value = $(this).attr("value").replace(' ','_').replace('/','_');
          
        $(this).attr("id", $(this).attr('name')+'_'+value);
      });
    },
    
    initDomicilioProcedencia: function() {
        var $filas = $("table.domicilio_clases tr.opcional");
        
        $('#domicilio_clases_igual_1').change(function(){
            if (!$(this).prop('checked')) {
                //muestro las filas de la table
                $filas.show(0.5);
            } else {
                //no deshabilito, simplemente copio los valores
                $.each(["calle", "nro", "piso", "depto", "localidad_id", "localidad_otra", "cp", "pcia_id", "pais_id", "pais_otro"], (function(i, item){
                    var $origen = $("#domicilio_procedencia_"+item);
                    var $destino = $("#domicilio_clases_"+item);
                    
                    $destino.val($origen.val());
                }));
                
                //oculto las filas de la table
                $filas.hide(0.5);
            }
        });
    },
    
    initSituacionLaboral: function() {
        var $filas = $("table.situacion_laboral tr.opcional");
        
        $('#situacion_laboral_TRABAJA, #situacion_laboral_DESOCUPADO').click(function(){
            //habilito los controles
            $filas.find("input[type=radio], textarea").prop("required", "required").prop("disabled", null);
            //muestro las filas de la table
            $filas.show(0.5);
        });
        
        $('#situacion_laboral_NO_TRABAJA').click(function(){
            //deshabilito y limpio los controles
            $filas.find("input[type=radio]").prop("checked", null);
            $filas.find("input[type=radio], textarea").prop("required", null).prop("disabled", "disabled");
            //limpio los controles
            $filas.find("select, textarea").val("");
            //oculto las filas de la table
            $filas.hide(0.5);
        });
    },
    
    initDatosPadre: function() {
        var $filas = $("table.datos_padre tr.opcional");
        
        $('#padre_vive_SI').click(function(){
            //habilito los controles
            $filas.find("input[type=radio], textarea").prop("required", "required").prop("disabled", null);
            //muestro las filas de la table
            $filas.show(0.5);
        });
        
        $('#padre_vive_NO, #padre_vive_NS_NC').click(function(){
            //deshabilito y limpio los controles
            $filas.find("input[type=radio]").prop("checked", null);
            $filas.find("input[type=radio], textarea").prop("required", null).prop("disabled", "disabled");
            //limpio los controles
            $filas.find("select, textarea").val("");
            //oculto las filas de la table
            $filas.hide(0.5);
        });
    },
    
    initDatosMadre: function() {
        var $filas = $("table.datos_madre tr.opcional");
        
        $('#madre_vive_SI').click(function(){
            //habilito los controles
            $filas.find("input[type=radio], textarea").prop("required", "required").prop("disabled", null);
            //muestro las filas de la table
            $filas.show(0.5);
        });
        
        $('#madre_vive_NO, #padre_vive_NS_NC').click(function(){
            //deshabilito y limpio los controles
            $filas.find("input[type=radio]").prop("checked", null);
            $filas.find("input[type=radio], textarea").prop("required", null).prop("disabled", "disabled");
            //limpio los controles
            $filas.find("select, textarea").val("");
            //oculto las filas de la table
            $filas.hide(0.5);
        });
    }
    

};