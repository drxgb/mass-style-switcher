<?php

namespace App;


abstract class GUI
{
	/**
	 * Escreve uma linha no prompt.
	 * @return void
	 */
	public static function writeLine(): void
	{
		echo '=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=' . PHP_EOL;
	}


	/**
	 * Escreve o nome da aplicação na tela.
	 * @param string $name
	 * @return void
	 */
	public static function title(string $name): void
	{
		self::writeLine();
		echo "\t" . strtoupper($name) . PHP_EOL;
		self::writeLine();
	}


	/**
	 * Envia uma pergunta e o usuário responde através da entrada da tecla.
	 * @param string $question
	 * @return string
	 */
	public static function inputWithQuestion(string $question): string
	{
		echo $question . ' ';
		return trim(fgets(STDIN));
	}
}
