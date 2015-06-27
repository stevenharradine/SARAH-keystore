<?php
	class KeyManagerRecord {
		private $KEYSTORE_ID;
		private $USER_ID;
		private $name;
		private $private_key;
		private $public_key;
		private $passphrase;

		public function __construct ($KEYSTORE_ID, $USER_ID, $name, $private_key, $public_key, $passphrase) {
			$this->KEYSTORE_ID = $KEYSTORE_ID;
			$this->USER_ID	= $USER_ID;
			$this->name = $name;
			$this->private_key = $private_key;
			$this->public_key = $public_key;
			$this->passphrase = $passphrase;
		}

		public function getKeystoreId () {
			return $this->KEYSTORE_ID;
		}
		public function getUserId () {
			return $this->USER_ID;
		}
		public function getName () {
			return $this->name;
		}
		public function getPrivateKey () {
			return $this->private_key;
		}
		public function getPublicKey () {
			return $this->public_key;
		}
		public function getPassphrase () {
			return $this->passphrase;
		}
	}

	class KeyManager extends Application {
		public function isInitialized () {
			$sql = <<<EOD
	SELECT
		COUNT(*) 
	FROM
		INFORMATION_SCHEMA.TABLES 
	WHERE
		TABLE_SCHEMA = 'sarah' 
			AND
		TABLE_NAME = 'keystore'
EOD;
			$db_list = mysql_query ( $sql ) or die (mysql_error());

			return mysql_fetch_array($db_list)['COUNT(*)'];
		}
		public function createDB () {
			$sql = <<<EOD
	CREATE TABLE IF NOT EXISTS `keystore` (
		`KEYSTORE_ID` int(11) NOT NULL AUTO_INCREMENT,
		`USER_ID` int(11) NOT NULL,
		`name` text NOT NULL,
		`private_key` text NOT NULL,
		`public_key` text NOT NULL,
		`passphrase` text NOT NULL,
		`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		PRIMARY KEY (`KEYSTORE_ID`)
	) ENGINE=InnoDB  DEFAULT CHARSET=latin1
EOD;
			return mysql_query ( $sql ) or die (mysql_error());
		}

		public function getAllRecords ($USER_ID) {
			$records = array ();
			$data = mysql_query(<<<EOD
	SELECT
		*
	FROM
		`keystore`;
EOD
			) or die(mysql_error());

			while ( ( $row = mysql_fetch_array( $data ) ) != null) {
				$KEYSTORE_ID = $row['KEYSTORE_ID'];
				$USER_ID = $row['USER_ID'];
				$name = $row['name'];
				$private_key = $row['private_key'];
				$public_key = $row['public_key'];
				$passphrase = $row['passphrase'];

				$records[] = new KeyManagerRecord ($KEYSTORE_ID, $USER_ID, $name, $private_key, $public_key, $passphrase);
			}

			return $records;
		}

		public function updateRecord ($KEYSTORE_ID, $USER_ID, $name, $private_key, $public_key) {
			$sql = <<<EOD
	UPDATE
		`sarah`.`keystore`
	SET
		`USER_ID` = $USER_ID,
		`name` = '$name',
		`private_key` = '$private_key',
		`public_key` = '$public_key'
	WHERE
		KEYSTORE_ID = '$KEYSTORE_ID';
EOD;
			
			return mysql_query($sql) or die(mysql_error());
		}

		public function addRecord ($KEYSTORE_ID, $USER_ID, $name, $private_key, $public_key, $passphrase) {
			echo $KEYSTORE_ID;
			$sql = <<<EOD
	INSERT INTO
		`sarah`.`keystore` (
			`KEYSTORE_ID`,
			`USER_ID`,
			`name`,
			`private_key`,
			`public_key`,
			`passphrase`
		) VALUES (
			'$KEYSTORE_ID',
			'$USER_ID',
			'$name',
			'$private_key',
			'$public_key',
			'$passphrase'
		);
EOD;
			return mysql_query($sql) or die(mysql_error());
		}

		public function deleteRecord ($KEYSTORE_ID, $USER_ID) {
			return mysql_query(<<<EOD
	DELETE FROM
		`sarah`.`keystore`
	WHERE
		`KEYSTORE_ID`='$KEYSTORE_ID'
			AND
		`USER_ID`='$USER_ID'
EOD
) or die(mysql_error());
		}

		public function getRecord ($KEYSTORE_ID, $USER_ID) {
			$sql = <<<EOD
	SELECT
		*
	FROM
		`keystore`
	WHERE
		`KEYSTORE_ID`= $KEYSTORE_ID
			AND
		`USER_ID` = $USER_ID
EOD;
			$data = mysql_query( $sql ) or die(mysql_error());
			$row = mysql_fetch_array( $data );

			$name = $row['name'];
			$private_key = $row['private_key'];
			$public_key = $row['public_key'];
			$passphrase = $row['passphrase'];

			return new KeyManagerRecord ($KEYSTORE_ID, $USER_ID, $name, $private_key, $public_key, $passphrase);
		}
	}