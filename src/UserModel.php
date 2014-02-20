<?php
namespace Development;

class UserModel
{
	/**
	 * @var \PDO
	 */
	private $data_base;

	/**
	 * @throws \RuntimeException
	 */
	public function getDatabaseConnection()
	{
		try
		{
			$this->data_base = new \PDO( "mysql:host=127.0.0.1;dbname=mysql", 'root', 'root' );
			$this->data_base->setAttribute( \PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC );
		} catch ( \PDOException $e )
		{
			throw new \RuntimeException( 'Database is down', 10 );
		}
	}

	/**
	 * @param $user_name
	 * @return bool
	 */
	public function existsUserName( $user_name )
	{
		$this->getDatabaseConnection();

		$sql = <<<SQL
SELECT
	user_name
FROM
	users
WHERE
	user_name = '$user_name'
SQL;
		$result = $this->data_base->prepare( $sql )->execute( array() )->fetchAll();

		return !empty( $result );
	}

	/**
	 * @param $user_data
	 * @return \PDOStatement
	 */
	public function addNewUser( $user_data )
	{
		$this->getDatabaseConnection();

		$sql = <<<SQL
INSERT INTO
	users
SET
	user_name 		= '{$user_data['user_name']}',
	email 			= '{$user_data['email']}',
	password 		= '{$user_data['password']}',
	activation_key 	= '{$user_data['activation_key']}'
SQL;
		return $this->data_base->query( $sql );
	}

}