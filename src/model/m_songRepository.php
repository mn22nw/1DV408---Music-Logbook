<?php
namespace model;

require_once ('./src/model/base/Repository.php');
require_once ('./src/model/m_songList.php');

class SongRepository extends base\Repository {
	private $songs;
	
	private static $name = 'name';
	private static $songID = 'songID';
	public static $instrumentID = 'instrumentIDFK';
	
	public function __construct() {
		$this -> dbTable = 'song';
		$this -> songs = new SongList();
	}

	public function add(Song $song) {
		$db = $this -> connection();

		// GET FOLDERID FIRST ?//


		$sql = "INSERT INTO $this->dbTable (". self::$songID . ", " . self::$name . ",  ".self::$instrumentID.") VALUES (?, ?, ?)";
		$params = array("",  $song -> getName(), $song->getOwner()->getinstrumentID());

		$query = $db -> prepare($sql);
		$query -> execute($params);
	}

	public function get($songID) {
		$db = $this -> connection();

		$sql = "SELECT * FROM $this->dbTable WHERE " . self::$songID. " = ?";
		$params = array($songID);

		$query = $db -> prepare($sql);
		$query -> execute($params);

		$result = $query -> fetch();

		if ($result) {
			return new \model\Song($result[self::$name], $result[self::$songID],$result[self::$songID]);  // TODO - add params here!
		}
	}

	public function delete($songID, $instrumentID) {
			
		$db = $this -> connection();

		$sql = "DELETE FROM $this->dbTable WHERE " . self::$songID. "= ? AND ". self::$instrumentID. "= ?" ;
		$params = array($songID, $instrumentID);

		$query = $db -> prepare($sql);
		$query -> execute($params);
		
	}

	public function query($sql, $params = NULL) {
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
