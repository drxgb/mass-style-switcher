<?php

namespace App;
use DB\Connection;
use App\Service\StyleService;


class App
{
	/** @var Connection|null */
	private Connection|null $connection;
	/** @var StyleService */
	private StyleService $service;

	private static array $config;


	private const OPT_SAVE = 1;
	private const OPT_LOAD = 2;
	private const OPT_APPLY = 3;
	private const OPT_EXIT = 4;

	public function __construct(array $config) {
		self::$config = $config;
	}


	/**
	 * Recebe um array com as configurações da aplicação.
	 * @param string $key
	 * @return array
	 */
	public static function config(string $key): array
	{
		return self::$config[$key];
	}

	/**
	 * Inicalizar aplicação.
	 * @return void
	 */
	public function start(): void
	{
		// Inicialização do sistema
		GUI::title(self::$config['app']['name']);
		echo PHP_EOL;
		echo 'Tentando estabelecer conexão com o bando de dados ' . self::$config['db']['schema'] . '...' . PHP_EOL;
		$this->attemptToConnect();

		// Ciclo principal do sistema
		$this->service = new StyleService($this->connection);
		$this->mainMenu();

		// Encerramento do sistema
		$this->closeConnection();
	}


	/**
	 * Mostra o menu principal da aplicação.
	 * @return void
	 */
	protected function mainMenu(): void
	{
		/** @var string */
		$question = PHP_EOL . 'Menu Principal' . PHP_EOL;
		$question .= '==============' . PHP_EOL;
		$question .= '1 - Salvar estilos dos usuários em um arquivo' . PHP_EOL;
		$question .= '2 - Carregar estilos dos usuários de um arquivo' . PHP_EOL;
		$question .= '3 - Aplicar um estilo para todos os usuários' . PHP_EOL;
		$question .= '4 - Sair' . PHP_EOL;
		$question .= 'Escolha uma opção (1-4):';
		while (($option = GUI::inputWithQuestion($question)) != self::OPT_EXIT)
		{
			echo $option . PHP_EOL;
			switch ($option)
			{
				case self::OPT_SAVE:
					$this->saveStyles();
					break;
				case self::OPT_LOAD:
					$this->loadStyles();
					break;
				case self::OPT_APPLY:
					$this->applyStyles();
					break;
				default:
					echo 'Comando inválido!' . PHP_EOL;
			}
		}
	}


	/**
	 * Salvar estilos a um arquivo.
	 * @return void
	 */
	protected function saveStyles(): void
	{
		/** @var string */
		$filename = GUI::inputWithQuestion('Escolha um nome para o arquivo (deixe vazio para cancelar):');

		if (!empty($filename))
		{
			/** @var string */
			$filePath = $this->storagePath($filename);
			echo PHP_EOL;
			if (is_file($filePath))
			{
				/** @var string */
				$answer = strtoupper(GUI::inputWithQuestion("O arquivo $filePath já existe. Deseja sobrescrevê-lo? (Y/N):"));

				if ($answer === 'N')
					return;
			}

			echo 'Recuperando os usuários e seus estilos...' . PHP_EOL;
			$this->service->save($filePath);
			echo 'Registros foram salvos em ' . $filePath . PHP_EOL;
		}
	}


	/**
	 * Carregar os estilos de um arquivo e aplicar ao banco de dados.
	 * @return void
	 */
	protected function loadStyles(): void
	{
		/** @var string */
		$filename = GUI::inputWithQuestion('Insira o nome do arquivo sem a extensão (deixe vazio para cancelar):');

		if (!empty($filename))
		{
			/** @var string */
			$filePath = $this->storagePath($filename);
			echo PHP_EOL;
			echo 'Abrindo ' . $filePath . '...' . PHP_EOL;

			if (is_file($filePath))
			{
				/** @var array<object> */
				$users = json_decode(file_get_contents($filePath));
				/** @var int */
				$size = count($users);

				foreach ($users as $u => $user)
				{
					/** @var int */
					$i = intval($u) + 1;

					echo "[$i/$size] Carregando estilo em $user->username... ";
					$this->service->apply($user->style_id, $user->user_id);
					echo 'OK!' . PHP_EOL;
				}
				echo 'Os registros do banco de dados foram atualizados conforme o arquivo carregado.' . PHP_EOL;
			}
			else
			{
				echo "Não foi possível encontrar o arquivo $filePath!" . PHP_EOL;
			}
		}
	}


	/**
	 * Aplica o estilo para todos os usuários.
	 * @return void
	 */
	protected function applyStyles(): void
	{
		/** @var string */
		$answer = GUI::inputWithQuestion('Insira o ID do estilo para qual será alterado (deixe vazio para cancelar):');

		if ($answer)
		{
			/** @var int */
			$styleId = intval($answer);

			echo PHP_EOL;
			if ($styleId > 0)
			{
				echo 'Aplicando o estilo para todos os usuários do banco de dados...' . PHP_EOL;
				$this->service->apply($styleId);
				echo 'Estilo aplicado a todos os usuários com sucesso!' . PHP_EOL;
			}
			else
			{
				echo 'ID de estilo inválido!' . PHP_EOL;
			}
		}
	}


	/**
	 * Estabelecer uma conexão com o banco de dados.
	 * @return void
	 */
	protected function attemptToConnect(): void
	{
		/** @var array */
		$db = self::$config['db'];
		$this->connection = new Connection($db['host'], $db['port'], $db['user'], $db['password'], $db['schema']);
		$this->connection->connect(true);
	}


	/**
	 * Encerra a conexão com o banco de dados.
	 * @return void
	 */
	protected function closeConnection(): void
	{
		$this->connection = null;
		echo 'A conexão com o Banco de Dados foi encerrada...' . PHP_EOL;
	}


	/**
	 * Recebe o diretório do arquivo de armazenamento de dados.
	 * @param string $filename
	 * @return string
	 */
	protected function storagePath(string $filename): string
	{
		/** @var string */
		$dir = self::$config['storage']['app_dir'];

		if (!is_dir($dir))
			mkdir($dir, 0755);
		return $dir . $filename . '.json';
	}
}
