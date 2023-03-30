<?php

namespace App\Repository;
use PDO;
use App\App;
use PDOStatement;
use DB\Connection;


class StyleRepository
{
	/** @var string */
	private string $table;


	public function __construct(private Connection $connection)
	{
		$this->table = App::config('db')['user_table'];
	}


	/**
	 * Recebe todos os estilos
	 * @param array|int|string|null $columns
	 * @return array
	 */
	public function getAll(array|string|int|null $columns = null): array
	{
		if (is_array($columns))
			$columns = implode(', ', $columns);
		elseif (is_null($columns))
			$columns = '*';

		/** @var string */
		$query = "SELECT $columns FROM $this->table";
		/** @var PDOStatement */
		$stmt = $this->connection->get()->query($query);

		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}


	/**
	 * Atualiza o estilo para todos os usuários.
	 * @param int $styleId
	 * @return void
	 */
	public function updateAll(int $styleId): void
	{
		/** @var string */
		$styleColumn = App::config('db')['style_column'];
		/** @var string */
		$query = "UPDATE $this->table SET $styleColumn = :styleId";

		/** @var PDOStatement */
		$stmt = $this->connection->get()->prepare($query);
		$stmt->bindValue(':styleId', $styleId, PDO::PARAM_INT);
		$stmt->execute();
	}


	/**
	 * Atualiza o estilo para um único usuário.
	 * @param int $styleId
	 * @param int $userId
	 * @return void
	 */
	public function update(int $styleId, int $userId): void
	{
		/** @var string */
		$styleColumn = App::config('db')['style_column'];
		/** @var string */
		$userColumn = App::config('db')['user_key'];

		/** @var string */
		$query = "UPDATE $this->table SET $styleColumn = :styleId WHERE $userColumn = :userId";

		/** @var PDOStatement */
		$stmt = $this->connection->get()->prepare($query);
		$stmt->bindValue(':styleId', $styleId, PDO::PARAM_INT);
		$stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
		$stmt->execute();
	}
}
