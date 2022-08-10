<?php
require ("../../../../dbConexion.php");
include("../../../../bionline/securitylayer/clases/class.Formateo.php");
$objFormateo = new Formateo();

if( isset($_GET["idSucursal"]) ){
	$idSucursal = trim($_GET["idSucursal"]);
	$where = "WHERE id_Sucursal = '".$idSucursal."'";
	
	$query0 = "SELECT st_nombre FROM cat_SucursalClinica WHERE id_SucursalClinica = '".$idSucursal."'";
	$rquery0 = mssql_query($query0);
	$arrayQuery0 = mssql_fetch_array($rquery0);
	$nombreAlmacen = strtoupper( $arrayQuery0['st_nombre'] );
	
	
}
else{
	echo "Parametros Invalidos";
	exit;
}


	$fecha = date('Ymd');
	$fecha2 = date('Y-m-d');
	
	
	require("../../../../utils/PHPExcel/Classes/PHPExcel.php");
	include ("../../../../utils/PHPExcel/Classes/PHPExcel/Writer/Excel5.php");
 
	$objPHPExcel = new PHPExcel();

	$objPHPExcel->setActiveSheetIndex(0);
	
	$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);

	$i=2;

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);	
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);

	$objPHPExcel->getActiveSheet()->getCell('A'.$i)->setValue("STOCK ".$nombreAlmacen." - FECHA:".$fecha2);
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':G'.$i);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getFont()->getColor()->setARGB('FFFFFFFF'); 
	$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00000000');

	$i++;
	$i++;

	$objPHPExcel->getActiveSheet()->getCell('A'.$i)->setValue("AREA");
	$objPHPExcel->getActiveSheet()->getCell('B'.$i)->setValue("CODIGO DE BARRAS");
	$objPHPExcel->getActiveSheet()->getCell('C'.$i)->setValue("DESCRIPCION");
	$objPHPExcel->getActiveSheet()->getCell('D'.$i)->setValue("LOTE");
	$objPHPExcel->getActiveSheet()->getCell('E'.$i)->setValue("CADUCIDAD");
	$objPHPExcel->getActiveSheet()->getCell('F'.$i)->setValue("CANTIDAD");
	$objPHPExcel->getActiveSheet()->getCell('G'.$i)->setValue("SUCURSAL");
	$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getFont()->getColor()->setARGB('FFFFFFFF'); 
	$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00000000');

	$i++;


	$query1 = "SELECT count(*) as conteo FROM tbl_SUCStockAlmacenInsumos ".$where;
	$rquery1 = mssql_query($query1);
	$arrayQuery1 = mssql_fetch_array($rquery1);
	$conteo = $arrayQuery1['conteo'];
	
	if($conteo == 0):
			
	$objPHPExcel->getActiveSheet()->getCell('A'.$i)->setValue("NO EXISTEN PRODUCTOS");
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':G'.$i);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	else:

	$query1 = "SELECT '' as e1,
				t2.id_UPC as e2,
				t2.st_nombre as e3,
				t1.st_Lote as e4,
				t1.dt_FechaCaducidad as e5,
				t1.i_Cantidad as e7,
				t3.st_Nombre as e8,
				t4.st_Nombre as e9
			FROM tbl_SUCStockAlmacenInsumos t1 
			INNER JOIN cat_ProductosAlmacenMaster t2 ON t1.id_ProductoAlmacen = t2.id_ProductoAlmacen
			INNER JOIN cat_SucursalClinica t3 ON t1.id_Sucursal = t3.id_SucursalClinica
			LEFT JOIN cat_AreaInsumos t4 ON t1.id_AreaInsumos = t4.id_AreaInsumos
			".$where." ORDER BY t2.st_nombre, t3.st_Nombre, t1.dt_FechaCaducidad";
	$rquery1 = mssql_query($query1);


	while($arrayQuery1 = mssql_fetch_array($rquery1)){
		$objPHPExcel->getActiveSheet()->getCell('A'.$i)->setValueExplicit(utf8_encode($arrayQuery1['e9']),PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->getCell('B'.$i)->setValueExplicit($arrayQuery1['e2'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->getCell('C'.$i)->setValue($arrayQuery1['e3']);
		$objPHPExcel->getActiveSheet()->getCell('D'.$i)->setValueExplicit($arrayQuery1['e4'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->getCell('E'.$i)->setValue( $objFormateo->mostrarFecha($arrayQuery1['e5']) );
		$objPHPExcel->getActiveSheet()->getCell('F'.$i)->setValue($arrayQuery1['e7']);
		$objPHPExcel->getActiveSheet()->getCell('G'.$i)->setValue($arrayQuery1['e8']);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$i++;
	 }

	
	endif;


	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="StockInsumos'.$idSucursal.'_'.$fecha.'.xls"');
	header('Cache-Control: max-age=0');

	$objWriter->save('php://output');
?>