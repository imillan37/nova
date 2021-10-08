<?

class ISR
{

	var $id_vendedor			 = 0;
	var $gravamen 				 = 0;
	var $fecha 					 = "";
	
	var $gravable_acumulado		 =0;
	var $isr_acumulado			 =0;


	//=======================================================================================================================
	// Tipo de sujeto : 36. Frac VI INGRESO ACT. EMP. Persona Fisica que realiza la Actividad Sucursalrial a la Persona Moral.
	//=======================================================================================================================

	var $subsidio_si_acreditable =0;	

	//=======================================================================================================================


	var $isr_retencion_final	 =0;
		
	var $gravable_total			 =0;
	var $isr					 =0;
	

	var $t113_linf				 =0;
	var $t113_lsup				 =0;
	var $t113_cfija				 =0;
	var $t113_exe				 =0;

	var $t114_linf				 =0;
	var $t114_lsup				 =0;
	var $t114_cfija				 =0;
	var $t114_im				 =0;
	
	var $v113_excedente_inf  	 =0;
	var $v113_cantidad_det 		 =0;	
	var $v113_impuesto			 =0;		

	var $v114_excedente_inf  	 =0;
	var $v114_impuesto_marginal	 =0;
	var $v114_cantidad_det 		 =0;
	var $v114_subsidio_calculado =0;
	var $v114_subsidio_acrediable=0;
	var $v114_impuesto_subsidiado=0;

	//===========================================================================================================//
	
	function ISR($gravable, $fecha, $id_vendedor, $db, $sa=100 )
	{

		$this->id_vendedor	= $id_vendedor;
		$this->gravamen 	= $gravable;
		$this->fecha 		= $fecha;


		//=======================================================================================================================
		// Tipo de sujeto : 36. Frac VI INGRESO ACT. EMP. Persona Fisica que realiza la Actividad Sucursalrial a la Persona Moral.
		//=======================================================================================================================
		$this->subsidio_si_acreditable = $sa;	
		
		

		$this->GetISR_Acumulado($db);

		$this->Get113($db);
		$this->Get114($db);

		$this->v113_excedente_inf 			= $this->gravable_total - $this->t113_linf;
		$this->v113_cantidad_det 			= $this->v113_excedente_inf * ($this->t113_exe/100);
		$this->v113_impuesto				= $this->v113_cantidad_det + $this->t113_cfija;

		$this->v114_excedente_inf  	 	 	= $this->gravable_total - $this->t114_linf;
		$this->v114_impuesto_marginal		= $this->v114_excedente_inf * ($this->t113_exe/100);	
		$this->v114_cantidad_det 		 	= $this->v114_impuesto_marginal * ($this->t114_im/100);
		$this->v114_subsidio_calculado 		= $this->v114_cantidad_det + $this->t114_cfija;
		
		$this->v114_subsidio_aplicable		= $this->v114_subsidio_calculado * ($this->subsidio_si_acreditable/100);
		$this->v114_impuesto_subsidiado		= $this->v114_subsidio_aplicable;
		
		$this->isr = $this->v113_impuesto - $this->v114_impuesto_subsidiado - $this->isr_acumulado;


	}

	//===========================================================================================================//

	function GetISR_Acumulado($db)
	{
		$sql = "SELECT SUM(Gravamen), SUM(ISR)
				FROM ".NUCLEO.".vendedor_isr
				WHERE 	ID_Vendedor  = '".$this->id_vendedor."' and   
						Month(Fecha) = '".fmes($this->fecha)."' and 
						YEAR(Fecha)  = '".fanio($this->fecha)."' ";
						
						
		$rs=$db->Execute($sql);		
		$this->gravable_acumulado		 = $rs->fields[0];
		$this->isr_acumulado			 = $rs->fields[1];		 
		$this->gravable_total			 = $this->gravable_acumulado+$this->gravamen;
		
		return;		 
	}

	//===========================================================================================================//
	
	function Get113($db)
	{

		$sql = "SELECT ID_ISR FROM ".NUCLEO.".isr WHERE Tipo='LISR 113' ORDER BY Fecha DESC LIMIT 0,1";
		$rs=$db->Execute($sql);
		$id_tabla = $rs->fields[0];
		
		$sql = "SELECT  Lim_inferior, Lim_superior, Cuata_fija, Excedente 
				FROM ".NUCLEO.".isr_dtl
				WHERE ID_ISR='".$id_tabla."' and (Lim_inferior <= ".$this->gravable_total.") and   (Lim_superior >=".$this->gravable_total.") ";
 		$rs=$db->Execute($sql);
		//debug($sql);
		
		
		$this->t113_linf	 =	$rs->fields[0];
		$this->t113_lsup	 =	$rs->fields[1];
		$this->t113_cfija	 =	$rs->fields[2];
		$this->t113_exe		 =	$rs->fields[3];

		return;
	}

	//===========================================================================================================//

	function Get114($db)
	{
		$sql = "SELECT ID_ISR FROM ".NUCLEO.".isr WHERE Tipo='LISR 114' ORDER BY Fecha DESC LIMIT 0,1";
		$rs=$db->Execute($sql);
		$id_tabla = $rs->fields[0];
		
		$sql = "SELECT Lim_inferior, Lim_superior, Cuata_fija, Excedente 
				FROM ".NUCLEO.".isr_dtl
				WHERE ID_ISR='".$id_tabla."' and (Lim_inferior <= ".$this->gravable_total.") and   (Lim_superior >=".$this->gravable_total.") ";
		$rs=$db->Execute($sql);
		$this->t114_linf	 =	$rs->fields[0];
		$this->t114_lsup	 =	$rs->fields[1];
		$this->t114_cfija	 =	$rs->fields[2];
		$this->t114_im		 =	$rs->fields[3];

		return;

	}


	//===========================================================================================================//

	function Show()
	{

		echo "<TABLE ALIGN='center' BORDER=0 CELLSPACING=1 CELLPADDING=2 ID='S2' WIDTH='60%'  bgcolor='black'>
		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='left'  bgcolor='white' > No. Vendedor	</TH>
			<TD  bgcolor='white' >".(10000+$this->id_vendedor)."</TD>
		</TR>
		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='left'   bgcolor='lightsteelblue' >Fecha </TH>
			<TD ALIGN='center' bgcolor='white' > ".ffecha($this->fecha)." </TD>
		</TR>
		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='left' bgcolor='lightsteelblue' >Gravamen </TH>
			<TD ALIGN='right' bgcolor='white' > ".number_format($this->gravamen,2)." </TD>
		</TR>
		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='left' bgcolor='lightsteelblue' >Gravable acumulado </TH>
			<TD ALIGN='right' bgcolor='white' > ".number_format($this->gravable_acumulado,2)." </TD>
		</TR>
		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='left'  bgcolor='lightsteelblue' >Gravable de cálculo  </TH>
			<TD ALIGN='right' bgcolor='white' > ".number_format($this->gravable_total,2)." </TD>
		</TR>

		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='center'  colspan='2' bgcolor='steelblue' >  ISR Art. 113</TH>
		</TR>

		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='left'  bgcolor='lightsteelblue' >Lim. Inferior  </TH>
			<TD ALIGN='right' bgcolor='white' >  ".number_format($this->t113_linf,2)." </TD>
		</TR>

		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='left'  bgcolor='lightsteelblue' >Lim. Superior  </TH>
			<TD ALIGN='right' bgcolor='white' >  ".number_format($this->t113_lsup,2)." </TD>
		</TR>

		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='left'  bgcolor='lightsteelblue' >Cuota fija  </TH>
			<TD ALIGN='right' bgcolor='white' >  ".number_format($this->t113_cfija,2)." </TD>
		</TR>

		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='left'  bgcolor='lightsteelblue' >Excedente</TH>
			<TD ALIGN='right' bgcolor='white' >  ".number_format($this->t113_exe,0)."%</TD>
		</TR>

		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='center'  colspan='2' bgcolor='steelblue' > &nbsp; </TH>
		</TR>

		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='left'  bgcolor='lightsteelblue' > Excedente del Limite inferior </TH>
			<TD ALIGN='right' bgcolor='white' >   ".number_format($this->v113_excedente_inf,2)." </TD>
		</TR>
		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='left'   bgcolor='lightsteelblue' > Cantidad determinada </TH>
			<TD ALIGN='right'  bgcolor='white' >  ".number_format($this->v113_cantidad_det,2)." </TD>
		</TR>
		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='left'   bgcolor='lightsteelblue' > Impuesto a cargo Art. 113</TH>
			<TD ALIGN='right'  bgcolor='white' >  ".number_format($this->v113_impuesto,2)." </TD>
		</TR>		


		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='center' colspan='2' bgcolor='steelblue' >  ISR Art. 114</TH>
		</TR>

		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='left'  bgcolor='lightsteelblue' >Lim. Inferior  </TH>
			<TD ALIGN='right' bgcolor='white' >  ".number_format($this->t114_linf,2)." </TD>
		</TR>

		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='left'  bgcolor='lightsteelblue' >Lim. Superior  </TH>
			<TD ALIGN='right' bgcolor='white' >  ".number_format($this->t114_lsup,2)." </TD>
		</TR>

		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='left'  bgcolor='lightsteelblue' >Cuota fija  </TH>
			<TD ALIGN='right' bgcolor='white' >  ".number_format($this->t114_cfija,2)." </TD>
		</TR>

		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='left'   bgcolor='lightsteelblue' >Excedente</TH>
			<TD ALIGN='right'  bgcolor='white' >  ".number_format($this->t114_im,0)."%</TD>
		</TR>

		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='center'  colspan='2' bgcolor='steelblue' > &nbsp; </TH>
		</TR>


		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='left'   bgcolor='lightsteelblue' >Excedente del lim. Inferior  </TH>
			<TD ALIGN='right'  bgcolor='white' >  ".number_format($this->v114_excedente_inf,2)." </TD>
		</TR>

		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='left'  bgcolor='lightsteelblue' >Impuesto marginal </TH>
			<TD ALIGN='right' bgcolor='white' >  ".number_format($this->v114_impuesto_marginal,2)." </TD>
		</TR>

		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='left'  bgcolor='lightsteelblue' >Cantidad determinada </TH>
			<TD ALIGN='right' bgcolor='white'  >  ".number_format($this->v114_cantidad_det,2)." </TD>
		</TR>

		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='left'  bgcolor='lightsteelblue' >Subsidio calculado</TH>
			<TD ALIGN='right' bgcolor='white' >  ".number_format($this->v114_subsidio_calculado,2)." </TD>
		</TR>
			

		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='left'  bgcolor='lightsteelblue' >Subsidio aplicable ART 114</TH>
			<TD ALIGN='right' bgcolor='white' >  ".number_format($this->v114_subsidio_aplicable,2)." </TD>
		</TR>
		
		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='left'  bgcolor='lightsteelblue' >Impuesto subsidiado.</TH>
			<TD ALIGN='right' bgcolor='white' >  ".number_format($this->v114_impuesto_subsidiado,2)." </TD>
		</TR>
				
		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='left'  bgcolor='lightsteelblue' >Impuesto acumulado en el mes.</TH>
			<TD ALIGN='right' bgcolor='white' > - ".number_format($this->isr_acumulado,2)." </TD>
		</TR>

		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='center'  colspan='2' bgcolor='steelblue' > &nbsp; </TH>
		</TR>
		
		<TR ALIGN='right' VALIGN='middle'>
			<TH ALIGN='left'  bgcolor='lightsteelblue' >Impuesto final</TH>
			<TD ALIGN='right' bgcolor='white' > ".number_format($this->isr,2)." </TD>
		</TR>\n";

		echo "</TABLE><BR><BR> \n\n \n";
		
		return;

	}	

	//===========================================================================================================//

	
	function Acumula($id_factura, $db)
	{

		$sql = "INSERT INTO ".NUCLEO.".vendedor_isr 
				(ID_Factura, ID_Vendedor, Fecha, Gravamen, ISR)
				VALUES
				('".$id_factura."', '".$this->id_vendedor."','".$this->fecha."','".$this->gravamen."','".$this->isr."')
				ON DUPLICATE KEY UPDATE ISR='".$this->isr."' ";
		
		$db->Execute($sql);

		return($db->_affectedrows());

	}
};




?>