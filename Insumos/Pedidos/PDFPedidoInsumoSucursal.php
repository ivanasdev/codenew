<?php

	define('FPDF_FONTPATH','../../../../utils/FPDF/font/');
	//require('../../utils/FPDF/fpdf.php');

	require ("../../../../dbConexion.php");
	require ("../../../../utils/FPDF/PDF_MC_Table.php");

	
	$ruta2index = "../../../../";
	////////////////////////////// TRACKING ////////////////
	include($ruta2index."class.Tracking.php");
	$objTracking = new Tracking(2,3,"INSUMOS - Reporte Pedido de Insumos PDF");
	///////////////////////////////////////////////////////
	

	$idPedidoInsumo = $_GET['idPedidoInsumo'];

	class PDF extends PDF_MC_Table{
		function Header(){

			$titulo = "PEDIDO DE INSUMOS";
			$tituloFolio = "FOLIO";
			$idFolio = $_GET['idPedidoInsumo'];

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
			FROM tbl_SUCInsumoPedido t1
			LEFT JOIN tbl_UsuarioSistemaWeb t2 ON t1.id_Operador = t2.id_Operador
			LEFT JOIN cat_SucursalClinica t3 ON t1.id_Sucursal = t3.id_SucursalClinica
			LEFT JOIN cat_AreaInsumos t4 ON t1.id_AreaInsumos = t4.id_AreaInsumos
			WHERE id_PedidoInsumo = '".$_GET['idPedidoInsumo']."'";
			$res = mssql_query($quer);
			$row = mssql_fetch_object($res);

			//$this->Cell(15,5,'PROVEEDOR: ',0,0,'L');
			$this->SetX(140);
			$this->Cell(15,5,'MOVIMIENTO: Pedido de Insumos',0,0,'L');

			$this->Ln();

			$this->Cell(15,5,'FECHA: '.$row->dt_FechaCierre,0,0,'L');
			$this->SetX(140);
			$this->Cell(15,5,'SUCURSAL: '.$row->nombreSucursal,0,0,'L');

			$this->Ln();

			$this->Cell(15,5,'AREA DE INSUMO: '.$row->areaInsumo,0,0,'L');
			$this->SetX(140);
			$this->Cell(15,5,'USUARIO: '.$row->st_Nombre,0,0,'L');

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
	$widths = array(12,85,15,80);
	$aligns = array('C','L','C','L');
	$pdf->SetWidths($widths);
	$pdf->SetAligns($aligns);

	$pdf->SetFont('Arial','','8');

	$pdf->Cell($widths[0],3,'Linea',1,0,'C',1);
	$pdf->Cell($widths[1],3,utf8_decode('Descripción'),1,0,'C',1);
	$pdf->Cell($widths[2],3,'Cantidad',1,0,'C',1);	
	$pdf->Cell($widths[3],3,'Observaciones',1,0,'C',1);
	
	$pdf->Ln();

	$pdf->SetTextColor(0,0,0);

	$pdf->SetFillColor(255,255,255);

	$queryDatos = "SELECT t1.id_Insumo, t1.i_Cantidad, t1.st_Observaciones, UPPER(t2.st_Nombre) as st_Nombre
	FROM tbl_SUCInsumoPedidoDetalle t1 
	INNER JOIN cat_CEDInsumos t2 ON t1.id_Insumo = t2.id_Insumo
	WHERE t1.id_PedidoInsumo = '".$idPedidoInsumo."'";
	$resDatos = mssql_query($queryDatos);

	$totalCant = 0;
	$totalCostoIVA = 0;
	$totalLinea = 0;
	$totalTotal = 0;

	$pdf->SetFont('Arial','','8');

	$lineas = 0;

	while($rowDatos = mssql_fetch_object($resDatos)){

		$lineas++;
		$descripcion = $rowDatos->st_Nombre;
		$iCantidad = $rowDatos->i_Cantidad;
		$stObservaciones = $rowDatos->st_Observaciones;		
				
   		$pdf->Row(
			array(
				$lineas,
				$descripcion,
				$iCantidad,
				$stObservaciones
			)
		);
	}

	if($pdf->GetY() > 220){
		$pdf->AddPage();	
	}

	$pdf->Ln();
	
	$query0 = "SELECT st_Observaciones FROM tbl_SUCInsumoPedido WHERE id_PedidoInsumo = '".$idPedidoInsumo."'";
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
	

	$pdf->Output('DetallePedidoInsumo_'.$idPedidoInsumo.'.pdf','D');
	mssql_close();
?>