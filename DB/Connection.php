<?php

namespace DB;

use PDO;

class Connection
{
	/**
	 * @var PDO
	 */
	private $pdo;


	public function __construct(
		private string $host,
		private string $port,
		private string $user,
		private string $password,
		private string $schema
	)
	{}


	/**
	 * Tenta estabelecer uma conexão com o banco de dados.
	 * @param bool $persistent
	 * @return PDO
	 */
	public function connect(bool $persistent = false) : PDO
	{
		try
		{
			$this->pdo = new PDO(
				'mysql:host=' . $this->host . ';dbport=' . $this->port .';dbname=' . $this->schema,
				$this->user,
				$this->password,
				[
					PDO::ATTR_PERSISTENT => $persistent,
				]
			);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch (\Exception $e)
		{
			echo $e->getMessage() . PHP_EOL;
		}

		return $this->pdo;
	}


	public function get() : PDO
	{
		return $this->pdo;
	}
}
