<?php 

$DB = &ADONewConnection(SERVIDOR);
$DB->PConnect(IP,USER,PASSWORD,$DB_EMP);        

$campos=$DB->Execute("Select * from cat_campos_aval");
while($campo=$campos->fetchRow()){

	$DB->Execute("Insert into cat_campos_aval(ID_secc,
												Nombre_tabla,
												Nombre_campo,
												Posicion,
												Tabla_destino,
												Obligatorio_sistema,
												Obligatorio,
												Visible,
												Clase,
												Html_asoc,
												Tipo,
												Etiqueta,
												Etiqueta_s2credit,
												Style,
												SQLQuery,
												Ajax,
												Script,
												ID_tipo,
												ID_Dominio
												) values (".
												$campo['ID_secc']."+5,'".
												$campo['Nombre_tabla']."','".
												$campo['Nombre_campo']."','".
												$campo['Posicion']."','".
												$campo['Tabla_destino']."','".
												$campo['Obligatorio_sistema']."','".
												$campo['Obligatorio']."','".
												$campo['Visible']."','".
												$campo['Clase']."','".
												$campo['Html_asoc']."','".
												$campo['Tipo']."','".
												$campo['Etiqueta']."','".
												$campo['Etiqueta_s2credit']."','".
												$campo['Style']."','".
												$campo['SQLQuery']."','".
												$campo['Ajax']."','".
												$campo['Script']."','".
												$campo['ID_tipo']."','".
												$campo['ID_Dominio'].
												"')");
echo $campo['Nombre_campo'].": Exito</br>";

}
?>