<!DOCTYPE html>
<html lang="es-AR">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <style>
            body {
                //border: 1px solid red;
                margin: -30px;
                width: 100%;
                height: 760px;
                font-family: "Segoe UI" !Important;
            }
            .certificado{
                //border: 1px solid black;
                //font-family: "Segoe UI" !Important;
                width: 1085px;
                height: 735px;
                position: relative;
            }
            #textoCertificado{
                //border: solid 2px green;
                position: absolute;
                width: 100%;
                top: 140px;
                color: black;
                text-align: center;
                //font-family: "Segoe UI" !Important;
                font-size: 16pt;
            }
            #cuv{
                position: absolute;
                top: 680px;
                left: 100px;
                font-size: 10pt !important;
                text-align: left;
            }
            #cuvhelp{
                position: absolute;
                top: 700px;
                left: 100px;
                font-size: 10pt !important;
                text-align: left;
            }
            #cuvqr{
                position: absolute;
                top: 650px;                
                text-align: right;
                right: 50px;
                width: 100%;
            }
        </style>
    </head>
<body>
    <?php
        //$rows son los datos de la Oferta, Ej.: $rows->nombre;
        $cap = Session::get('cap');
        $capacRol = RolCapacitador::find($cap->rol_id);
        $capacPersonal = Personal::find($cap->personal_id);
        //guardo en un array todos los meses - sirve para luego buscar el mes actual en string
        $meses = array('01' => 'Enero','02' => 'Febrero','03' => 'Marzo','04' => 'Abril',
                '05' => 'Mayo','06' => 'Junio','07' => 'Julio','08' => 'Agosto',
                '09' => 'Septiembre','10' => 'Octubre','11' => 'Noviembre','12' => 'Diciembre',);
        $aux = array_get($meses, date('m'));
        //código para generar la imagen del código QR se guarda en public/images/qrcodes
        $renderer = new \BaconQrCode\Renderer\Image\Png();
        $renderer->setHeight(256);
        $renderer->setWidth(256);
        $writer = new \BaconQrCode\Writer($renderer);       
        $filename = $rows->id; $filename .= $capacPersonal->id; $filename .= ".png";
        $mje = "http://udc.edu.ar/verificacion-de-certificado?cuv=";
        $writer->writeFile($mje,$filename);
    ?>  
    
    <div class="certificado">
        <img src="{{ asset($rows->cert_base_cap->url()) }}" alt="Certificado base" style="width: 1085px;height: 760px;"/>
        <div id='textoCertificado'>
            <p>La UNIVERSIDAD DEL CHUBUT certifica que</p>
            <p><span><?php echo strtoupper($capacPersonal->apellido).", ".$capacPersonal->nombre;?></span></p>
            <p>D.N.I. <span><?php echo number_format($capacPersonal->dni, 0, ',', '.');?>,</span></p>
            <p>ha participado en calidad de <?php echo strtolower($capacRol->rol);?>, en </p>
            <p><span><?php echo strtoupper($rows->nombre);?></span></p>
            <p>según Resolución Rectoral N° <span><?php echo $rows->resolucion_nro;?></span>, con una acreditación de 
                <span><?php echo $rows->duracion_hs;?> horas reloj.</span></p>            
            <p>Se extiende el presente certificado a los 
                <span><?php echo date('d')?></span> días del mes de 
                <span><?php echo strtoupper($aux) ?></span> de 2016</p>
            <p>en la ciudad de Rawson, Provincia del Chubut.</p>            
        </div>
            <p id="cuv">Código Único de Verificación (CUV): <span><?php echo $rows->codigo_verificacion ?></span></p>
            <p id="cuvhelp">Para verificar el certificado accedé a http://udc.edu.ar/verificacion-de-certificados o escaneá el código QR con tu celular</p>            
            <div id='cuvqr'><img src="<?php echo $filename ?>" alt="Código QR" style="width: 100px;height: 100px;"/></div>
    </div>
    
    <!--<div class="certificado">
        <img src="{{ asset($rows->cert_base_cap->url()) }}" alt="Certificado base" style="width: 1085px;height: 735px;"/>
        <p id="nombreCapacitador"><span><?php //echo $capacPersonal->nombre.", ".$capacPersonal->apellido; ?></span></p>
        <p id="dniCapacitador"><span><?php //echo number_format($capacPersonal->dni, 0, ',', '.');?></span></p>
        <p id="rolCapacitador"><span><?php //echo strtolower($capacRol->rol);?></span></p>
        <p id="nombreOferta"><span><?php //echo strtoupper($rows->nombre);?></span></p>
        <p id="resolucion"><?php //echo $rows->resolucion_nro;?></p>
        <p id="cantidadHorasReloj"><?php //echo $rows->duracion_hs;?></p>
        <p id="diaHoy"><?php //echo date('d')?></p>
        <p id="mesHoy"><?php //echo strtoupper($aux) ?></p>
    </div> -->
    
</body>
</html>