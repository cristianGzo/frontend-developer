<?php

require('./fpdf.php');

class PDF extends FPDF
{

   // Cabecera de página
   function Header()
   {
      require_once '../../models/conexion.php';//llamamos a la conexion BD
      require_once '../../models/historialModel.php';

      $historial = new HistorialModel();
      $historial->obtenerHistorial();

      //$consulta_info = $conexion->query(" select *from hotel ");//traemos datos de la empresa desde BD
      //$dato_info = $consulta_info->fetch_object();
      $this->Image('../../logos/Log.png', 270, 5, 20); //logo de la empresa,moverDerecha,moverAbajo,tamañoIMG
      $this->SetFont('Arial', 'B', 19); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
      $this->Cell(95); // Movernos a la derecha
      $this->SetTextColor(0, 0, 0); //color
      //creamos una celda o fila
      $this->Cell(110, 15, utf8_decode('NOMBRE EMPRESA'), 1, 1, 'C', 0); // AnchoCelda,AltoCelda,titulo,borde(1-0),saltoLinea(1-0),posicion(L-C-R),ColorFondo(1-0)
      $this->Ln(3); // Salto de línea
      $this->SetTextColor(103); //color

      /* UBICACION */
     // $this->Cell(180);  // mover a la derecha
     /* $this->SetFont('Arial', 'B', 10);
      $this->Cell(96, 10, utf8_decode("Ubicación : "), 0, 0, '', 0);
      $this->Ln(5);*/

      /* TELEFONO */
     // $this->Cell(180);  // mover a la derecha
      /*$this->SetFont('Arial', 'B', 10);
      $this->Cell(59, 10, utf8_decode("Teléfono : "), 0, 0, '', 0);
      $this->Ln(5);*/

      /* COREEO */
      //$this->Cell(180);  // mover a la derecha
      /*$this->SetFont('Arial', 'B', 10);
      $this->Cell(85, 10, utf8_decode("Correo : "), 0, 0, '', 0);
      $this->Ln(5);*/

      /* TELEFONO */
      //$this->Cell(180);  // mover a la derecha
      /*$this->SetFont('Arial', 'B', 10);
      $this->Cell(85, 10, utf8_decode("Sucursal : "), 0, 0, '', 0);
      $this->Ln(10);*/

      /* TITULO DE LA TABLA */
      //color
      $this->SetTextColor(228, 100, 0);
      $this->Cell(100); // mover a la derecha
      $this->SetFont('Arial', 'B', 15);
      $this->Cell(100, 10, utf8_decode("REPORTE DE COMPRAS "), 0, 1, 'C', 0);
      $this->Ln(7);

      /* CAMPOS DE LA TABLA */
      //color
      $this->SetFillColor(228, 100, 0); //colorFondo
      $this->SetTextColor(255, 255, 255); //colorTexto
      $this->SetDrawColor(163, 163, 163); //colorBorde
      $this->SetFont('Arial', 'B', 11);
      $this->Cell(30, 10, utf8_decode('Producto'), 1, 0, 'C', 1);
      $this->Cell(40, 10, utf8_decode('Ilustracion'), 1, 0, 'C', 1);
      $this->Cell(40, 10, utf8_decode('Importe'), 1, 0, 'C', 1);
      $this->Cell(40, 10, utf8_decode('Cantidad'), 1, 0, 'C', 1);
      $this->Cell(85, 10, utf8_decode('Fecha'), 1, 0, 'C', 1);
      $this->Cell(40, 10, utf8_decode('Nombre'), 1, 1, 'C', 1);
      
   }

   // Pie de página
   function Footer()
   {
      $this->SetY(-15); // Posición: a 1,5 cm del final
      $this->SetFont('Arial', 'I', 8); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
      $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C'); //pie de pagina(numero de pagina)

      $this->SetY(-15); // Posición: a 1,5 cm del final
      $this->SetFont('Arial', 'I', 8); //tipo fuente, cursiva, tamañoTexto
      $hoy = date('d/m/Y');
      $this->Cell(540, 10, utf8_decode($hoy), 0, 0, 'C'); // pie de pagina(fecha de pagina)
   }
}

require_once '../../models/conexion.php';//llamamos a la conexion BD
require_once '../../models/historialModel.php';

$historial = new HistorialModel();
$lista=$historial->obtenerHistorial();

//include '../../recursos/Recurso_conexion_bd.php';
//require '../../funciones/CortarCadena.php';
/* CONSULTA INFORMACION DEL HOSPEDAJE */
//$consulta_info = $conexion->query(" select *from hotel ");
//$dato_info = $consulta_info->fetch_object();

$pdf = new PDF();
$pdf->AddPage("landscape"); /* aqui entran dos para parametros (horientazion,tamaño)V->portrait H->landscape tamaño (A3.A4.A5.letter.legal) */
$pdf->AliasNbPages(); //muestra la pagina / y total de paginas

$i = 0;
$pdf->SetFont('Arial', '', 12);
$pdf->SetDrawColor(163, 163, 163); //colorBorde

/*$consulta_reporte_alquiler = $conexion->query("  ");*/

/*while ($datos_reporte = $consulta_reporte_alquiler->fetch_object()) {      
   }*/
   foreach($lista as $row){
      $ruta="../images/";
      $rutaImagen=$ruta . $row['ImagenProducto'];
      $i = $i + 1;
/* TABLA */
$pdf->Cell(30, 10, utf8_decode($row['Producto']), 1, 0, 'C', 0);
$pdf->Cell(40, 10, utf8_decode($rutaImagen), 1, 0, 'C', 0);
$pdf->Cell(40, 10, utf8_decode($row['Importe']), 1, 0, 'C', 0);
$pdf->Cell(40, 10, utf8_decode($row['Cantidad']), 1, 0, 'C', 0);
$pdf->Cell(85, 10, utf8_decode($row['Fecha']), 1, 0, 'C', 0);
$pdf->Cell(40, 10, utf8_decode($row['Usuario']), 1, 1, 'C', 0);
  }
$i = $i + 1;
/* TABLA 
$pdf->Cell(30, 10, utf8_decode("N°"), 1, 0, 'C', 0);
$pdf->Cell(40, 10, utf8_decode("cd"), 1, 0, 'C', 0);
$pdf->Cell(40, 10, utf8_decode("nombre"), 1, 0, 'C', 0);
$pdf->Cell(40, 10, utf8_decode("precio"), 1, 0, 'C', 0);
$pdf->Cell(85, 10, utf8_decode("info"), 1, 0, 'C', 0);
$pdf->Cell(40, 10, utf8_decode("total"), 1, 1, 'C', 0);*/


$pdf->Output('Prueba2.pdf', 'I');//nombreDescarga, Visor(I->visualizar - D->descargar)
