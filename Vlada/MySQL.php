<?php
namespace Vlada;

use PDO;
use PDOStatement;

class MySQL
{
	/**
	 * @var PDO
	 */
    private $pdo;
    
	/**
     * Инициализация подключения
     */
	function __construct( $db_host, $db_user, $db_pass, $db_name, $source = '' )
	{
		$this->pdo = new PDO(
			'mysql:host='. $db_host .';dbname='. $db_name .';charset=utf8',
			$db_user,
			$db_pass,
			array(
				PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_ASSOC, // PDO::FETCH_LAZY
				PDO::ATTR_PERSISTENT			=> false,
				PDO::MYSQL_ATTR_INIT_COMMAND	=> "SET sql_mode='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'"
			)
		);

		$this->query("SET NAMES utf8");
	}
	/**
     * Выполнение и результат
     */
	function exec( $sql )
	{
		// Выполнение и результат
		return $this->pdo->exec( $sql );
	}
	/**
	 * Выполнение запроса
	 * @return PDOStatement
	 */
	function query( $sql, $params = array(), $master = false )
	{
		$query = $this->pdo->prepare( $sql );
		$query->execute( $params );

		return $query;
	}

	/**
     * Получение последного вствленого в БД ID
     */
	function getLastId()
	{
		return $this->pdo->lastInsertId();
	}
}
?>