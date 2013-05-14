<?php
/**
 * Примитивный класс для отладки - сохранение значений переменных в файл
 *
 * Может дописать в существующий файл, записать в новый, а также дописать в файл текущего запроса
 */
namespace Tools;

class Debug
{

	static $current;
	static $path;

	/**
	 * Задать путь к файлу с данными отладки
	 *
	 * @param string $path
	 * @return void
	 */
	public static function setPath ($path)
	{
		self::$path = $path;
	}

	/**
	 * Дописать содержимое переменной
	 *
	 * @param mixed $variable
	 * @return void
	 */
	public static function a ($variable)
	{
		if (self::$path === null) {
			throw new \Exception ('path not defined');
		}

		if (self::$current === null || is_file (self::$path) == false) {
			file_put_contents (self::$path, '');
		}

		self::$current = true;

		$fh = fopen (self::$path, 'a');
		fwrite ($fh, self::presentPlain ($variable));
		fclose ($fh);
	}

	/**
	 * Представить переменную в текстовом виде
	 *
	 * @param mixed $variable
	 * @return string
	 */
	public static function presentPlain ($variable)
	{
		return trim (print_r ($variable, true)) . "\n\n";
	}

}
