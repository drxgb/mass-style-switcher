<?php

namespace App\Service;

use App\App;
use DB\Connection;
use App\Repository\StyleRepository;


class StyleService
{
	/** @var StyleRepository */
	private StyleRepository $repository;


	public function __construct(Connection $connection)
	{
		$this->repository = new StyleRepository($connection);
	}


	/**
	 * Recupera todos os usuários e salva os registros encontrados em um arquivo.
	 * @param string $filename
	 * @return void
	 */
	public function save(string $filename): void
	{
		/** @var array */
		$db = App::config('db');

		$styles = $this->repository->getAll([$db['user_key'], $db['username'], $db['style_column']]);
		file_put_contents($filename, json_encode($styles));
	}


	/**
	 * Aplica o estilo ao usuário.
	 * @param int $styleId
	 * @param int|null $userId
	 * @return void
	 */
	public function apply(int $styleId, int|null $userId = null): void
	{
		if (is_null($userId))
			$this->repository->updateAll($styleId);
		else
			$this->repository->update($styleId, $userId);
	}
}
