<?php
namespace Tools;

class Cli
{
	/**
	 * Выводит лог в stdout в формате:
	 *     имя_файла_скрипта:строка [дата время]: текст
	 *
	 * @param string $message
	 * return void
	 */
	public static function log($message)
	{
		$backtrace = debug_backtrace();

		print basename($backtrace[0]["file"]) . ':' . vsprintf ('%-4d', $backtrace[0]["line"]) .
			' [' . date('Y-m-d H:i:s') . '] ' . $message . "\n";
	}
}
