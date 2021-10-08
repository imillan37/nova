<?
/****************************************/
/*Fecha: 19/09/2012
/*Autor: Tonathiu Cárdenas
/*Descripcíón: Se genera la validación de los procesos para enviar alertas vía email 
/*Dependencias: Script's de procesos
/*Tablas asociadas: cat_tipo_credito_proceso
/*Versión S2credit: 4.5
/****************************************/


class TNuevaAlerta
{
  private $db;
  private $ID_Tipo_regimen;
  private $TIPO_CREDITO;
  private $PROCESO;
  private $PROCESO_ASOCIADO;
  private $ID_SUC;
  private $NOMBRE_SUCURSAL;
  private $ID_USR;
  private $NOTIFICACIONES;
  private $TIPO_NOTIFICACIONES;
  private $EMAIL;
  private $MSG_EMAIL;
  private $TITLE_EMAIL;
  private $MSG_CUSTOM;
  private $PERIODICIDAD_EMAIL;
  private $ID_SOLICITUD;
  private $NUM_CLIENTE;
  private $NOMBRE_CLIENTE;

 
  
    function    TNuevaAlerta ($ID_Solicitud,$ID_Tipocredito,$ID_Tipo_regimen,$Proceso,$Proceso_asociado,&$db,$id_sucursal,$id_usr)//Constructor
    {
       $this->db             			= $db;
       $this->ID_Tipo_regimen 			= $ID_Tipo_regimen;
       $this->TIPO_CREDITO 				= $ID_Tipocredito;
       $this->PROCESO 					= $Proceso;
       $this->PROCESO_ASOCIADO			= $Proceso_asociado;
       $this->ID_SUC 					= $id_sucursal;
       $this->ID_USR 					= $id_usr;
       $this->ID_SOLICITUD 				= $ID_Solicitud;

		/************INFO SUCURSAL********************/
         $SQL_SUC = "SELECT
							Nombre		AS NMB_SUC
                     FROM sucursales 
                     WHERE   ID_Sucursal= '".$this->ID_SUC."' ";
		$rs_suc=$this->db->Execute($SQL_SUC);

        $this->NOMBRE_SUCURSAL = $rs_suc->fields["NMB_SUC"];

		/**************NÚMERO DE CLIENTE***************/
		$TABLA_CTE = ($this->TIPO_CREDITO < 4 )?('clientes_datos'):('clientes_datos_pmoral');

        $SQL_NUM_CTE = "SELECT
							Num_cliente		AS NUM_CTE
                     FROM
							".$TABLA_CTE."
                     WHERE   ID_Solicitud = '".$this->ID_SOLICITUD."' ";
		$rs_num=$this->db->Execute($SQL_NUM_CTE);
		
		$this->NUM_CLIENTE = $rs_num->fields["NUM_CTE"];
	}

	function check_proceso()//VERIFICAR PROCESO NECESITA ALERTA
	{
				
				
 				$SQL_CONS="SELECT
										Notificacion	AS NTFC
								FROM
										cat_tipo_credito_proceso
								WHERE
											ID_Tipo_regimen = '".$this->ID_Tipo_regimen."'
									AND	Proceso	= '".$this->PROCESO."' ";
				$rs_cons=$this->db->Execute($SQL_CONS);

			   $this->NOTIFICACIONES=$rs_cons->fields["NTFC"];

			   //VERIFICAMOS PROCESOS ASOCIADOS
			   if( ($this->NOTIFICACIONES == 'Y') && (!empty($this->PROCESO_ASOCIADO)) )
			   {
					$SQL_CONS="SELECT
											Proceso_asociado	AS PROCESO_ASOC
									FROM
											cat_tipo_credito_proceso
									WHERE
												ID_Tipo_regimen = '".$this->ID_Tipo_regimen."'
										AND	Proceso	= '".$this->PROCESO."'
										".$DISCRIMINANTE." ";
					$rs_cons=$this->db->Execute($SQL_CONS);

					$PROCESO_ASOC = $rs_cons->fields["PROCESO_ASOC"];
					$PROCESO_ASOC = strpos($PROCESO_ASOC,$this->PROCESO_ASOCIADO);

					if($PROCESO_ASOC === false )
						$this->NOTIFICACIONES = 'N';
					else
						$this->NOTIFICACIONES = 'Y';

			  }

	}

	function cliente_nombre()
	{

		if($this->TIPO_CREDITO < 4 )
		{
			$sql_cons ="SELECT
								CONCAT(Nombre,' ',NombreI,' ',AP_Paterno,' ',AP_Materno) AS CTE
						 FROM solicitud
							WHERE ID_Solicitud ='".$this->ID_SOLICITUD."' ";
			$rs_cons = $this->db->Execute($sql_cons);
		}
		else
		{
					$Sql_reg="SELECT
										ID_Regimen	AS REG
								FROM
										cat_tipo_credito_regimen
								WHERE ID_Tipo_regimen = '".$this->ID_Tipo_regimen."' ";
					$rs_reg=$this->db->Execute($Sql_reg);
					$TIPO_REGIMEN=$rs_reg->fields["REG"];

					if($TIPO_REGIMEN == 'PFAE')
					   $NOMBRE_CTE ="CONCAT(Nombre_pfae,' ',NombreI_pfae,' ',Ap_paterno_pfae,' ',Ap_materno_pfae) AS CTE";
					 else
						$NOMBRE_CTE ="Razon_social		AS CTE";
						
					$Sql_cons="SELECT
									".$NOMBRE_CTE."
								FROM 
										solicitud_pmoral
								WHERE ID_Solicitud = '".$this->ID_SOLICITUD."' ";
								
					$rs_cons=$this->db->Execute($Sql_cons);

		}

	   $this->NOMBRE_CLIENTE = $rs_cons->fields["CTE"];
	}


	function set_send_email($EMAIL)
	{ 
	

		
		// *********************************************************************** 
		$mime_boundary = "----S2CREDIT----".md5(time());
		# -=-=-=- MAIL HEADERS
		$cabeceras  = "From: S2Credit <admin@s2credit.com>\n";
		$cabeceras .= "MIME-Version: 1.0\n";
		$cabeceras .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
		# -=-=-=- TEXT EMAIL PART
		$codigohtml = "--$mime_boundary\n";
		$codigohtml .= "Content-Type: text/plain; charset=UTF-8\n";
		$codigohtml .= "Content-Transfer-Encoding: 8bit\n\n";
		$codigohtml .= str_replace( "<br>", "\n", str_replace( "<p>", "\n", str_replace( "</p>", "\n",  $this->MSG_EMAIL ) ) ); 
		$codigohtml .= "\n\n"; 
		# -=-=-=- HTML EMAIL PART
		$codigohtml .= "--$mime_boundary\n";
		$codigohtml .= "Content-Type: text/html; charset=UTF-8\n";
		$codigohtml .= "Content-Transfer-Encoding: 8bit\n\n";
		$codigohtml .= "<html>\n";
		$codigohtml .= "<body style=\"font-family:Verdana, Verdana, Geneva, sans-serif; font-size:14px; color:#666666;\">\n";
		$codigohtml .= "<table width='650' cellspacing='0' cellpadding='0' align='center' style='font-size:14px'>
						<tbody><tr>
								<td width='10' bgcolor='#ffffff' background='".$_SERVER['SERVER_ADDR']."/images_email/shadow_tl.gif' height='10'></td>
								<td bgcolor='#ffffff' background='".$_SERVER['SERVER_ADDR']."/images_email/shadow_top.gif' height='10'> </td>
								<td width='10' bgcolor='#ffffff' background='".$_SERVER['SERVER_ADDR']."/images_email/shadow_tr.gif' height='10'> </td>
							</tr>
							
							<tr>
								<td width='10' bgcolor='#ffffff' background='".$_SERVER['SERVER_ADDR']."/images_email/shadow_left.gif' rowspan='2'></td>
								<td bgcolor='#e6f1fb' background='".$_SERVER['SERVER_ADDR']."/images_email/header_bg.gif' align='center' height='102'>
									<table width='95%'><tbody><tr>
										<td align='left' width='50%'>
											<img src='".$_SERVER['SERVER_ADDR']."/images_email/S2CREDIT.png'>
										</td>
										<td align='right' width='50%'>
											<img src='".$_SERVER['SERVER_ADDR']."/images_email/bg_s2.png' width='600px'>
										</td>
									</tr></tbody></table>
								</td>
								<td width='10' bgcolor='#ffffff' background='".$_SERVER['SERVER_ADDR']."/images_email/shadow_right.gif' rowspan='2'> </td>
							</tr>
							
							<tr>
								<td bgcolor='#f4faff' align='center'>
									<table width='95%' cellpadding='15'>
										<tbody>
											<tr>
											<th align='center'>
												<font face='Lucida Grande, Segoe UI, Arial, Verdana, Lucida Sans Unicode, Tahoma, Sans Serif'>
													 NOTIFICACIÓN S2CREDIT
												</font>
											</th>
											</tr>
											<tr>
											<td align='left'>
												<font face='Lucida Grande, Segoe UI, Arial, Verdana, Lucida Sans Unicode, Tahoma, Sans Serif'>
													".$this->MSG_EMAIL."
												</font>
											</td>
										</tr>
									</tbody></table>
								</td>
							</tr>
							
							<tr>
								<td width='10' bgcolor='#ffffff' background='".$_SERVER['SERVER_ADDR']."/images_email/shadow_bl.gif' height='10'></td>
								<td bgcolor='#ffffff' background='".$_SERVER['SERVER_ADDR']."/images_email/shadow_bottom.gif' height='10'> </td>
								<td width='10' bgcolor='#ffffff' background='".$_SERVER['SERVER_ADDR']."/images_email/shadow_br.gif' height='10'> </td>
							</tr>
							
						<tr>
							<td></td>
							<td>
								<table style='width:100%'>
									<tbody>
										<tr>
											<td style='font-family:'Lucida Grande','Segoe UI',Arial,Verdana,'Lucida Sans Unicode',Tahoma,'Sans Serif';font-size:5px;color:#909090;padding-left:45px'>
												&nbsp;
											</td>
											<td align='right'>
												  <span style='font-family:'Lucida Grande','Segoe UI',Arial,Verdana,'Lucida Sans Unicode',Tahoma,'Sans Serif';font-size:11px;color:#888'>&copy;&nbsp;".date("Y")."&nbsp;S2CREDIT</span>
											</td>                    
										</tr>
								   </tbody>
								 </table>
							</td>
							<td></td>
						</tr>
					</tbody></table>";
		//$codigohtml .=  $this->MSG_EMAIL;
		$codigohtml .= "</body>\n";
		$codigohtml .= "</html>\n";
		# -=-=-=- FINAL BOUNDARY
		$codigohtml .= "--$mime_boundary--\n\n";

		$this->TITLE_EMAIL = ($this->PROCESO == 'CHECK LIST' )?($this->TITLE_EMAIL." [".$this->NUM_CLIENTE."] "):($this->TITLE_EMAIL." [".$this->ID_SOLICITUD."] ");
		
	 	@mail($EMAIL, $this->TITLE_EMAIL , utf8_encode($codigohtml), $cabeceras ); 
		//echo("$EMAIL, ".$this->TITLE_EMAIL.", ".utf8_encode($codigohtml)."");
	}

	function get_emails()//OBTIENE LOS EMAIL ENLAZADOS AL PROCESO
	{
 				$SQL_CONS="SELECT
										Email	AS EMAIL
								FROM
										cat_tipo_credito_proceso
								WHERE
											ID_Tipo_regimen = '".$this->ID_Tipo_regimen."'
									AND	Proceso	= '".$this->PROCESO."' ";
				$rs_cons=$this->db->Execute($SQL_CONS);

			   $this->EMAIL=$rs_cons->fields["EMAIL"];
			   //$this->EMAIL=explode("|",$this->EMAIL);
 
	}

	function get_mensaje_emails()//OBTIENE EL MSG DEL EMAIL ENLAZADOS AL PROCESO
	{
 				$SQL_CONS="SELECT
										Mensaje_email	AS MSG_EMAIL
								FROM
										cat_tipo_credito_proceso
								WHERE
											ID_Tipo_regimen = '".$this->ID_Tipo_regimen."'
									AND	Proceso	= '".$this->PROCESO."' ";
				$rs_cons=$this->db->Execute($SQL_CONS);

			   $this->MSG_EMAIL=$rs_cons->fields["MSG_EMAIL"];

			   $this->cliente_nombre();
			  /************PARSER MENSAJE EMAIL**********/
			  $this->MSG_EMAIL = str_replace('[ID_SOLICITUD]',"<B>[".$this->ID_SOLICITUD."]</B>",$this->MSG_EMAIL);
			  $this->MSG_EMAIL = str_replace('[NOMBRE_SOLICITUD]',"<B>[".$this->NOMBRE_CLIENTE."]</B>",$this->MSG_EMAIL);
			  $this->MSG_EMAIL = str_replace('[NOMBRE_SUCURSAL]',"<B>[".$this->NOMBRE_SUCURSAL."]</B>",$this->MSG_EMAIL);
			  $this->MSG_EMAIL = str_replace('[SUCESO]',"<B>[".$this->MSG_CUSTOM."]</B>",$this->MSG_EMAIL);
			  $this->MSG_EMAIL = str_replace('[NUMERO_CTE]',"<B>[".$this->NUM_CLIENTE."]</B>",$this->MSG_EMAIL);
			  
			    
	}

	function get_periodicidad_emails()//OBTIENE EL MSG DEL EMAIL ENLAZADOS AL PROCESO
	{
 				$SQL_CONS="SELECT
										Periodicidad_email	AS PERIODO_EMAIL
								FROM
										cat_tipo_credito_proceso
								WHERE
											ID_Tipo_regimen = '".$this->ID_Tipo_regimen."'
									AND	Proceso	= '".$this->PROCESO."' ";
				$rs_cons=$this->db->Execute($SQL_CONS);

			   $this->PERIODICIDAD_EMAIL=$rs_cons->fields["PERIODO_EMAIL"];
	}

	/********************LANZAR ALERTAS*****************************/
	
	function make_emails()
	{
		$this->get_mensaje_emails();
		$this->get_periodicidad_emails();
	
		switch ($this->PERIODICIDAD_EMAIL)
			{
				case "EVENTO":
				
					//foreach($this->EMAIL as $EMAIL)
					//{
						if(!empty($this->EMAIL))
							$this->set_send_email($this->EMAIL);
					//}
					
				break;
					
			}

	}

	function set_notifica_proceso($Tipo_notifica,$TITLE,$SUCESO_MSG) //LANZADOR DE ALERTAS
	{
		$this->TIPO_NOTIFICACIONES 		= $Tipo_notifica;
		$this->MSG_CUSTOM		 		= $SUCESO_MSG;
		$this->TITLE_EMAIL				= $TITLE;

		$this->check_proceso();
 
		if($this->NOTIFICACIONES == 'Y' )
		{

					if($this->TIPO_NOTIFICACIONES == "EMAIL")
					{ 
							$this->get_emails();
							
							if( count($this->EMAIL) >= 0  )
								$this->make_emails();
					}
					 
				

		}

	}


	
}//FIN CLASS


?>
