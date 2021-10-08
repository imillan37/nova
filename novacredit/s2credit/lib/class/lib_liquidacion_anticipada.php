<?
//=====================================================================================================================//
// Class TCUENTA : Estado de cuenta sobre saldos insolutos
//=====================================================================================================================//
require_once($DOCUMENT_ROOT."/rutas.php");
require($class_path."lib_credit.php");

class T_SALDAR_CUENTAS
{

  
  var   $edos_cuenta = array();
  var   $db;
  var   $total_liquidaciones=0;
  var   $total_liquidaciones_anticipadas=0;


  
  function T_SALDAR_CUENTA($id_caja, &$db)  // Constructor
  {

	$this->db = $db;
	$this->id_caja = $id_caja;
	
	
	
	$sql="		 SELECT fact_cliente.ID_Factura,
				pagos.Fecha,
				pagos.ID_Pago,
				pagos.monto

			 FROM   caja_cierre

			 INNER JOIN caja_pagos 		ON caja_pagos.id_caja_apertura  = caja_cierre.id_caja_apertura
			 INNER JOIN pagos      		ON pagos.id_caja_pagos 		= caja_pagos.id_caja_pagos and pagos.activo='S'
			 INNER JOIN fact_cliente 	ON fact_cliente.num_compra 	= pagos.Num_compra

			 WHERE caja_cierre.id_caja_apertura =  '".$this->id_caja."' ";
	
	$rs=$this->db->Execute($sql);
	
	if($rs->_numOfRows)
	   while(! $rs->EOF)
	   {
	 	$this->edos_cuenta[] = $rs->fields;
	
	     	$rs->MoveNext();
	   }
	 
	
  	
  }


  function execute();
  {

	if(count($this->edos_cuenta ))
	{
		foreach($this->edos_cuenta as $row)
		{


			$oAntes    = new TCUENTA($row['ID_Factura'],    $row['Fecha'], 0, $row['ID_Pago']);
			$oDespues  = new TCUENTA($row['ID_Factura'],    $row['Fecha']);

			
			if($oDespues->SaldoGlobalGeneral < 0.01)
			{
				
				$this->sellar_liquidacion($row['ID_Factura'], $row['ID_Pago']);
			
			}
			
			
			else
			
			if($oAntes->saldo_para_liquidar_hoy > 0) and ($oAntes->saldo_para_liquidar_hoy < (abs($oAntes->SaldoFavorPendiente) + $row['monto'])
			{
			
				$this->aplicar_liquidacion_anticipada($row['ID_Factura'],$row['Fecha'], $row['ID_Pago']);
			
			
			}
			

		}

	}

  }


  function sellar_liquidacion($id_factura,  $id_pago)
  {
  
  	$sql = " INSERT INTO factura_cliente_liquidacion (ID_Factura, Fecha, ID_Pago, ID_Caja) VALUES ('".$id_factura."', now(), '".$id_pago."', '".$this->id_caja."') ";
  	$this->db->Execute($sql);
  	$this->total_liquidaciones++;
  
  }




  function aplicar_liquidacion_anticipada($id_factura,$fecha, $id_pago)
  {
  
  	// Paso 1 generar vencimiento anticipado de todas la cuotas si es posible.
  	// Analizar si no hay inconvenientes.
  
       	        $fecha_aplicacion = $fecha;
		$con_vencimiento_anticipado = true;

		unset($id_cargo);
         
                    	$error = 0;


              		$sql="  SELECT num_compra
				FROM fact_cliente 
				WHERE iD_Factura = '".$id_factura."' ";

        		$rs=$this->db->Execute($sql);
        
        		if(!$rs->fields[0])
        		{
        		
        			$this->bitacora_errores('$id_factura',"No se encontró la clave de compra del crédito en cuestión.");
        			$error++;
        		}
        		
        		$num_compra = $rs->fields[0];

              
              		$sql="  SELECT COUNT(*) 
          			FROM    cargos
          			WHERE   Num_compra='".$num_compra."' and Fecha_vencimiento>='".$fecha_aplicacion."'and Activo='Si' and ID_Concepto='-3'
       				GROUP BY Num_compra ";
       
        		$rs=$this->db->Execute($sql);
        
        		if($rs->fields[0]==0)
        		{
        		
        			$this->bitacora_errores('$id_factura',"No es posible realizar el vencimiento anticipado debido a que no hay cuotas que vencer anticipadamente.al $fecha_aplicacion");
        			$con_vencimiento_anticipado = false;
        			
        		}
        
              	
              		
              		
              		
              		
              		
              		if(!$error)
              		{
              		
				$sql="  SELECT  GROUP_CONCAT(ID_Cargo), COUNT(*), SUM(Monto), MAX(Fecha_Vencimiento) 
					FROM    cargos
					WHERE   Num_compra='".$num_compra."' and Fecha_vencimiento>='".$fecha_aplicacion."' and Activo='Si' and ID_Concepto='-3'
					GROUP BY Num_compra ";

				$rs=$this->db->Execute($sql);
				$listado=$rs->fields[0];
				$cuotas =$rs->fields[1]; 
				$monto  =$rs->fields[2];
				$ultima_fecha =$rs->fields[3];
       			
       			
       			
				$sql="	SELECT COUNT(*) 
					FROM  notas_credito
					WHERE ID_Cargo IN (".$listado.") and Num_compra='".$num_compra."' ";


				$rs=$this->db->Execute($sql);
				if($rs->fields[0]>0)
				{

					$this->bitacora_errores('$id_factura',"No es posible llevar acabo la operación debido a que existen ".$rs->fields[0]." notas de crédito asociadas<BR> a algunos de las cuotas que se vencerán anticipadamente.");
					$error++;
				}
        		}
        
        
              		if(!$error)
              		{
              		
				$sql="  SELECT  COUNT(*)    
					FROM    cargos
					WHERE   Num_compra='".$num_compra."' and Concepto LIKE 'Vencimiento anticipado de la cuota%'  and Activo='Si' and ID_Concepto='-3' ";


					$rs=$this->db->Execute($sql);
					if($rs->fields[0]>0)
					{

						$this->bitacora_errores('$id_factura',"No es posible llevar acabo la operación de vencimiento anticipado, debido a que ya existe un vencimiento anticipado previo.");
						$con_vencimiento_anticipado = false;
					}
         		}
       
       
              		if(!$error)
              		{
              		
				if($con_vencimiento_anticipado)  			
				{


						$sql="	SELECT COUNT(*), SUM(Monto)
							FROM  cargos
							WHERE Num_compra='".$num_compra."' and Fecha_vencimiento>='".$fecha_aplicacion."'and Activo='Si' and ID_Concepto='-3' ";
						$rs=$this->db->Execute($sql);
						//debug($sql);

						$num_cargos  =$rs->fields[0];
						$monto_cargos=$rs->fields[1];

						
						
						
						if($num_cargos)
						{

							$sql="	INSERT INTO cargos (ID_Cargo,ID_Concepto,Num_compra,Num_factura,Fecha_vencimiento,Monto,Capital,Interes,Comision,Moratorio,Otros,IVA,SubTipo,Concepto,Observaciones)
								(SELECT   
									(SELECT MAX(ID_Cargo)+1 from cargos 	WHERE Num_compra='".$num_compra."')	AS ID_Cargo, 
									ID_Concepto,
									Num_compra,
									Num_factura,
									'".$fecha_aplicacion."' 						        AS Fecha,
									IF(SUM(Monto)!=0,			SUM(Monto),0) 			AS _Monto,
									IF(SUM(Capital)!=0,			SUM(Capital),0) 		AS _Capital, 
									IF(SUM(Interes)!=0, 			SUM(Interes),0) 		AS _Interes,
									IF(SUM(Comision)!=0, 			SUM(Comision),0) 		AS _Comision,
									IF(SUM(Moratorio)!=0, 			SUM(Moratorio),0) 		AS _Moratorio,
									IF(SUM(Otros)!=0,			SUM(Otros),0) 			AS _Otros,
									IF(SUM(IVA)!=0,				SUM(IVA),0) 			AS _IVA,
									'General' 								AS SubTipo,
									CONCAT('Vencimiento anticipado de la cuota ',MIN(ID_Cargo),' a la ',MAX(ID_Cargo)) AS Concepto,
									GROUP_CONCAT(ID_Cargo) AS Observaciones

									FROM  cargos
									WHERE Num_compra='".$num_compra."' and Fecha_vencimiento>='".$fecha_aplicacion."'and Activo='Si' and ID_Concepto='-3'
									GROUP BY Num_compra) ";

							$this->db->Execute($sql);	


							$sql = " SELECT MAX(ID_Cargo) FROM cargos WHERE Num_compra='".$num_compra."' ";
							$rs=$this->db->Execute($sql);


							$id_cargo = $rs->fields[0];
							if($id_cargo)
							{
								$sql = "SELECT Observaciones FROM cargos WHERE Num_compra='".$num_compra."' and  ID_Cargo='".$id_cargo."' ";

								$rs=$this->db->Execute($sql);	


								if($rs->fields[0])
								{

									$sql="UPDATE cargos SET Activo='No' WHERE ID_Cargo IN(".$rs->fields[0].") and Num_compra='".$num_compra."' ";

									//debug($sql);

									$rs=$this->db->Execute($sql);
							       }
							}

						}
  			
  					}
  					// FIN Vencimiento anticipado.
  					
  					//Paso 2  Generar notas de crédito por la diferencia
  					if{
  					
  					
  						if(empty($id_cargo))
  						{
  							$sql="	SELECT MAX(ID_Cargo) from cargos  WHERE ID_Concepto =-3 and Activo='Si' and Num_compra='".$num_compra."' ";
  							$rs=$this->db->Execute($sql);
  							$id_cargo= $rs->fields[0];
							if(empty($id_cargo))
							{
								$this->bitacora_errores('$id_factura',"No se encontró ningún cargo de tipo cuota sobre el cual agregar notas de crédito.");
								$error++;

							}
 
 
  						}
						 
						 
						if(!$error)
						{
						 
							$oAntes    = new TCUENTA($id_factura,   $fecha, 0, $id_pago);


							$_monto =  $oAntes->SaldoInteres + $oAntes->SaldoInteresPorVencer - $oAntes->proxima_cuota_interes)*(1+$oAntes->iva_pcnt_intereses);
							$_monto =($_monto <=0)?(0):($_monto);
							
							if($_monto >0 )
							{
							
								$_abono = $oAntes->SaldoInteres + $oAntes->SaldoInteresPorVencer - $oAntes->proxima_cuota_interes);
								$_iva =  $oAntes->SaldoInteres + $oAntes->SaldoInteresPorVencer - $oAntes->proxima_cuota_interes)*($oAntes->iva_pcnt_intereses);


								 $forma ='Nota de crédito contra interés normal';
								 $tipo_nota = 'Interes';
								 $referencia = "Caja  :".date("Y-m-d H:i:s")." : #".$this->id_caja." : ".$NOM_USR;

								 $sql="  INSERT INTO notas_credito 
											(ID_Cargo, 		Num_compra,	ID_concepto,		Fecha,		  Monto,       Abono,	IVA,	Forma,	                Subtipo,	Aplicacion,	Usuario)
									 VALUES 
											('".$id_cargo."', '".$num_compra."',	'-5',	'".$fecha_aplicacion."',	'".$_monto."',	'".$_abono ."','".$_iva ."',		'".$forma."','".$tipo_nota."',	'Progresivo',	'".$referencia."')";
								$this->db->Execute($sql);
								
								
								
								
								$oDespues  = new TCUENTA($id_factura,   $fecha);
								
								
								if($oDespues) and ($oDespues->SaldoGlobalGeneral < 0.01)
								{
								
								$this->sellar_liquidacion($id_factura,  $id_pago);
								
								
								}
								else
								{
								
									$this->bitacora_errores('$id_factura',"La cuenta no quedó saldada, quedó un saldo de : ".$oDespues->SaldoGlobalGeneral.".");
							
								
								}

								
								
								

  							}
  					
  					
  					        }
  					
  					
  					
  					
  					
  					
  					
  					
  					
  					}
  					
  					
  					
  					
  					
  					
  					
  					
  			} //Fin Validación
  }




};//EndClass

?>