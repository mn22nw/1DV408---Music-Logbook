<?php
namespace model;

require_once ('./src/model/m_instrument.php');
require_once ('./src/model/m_instrumentList.php');
require_once ('./src/model/base/Repository.php');
require_once("src/helper/Misc.php");

class InstrumentRepository extends base\Repository {
	private $instruments;
	private $misc;
	
	//DB fields
	private static $instrumentID = 'instrumentID';
	private static $name = 'name';
	private static $userID = 'userID';
	private static $username ='username';
	private static $userIDFK = 'userIDFK';
	private static $instrumentIDFK = 'instrumentIDFK';
	private static $songID = 'songID';
	
	//DB tables
	private static $songTable = 'song';
	private static $userTable = 'user';
	

	public function __construct() {
		$this -> dbTable = 'instrument';
		$this -> instruments = new InstrumentList();
		$this->misc = new \helper\Misc();
	}
	
	public function add(Instrument $instrument, $username) {  // TODO -NEED TO ADD TO THE RIGHT USER!
	
		try { 
				$db = $this->connection();
				$db->beginTransaction();
				
				// SELECT (userID from usertable) //
				$sql= "SELECT `". self::$userID . "` FROM `". self::$userTable . "` WHERE `". self::$username . "` = '".$username. "' LIMIT 1";
				$query = $db->prepare($sql);
				$query->execute();
				$result= $query->fetch(\PDO::FETCH_ASSOC);
				// END SELECT
				
				// INSERT (Instrument into database) //
				$sql = "INSERT INTO $this->dbTable (". self::$instrumentID . ", " . self::$name . " , " . self::$userIDFK . ") VALUES (  ?, ?, ?)";
				$params = array("", ucfirst($instrument->getName()), $result[self::$userID]);
		
				$query = $db->prepare($sql);
				$query->execute($params);
				// END INSERT //
				
				$db->commit();  //commits the transaction if it is succesfull   
					
				//gets instrumentID from the newly created instrument  
				//TODO- is this necessary?! o.0 -- MAYBE IF you get to choose default instrument in the list (to then save in db)
				//-but it should be in the toList instead!
				
				$instrumentID = $this->getInstrumentID($instrument -> getName(), $result[self::$userID]);
				$instrument->setInstrumentID($instrumentID);
		}
				catch(Exception $e){  // catch to be able to do a rollback!
				    $db->rollback();
				    throw new \Exception ($e->getMessage());
				}
		
	}

	public function get($instrumentID) {  // TODO- denna är när man klickat pa en song
		
		$db = $this -> connection();

		$sql = "SELECT * FROM $this->dbTable WHERE " . self::$instrumentID . " = ?";
		$params = array($instrumentID);

		$query = $db -> prepare($sql);
		$query -> execute($params);

		$result = $query -> fetch();

		if ($result) {
			$instrument = new \model\Instrument( $result[self::$name], null, $result[self::$instrumentID]);
			
			$sql = "SELECT * FROM ".self::$songTable. " WHERE ".SongRepository::$instrumentID." = ?";  //TODO - check songrepository!
			$query = $db->prepare($sql);
			$query->execute (array($result[self::$instrumentID]));
			$songs = $query->fetchAll();
			
			foreach($songs as $song) {
				$newSong = new Song($song[self::$name], $song[self::$songID], $song[self::$instrumentIDFK]);  
				$instrument->add($newSong);
			}
			return $instrument;
		}

		return null;
	}

	public function getInstrumentID($name, $username) {
		$db = $this -> connection();

		$sql = "SELECT * FROM $this->dbTable WHERE " . self::$name . " = ? AND " . self::$userIDFK . "= ?";
		$params = array($name, $username);

		$query = $db -> prepare($sql);
		$query -> execute($params);

		$result = $query -> fetch();	
		
		return $result->name;	
			
    }

	public function delete(\model\Instrument $instrument, $username) {
			
		// TODO - delete all songs from instrument FIRST!
		
		$db = $this -> connection();

		$sql = "DELETE FROM $this->dbTable WHERE " . self::$instrumentID . "= ?";
		$params = array($instrument -> getInstrumentId());

		$query = $db -> prepare($sql);
		$query -> execute($params);
		
		// unset and set session and get main instrument id from user
		$mainInstrument = $this->getMainInstrument($username);
		$this->misc->unsetSession();
		$this->misc->setInstrumentID($mainInstrument);
	}

	public function toList() {
		
		try {
			$db = $this -> connection();

			$sql = "SELECT * FROM $this->dbTable";
			$query = $db -> prepare($sql);
			$query -> execute();

			foreach ($query->fetchAll() as $owner) {
				$name = $owner[self::$name];
				$instrumentID = $owner[self::$instrumentID];  

				$instrument = new Instrument($name, null, $instrumentID);   

			
			 // Add songs to instrument (to be able to count them)
			 	
			 	//Select song from song  
				$sql = "SELECT * FROM ".self::$songTable. " WHERE ".SongRepository::$instrumentID." = ?";  
				$query = $db->prepare($sql);
				$query->execute (array($instrumentID));
				$songs = $query->fetchAll(); 
			 
				// Add song to song
				foreach($songs as $song) { 
					$newSong = new Song($song[self::$name], $song[self::$songID], $song[self::$instrumentIDFK]);  
					$instrument->add($newSong);
				}	
			
				$instrument->setInstrumentID($instrumentID);  
		
		//TODO click on an instrument from the list to set session ID
				$this->instruments->add($instrument);   
			}
			
			return $this->instruments;
			
		} catch (\PDOException $e) {
			echo '<pre>';
			var_dump($e);
			echo '</pre>';

			die('Error while connection to database.');
		}
	}
	
	/**
	 * @return int
	 */
	public function getMainInstrument($username) {
		
				$db = $this->connection();
				
				// SELECT (InstrumentIDFK (main instrument) from usertable) //
				$sql= "SELECT `". self::$instrumentIDFK . "` FROM `". self::$userTable . "` WHERE `". self::$username . "` = '".$username. "' LIMIT 1";
				$query = $db->prepare($sql);
				$query->execute();
				$result= $query->fetch(\PDO::FETCH_ASSOC);
				// END SELECT
				
		return $result[self::$instrumentIDFK];
	}
	
	public function updateMainInstrument($instrumentID, $username) { // TODO -NEED TO ADD TO THE RIGHT USER!
		
		try { 
				$db = $this->connection();
				$db->beginTransaction();
				
				// SELECT (userID from usertable) //
				$sql= "SELECT `". self::$userID . "` FROM `". self::$userTable . "` WHERE `". self::$username . "` = '".$username. "' LIMIT 1";
				$query = $db->prepare($sql);
				$query->execute();
				$result= $query->fetch(\PDO::FETCH_ASSOC);
				// END SELECT
							
				// UPDATE (InstrumentID into usertable) //
				$sql = "UPDATE ". self::$userTable . "
		        SET ". self::$instrumentIDFK . "=?
				WHERE " . self::$userID . "=?";
				
				$params = array($instrumentID, $result[self::$userID]);
				$query = $db->prepare($sql);
				$query->execute($params);
				// END UPDATE //
				
				$db->commit();  //commits the transaction if it is succesfull   
				
				//unsets and sets session with instrumentID	
				$this->misc->unsetSession();
				$this->misc->setInstrumentID($instrumentID);
		}
				catch(Exception $e){  // catch to be able to do a rollback!
				    $db->rollback();
				    throw new \Exception ($e->getMessage());
				}		
			
		}
}
