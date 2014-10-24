<?php
namespace model;

require_once ('./src/model/m_instrument.php');
require_once ('./src/model/m_instrumentList.php');
require_once ('./src/model/m_songRepository.php');
require_once ('./src/model/base/Repository.php');
require_once("src/helper/SessionHelper.php");

class InstrumentRepository extends base\Repository {
	private $instruments;
	private $sessionHelper;
	
	//DB fields
	private static $instrumentID = 'instrumentID';
	private static $name = 'name';
	private static $userID = 'userID';
	private static $username ='username';
	private static $userIDFK = 'userIDFK';
	public static $instrumentIDFK = 'instrumentIDFK';
	private static $songID = 'songID';
	
	//DB tables
	private static $songTable = 'song';
	private static $userTable = 'user';
	

	public function __construct() {
		$this -> dbTable = 'instrument';
		$this -> instruments = new InstrumentList();
		$this->sessionHelper = new \helper\SessionHelper();
	}
	
	public function add($instrumentName, $username) {  
	

		try {
			 	$db = $this->connection();
				$db->beginTransaction();  // TODO- might not need begin/end transaction
				
				$userIDFK = $this->getUserID($username);
			
				//check if song already exists in database
				if ($this->nameAlreadyExists($instrumentName, $userIDFK)) {
					$this->sessionHelper->setAlert("You already have an instrument called '". $instrumentName . "'. </p><p>Please choose a new name.");	
					return null;	
				}
				
				//everything ok, add instrument!
				
				// INSERT (instrument into database) //
				$sql = "INSERT INTO $this->dbTable (". self::$instrumentID . ", " . self::$name . " , " . self::$userIDFK . ") VALUES (  ?, ?, ?)";
				$params = array("", strtoupper($instrumentName), $userIDFK);
		
				$query = $db->prepare($sql);
				$query->execute($params);
				// END INSERT //
				
				$instrumentID = $db->lastInsertId(); 
				$db->commit();  //commits the transaction if it is succesfull   
				
				
				return $instrumentID;		
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

	public function getInstrumentID($name, $username) {  //TODO check if used!
		$db = $this -> connection();

		$sql = "SELECT * FROM $this->dbTable WHERE " . self::$name . " = ? AND " . self::$userIDFK . "= ?";
		$params = array($name, $username);

		$query = $db -> prepare($sql);
		$query -> execute($params);

		$result = $query -> fetch();	
		
		return $result->name;	
			
    }

	public function delete(\model\Instrument $instrument, $username) {
			
		
		$mainInstrumentID = $this->getMainInstrument($username);
		$instrumentID = $instrument -> getInstrumentId();
		
		//if main instrument is going to be deleted, set main to 0 in usertable
		if ($mainInstrumentID == $instrumentID)
		{	
			$this->updateMainInstrument(0, $username);
			$mainInstrumentID = 0;
		}
	
		$db = $this -> connection();

		//delete songs from songtable
		$sql = "DELETE * FROM". self::$songTable. "WHERE" . self::$instrumentID . "= ?";  
		$params = array($instrument -> getInstrumentId());
		
		//delete instrument from instrument table
		$sql = "DELETE FROM $this->dbTable WHERE " . self::$instrumentID . "= ?";
		$params = array($instrument -> getInstrumentId());

		$query = $db -> prepare($sql);
		$query -> execute($params);
		
		// unset and set session and get main instrument id from user
		$mainInstrumentID = $this->getMainInstrument($username);
		$this->sessionHelper->unsetSession();
		$this->sessionHelper->setInstrumentID($mainInstrumentID);
	}
	
	public function toList($username) {
		
		try {
			$db = $this -> connection();
			
			$userIDFK = $this->getUserID($username);
			
			$sql = "SELECT * FROM `". $this->dbTable. "` WHERE " . self::$userIDFK . "= ?";
			$params = array($userIDFK);
			$query = $db -> prepare($sql);
			$query -> execute($params);

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
	/** To prevent the user to create an instrument that already exists 
	 * @return bolean
	 */
	public function nameAlreadyExists($instrumentName, $userID) {
		
			$db = $this->connection();
			$sql = "SELECT * FROM $this->dbTable WHERE `" .self::$name . "` = ? AND `" .self::$userIDFK . "` = ?";
			$params = array($instrumentName, $userID );
			$query = $db->prepare($sql);
			$query->execute($params);
			
			if ($query->rowCount() > 0) 
        		return true;

			return false;
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
	/*
	 * @return UserID from db user table
	 */
	public function getUserID($username) {
					
				$db = $this -> connection();
				// SELECT (userID from usertable) //
				$sql= "SELECT `". self::$userID . "` FROM `". self::$userTable . "` WHERE `". self::$username . "` = '".$username. "' LIMIT 1";
				$query = $db->prepare($sql);
				$query->execute();
				$result= $query->fetch(\PDO::FETCH_ASSOC);
				// END SELECT
				
				return $result[self::$userID];
	}
	
	public function updateMainInstrument($instrumentID, $username) { 
		
				$db = $this->connection();
				$db->beginTransaction();
							
				$userID = $this->getUserID($username);
							
				// UPDATE (InstrumentID into usertable) //
				$sql = "UPDATE ". self::$userTable . "
		        SET ". self::$instrumentIDFK . "=?
				WHERE " . self::$userID . "=?";
				
				$params = array($instrumentID, $userID);  //TODO check that update main instrument works!
				$query = $db->prepare($sql);
				$query->execute($params);
				// END UPDATE //
				
				$db->commit();  //commits the transaction if it is succesfull   
				
				//unsets and sets session with instrumentID	
				$this->sessionHelper->unsetSession();
				$this->sessionHelper->setInstrumentID($instrumentID);
	
		}
}
