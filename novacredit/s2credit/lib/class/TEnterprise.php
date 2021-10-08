<?


class TEnterprise
{

var $DBName;


//Métodos

//===========================================================================================================//
function TEnterprise($dbname,$db) //Constructor
{

      $this->DBName = $dbname;
  
  	  $sql = "CREATE DATABASE /*!32312 IF NOT EXISTS*/ `".$this->DBName."` /*!40100 DEFAULT CHARACTER SET latin1 */";
	  $db->Execute($sql);
	  
	  $dbi = &ADONewConnection(SERVIDOR);
	  $dbi->PConnect(IP,USER,PASSWORD,$this->DBName);
	  
	  
	//  Aqui deben ir la contrucciónes de la base de datos, estructura y datos primordiales para los sucursales.
	
  
  
}
//===========================================================================================================//

//===========================================================================================================//

}//EndClass
