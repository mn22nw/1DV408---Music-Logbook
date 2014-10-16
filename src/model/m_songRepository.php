<?php
namespace model;

require_once ('./src/model/base/Repository.php');
require_once ('./src/model/m_songList.php');

class SongRepository extends base\Repository {
	private $songs;
	
	private static $name = 'name';
	private static $songID = 'songID';
	public static $instrumentID = 'instrumentIDFK';
	public $sessionHelper;
	
	public function __construct() {
		$this -> dbTable = 'song';
		$this -> songs = new SongList();
		$this->sessionHelper = new \helper\SessionHelper();
	}
	/**
	 * 
	 * @return int (songID)
	 */
	public function add(Song $song) {
		
		//check if song already exists in database
		if ($this->nameAlreadyExists($song->getName(), $song->getOwner()->getInstrumentID())) {
			$this->sessionHelper->setAlert("You already have a song called '". $song->getName() . "'. </p><p>Please choose a new name.");	
			return null;	
		}
		else { //everything ok, add song!
			$db = $this -> connection();
			
			$sql = "INSERT INTO $this->dbTable (". self::$songID . ", " . self::$name . ",  ".self::$instrumentID.") VALUES (?, ?, ?)";
			$params = array("", ucfirst($song -> getName()), $song->getOwner()->getInstrumentID());
			$query = $db -> prepare($sql);
			$query -> execute($params);
			$songID = $db->lastInsertId(); 
			return $songID;
		}
		
	}

	public function get($songID) {
		$db = $this -> connection();

		$sql = "SELECT * FROM $this->dbTable WHERE " . self::$songID. " = ?";
		$params = array($songID);
		$query = $db -> prepare($sql);
		$query -> execute($params);

		$result = $query -> fetch();

		if ($result) {
			return new \model\Song($result[self::$name], $result[self::$songID],$result[self::$instrumentID]);  // TODO - add params here!
		}
	}

	public function delete($songID, $instrumentID) {
			
		$db = $this -> connection();

		$sql = "DELETE FROM $this->dbTable WHERE " . self::$songID. "= ? AND ". self::$instrumentID. "= ?" ;
		$params = array($songID, $instrumentID);

		$query = $db -> prepare($sql);
		$query -> execute($params);
		
	}
	
	public function nameAlreadyExists($name, $instrumentID) {
		
			$db = $this->connection();
			$sql = "SELECT * FROM $this->dbTable WHERE `" .self::$name . "` = ? AND `" .self::$instrumentID . "` = ?";
			$params = array($name, $instrumentID );
			$query = $db->prepare($sql);
			$query->execute($params);
			
			if ($query->rowCount() > 0) 
        		return true;

			return false;
	}

	public function query($sql, $params = NULL) {  // TODO - use this when refactoring
		$db = $this -> connection();

		$query = $db -> prepare($sql);
		$result;
		if ($params != NULL) {
			if (!is_array($params)) {
				$params = array($params);
			}

			$result = $query -> execute($params);
		} else {
			$result = $query -> execute();
		}

		if ($result) {
			return $result -> fetchAll();
		}

		return NULL;
		
	}

	public function toList() {  //TODO- when is this called? COME ON!
		try {
			$db = $this -> connection();

			$sql = "SELECT * FROM $this->dbTable";
			$query = $db -> prepare($sql);
			$query -> execute();

			foreach ($query->fetchAll() as $song) {
				$name = $song[self::$name];
				$songID = $song[self::$songID];
				$owner =  $song[self::$instrumentID];
				
				$song = new Song($name, $songID);

				$this->songs ->add($song);
			}

			return $this -> songs;
		} catch (\PDOException $e) {
			echo '<pre>';
			var_dump($e);
			echo '</pre>';

			die('Error while connection to database.');
		}
	}

}
