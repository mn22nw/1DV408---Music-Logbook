<?php 
	
//Dependencies
require_once ('./src/model/base/Repository.php');		
				
		try { 
				$dbTable = 'song';
				$connection = new\model\Repository();

				$db = $connection -> connection();
				
				
				// SELECT (songID from usertable) //
				$sql= "SELECT `". self::$userID . "` FROM `". self::$userTable . "` WHERE `". self::$username . "` = '".$username. "' LIMIT 1";
				$query = $db->prepare($sql);
				$query->execute();
				$result= $query->fetch(\PDO::FETCH_ASSOC);
				// END SELECT
							
				// UPDATE (InstrumentID into usertable) //
				$sql = "UPDATE ". self::$userTable . "
		        SET ". self::$instrumentIDFK . "=?
				WHERE " . self::$userID . "=?"; 
				return $songID;
			
			}
			catch(Exception $e){

			    $handler->rollback();
			}
			
			echo "Success";

?>