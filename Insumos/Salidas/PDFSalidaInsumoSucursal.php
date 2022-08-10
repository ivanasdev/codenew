<?php

	define('FPDF_FONTPATH','../../../../utils/FPDF/font/');
	//require('../../utils/FPDF/fpdf.php');

	require ("../../../../dbConexion.php");
	require ("../../../../utils/FPDF/PDF_MC_Table.php");

	

	$idCabeceraSalida = $_GET['idCabeceraSalida'];

	class PDF extends PDF_MC_Table{
		function Header(){

			$titulo = "DETALLE SALIDA DE INSUMOS";
			$tituloFolio = "FOLIO";
			$idFolio = $_GET['idCabeceraSalida'];

			$this->Image("../../../cedis/images/header.jpg",10,7);			
			$this->Image("../../../cedis/images/selloIso.jpg",158,7);			
			
			$this->SetFont('Arial','','10');
			$this->SetX(70);
			$this->Cell(80,7,$titulo,0,0,'C');
			
			$this->SetFont('Arial','','8');
			$this->Ln();
			$this->SetX(70);
			$this->Cell(80,4,utf8_decode('Cuvier 77, Colonia Anzures'),0,0,'C');

			$this->Ln();
			$this->SetX(70);
			$this->Cell(80,4,utf8_decode('Del. Miguel Hidalgo'),0,0,'C');
			
			$this->Ln();
			$this->SetX(70);
			$this->Cell(80,4,utf8_decode('C.P. 11590 Ciudad de México'),0,0,'C');
				
			$this->SetY(29);
			$this->SetX(150);
			$this->Cell(52,5,$tituloFolio,1,0,'C');
			
			$this->SetFont('Arial','','8');
			$this->Ln();
			$this->SetX(150);
			$this->Cell(52,4,$idFolio,1,0,'C');
			
			$this->SetY(40);


			$quer = "SELECT t2.st_Nombre, t1.st_Observaciones, t1.dt_FechaCierre, 
			UPPER(t3.st_Nombre) as nombreSucursal, UPPER(t4.st_Nombre) as areaInsumo 
			FROM tbl_SUCInsumoSalida t1
			LEFT JOIN tbl_UsuarioSistemaWeb t2 ON t1.id_Operador = t2.id_Operador
			LEFT JOIN cat_SucursalClinica t3 ON t1.id_Sucursal = t3.id_SucursalClinica
			LEFT JOIN cat_AreaInsumos t4 ON t1.id_AreaInsumos = t4.id_AreaInsumos
			WHERE id_CabeceraSalida = '".$_GET['idCabeceraSalida']."'";
			$res = mssql_query($quer);
			$row = mssql_fetch_object($res);

			//$this->Cell(15,5,'PROVEEDOR: ',0,0,'L');
			$this->SetX(130);
			$this->Cell(25,5,'MOVIMIENTO: Salida de Insumos',0,0,'L');

			$this->Ln();

			$this->Cell(15,5,'FECHA: '.$row->dt_FechaCierre,0,0,'L');
			$this->SetX(130);
			$this->Cell(25,5,'SUCURSAL: '.$row->nombreSucursal,0,0,'L');

			$this->Ln();

			$this->Cell(15,5,'AREA DE INSUMO: '.$row->areaInsumo,0,0,'L');
			$this->SetX(130);
			$this->Cell(25,5,'USUARIO: '.$row->st_Nombre,0,0,'L');

			$this->Ln();
			$this->Ln();
		}

		function Footer(){
			 // Go to 1.5 cm from bottom
		    $this->SetY(-15);
		    // Select Arial italic 8
		    $this->SetFont('Arial','I',8);
		    // Print current and total page numbers
		    $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
		}
	
	}

	$pdf = new PDF('P','mm','A4');
	$pdf->AliasNbPages();

	$pdf->SetFont('Arial','B','7');

	$pdf->AddPage();
	$pdf->SetTextColor(255,255,255);
	$pdf->SetFillColor(0, 0, 0);
	
	
	//Definimos el ancho y alineacion de cada columna //190
	$widths = array(25,70,13,13,12,59);
	$aligns = array('C','L','C','C','C','C');
	$pdf->SetWidths($widths);
	$pdf->SetAligns($aligns);

	$pdf->SetFont('Arial','','7');

	$pdf->Cell($widths[0],3,'UPC/C. de Barras',1,0,'C',1);
	$pdf->Cell($widths[1],3,utf8_decode('Descripción'),1,0,'C',1);
	$pdf->Cell($widths[2],3,'Caducidad',1,0,'C',1);
	$pdf->Cell($widths[3],3,'Lote',1,0,'C',1);
	$pdf->Cell($widths[4],3,'Cantidad',1,0,'C',1);	
	$pdf->Cell($widths[5],3,'Motivo Salida',1,0,'C',1);


	
	$pdf->Ln();

	$pdf->SetTextColor(0,0,0);

	$pdf->SetFillColor(255,255,255);

	$queryDatos = "SELECT t1.id_ProductoAlmacen, t1.dt_FechaCaducidad, t1.st_Lote, t1.st_Ubicacion, t1.i_Cantidad, t1.st_Observaciones,
	t2.st_Nombre, t2.st_SA, t2.id_UPC, t1.i_CostoIVA 
	FROM tbl_SUCInsumoSalidaDetalle t1 
	INNER JOIN cat_ProductosAlmacenMaster t2 ON t1.id_ProductoAlmacen = t2.id_ProductoAlmacen
	WHERE t1.id_CabeceraSalida = '".$idCabeceraSalida."'";
	$resDatos = mssql_query($queryDatos);

	$totalCant = 0;
	$totalCostoIVA = 0;
	$totalLinea = 0;
	$totalTotal = 0;

	$pdf->SetFont('Arial','','6');

	while($rowDatos = mssql_fetch_object($resDatos)){

		$idUPC = $rowDatos->id_UPC;
		$descripcion = utf8_decode($rowDatos->st_Nombre);
		$objDateTime = new DateTime($rowDatos->dt_FechaCaducidad);
		$fechacaducidad = $objDateTime->format('Y-m');
		$stLote = utf8_decode($rowDatos->st_Lote);
		$iCantidad = $rowDatos->i_Cantidad;
		$stObservaciones = $rowDatos->st_Observaciones;
		$iCostoIVA = $rowDatos->i_CostoIVA;
		$totalLinea = $iCantidad*$iCostoIVA;
		
		$totalCant = $totalCant + $rowDatos->i_Cantidad;		
		$totalTotal += $totalLinea;
		
				
   		$pdf->Row(
			array(
				$idUPC,
				$descripcion,
				$fechacaducidad,
				$stLote,
				$iCantidad,
				$stObservaciones
			)
		);
	}

	if($pdf->GetY() > 220){
		$pdf->AddPage();	
	}

	$pdf->SetFont('Arial','B','7');
	$pdf->SetTextColor(255,255,255);
	$pdf->SetFillColor(0, 0, 0);
	$pdf->Cell($widths[0]+$widths[1]+$widths[2]+$widths[3],3,utf8_decode('TOTAL'),1,0,'R',1);
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFillColor(255,255,255);

	$pdf->Cell($widths[4],3,number_format($totalCant),1,0,'C',1);
	$pdf->Cell($widths[5],3,'',1,0,'C',1);


	$pdf->Ln();
	$pdf->Ln();
	
	$query0 = "SELECT st_Observaciones FROM tbl_SUCInsumoSalida WHERE id_CabeceraSalida = '".$idCabeceraSalida."'";
	$rquery0 = mssql_query($query0);
	$arrayQuery0 = mssql_fetch_array($rquery0);
	
	$pdf->SetFont('Arial','B','8');
	$pdf->Cell(25,4,"Observaciones: ",0,0,'L');
	$pdf->SetFont('Arial','','8'); 
	$pdf->MultiCell(235,4,$arrayQuery0["st_Observaciones"],0,'L');
	
	
	$pdf->SetFont('Arial','B','7');
	$pdf->Line(20, 247, 80, 247);
	$pdf->SetY(242);
	$pdf->SetX(40);
	$pdf->Cell(20,20,"AUTORIZA (NOMBRE Y FIRMA)",0,0,'C');
	
	$pdf->SetY(225);
	$pdf->SetX(108);
	$pdf->Cell(20,20,"NOMBRE Y FIRMA QUIEN RECIBE:",0,0,'R');
	$pdf->Line(130, 237, 195, 237);
	
	$pdf->SetY(230);
	$pdf->SetX(108);
	$pdf->Cell(20,20,"FECHA RECIBIDO:",0,0,'R');
	$pdf->Line(130, 242, 195, 242);
	
	$pdf->SetY(240);
	$pdf->SetX(108);
	$pdf->Cell(20,20,"OBSERVACIONES:",0,0,'R');
	$pdf->Line(130, 252, 195, 252);
	
	$pdf->SetY(250);
	$pdf->SetX(108);
	$pdf->Cell(20,20,"SELLO RECIBIDO:",0,0,'R');
	

	$pdf->Output('DetalleSalidaInsumo_'.$idCabeceraSalida.'.pdf','D');
	mssql_close();
?>