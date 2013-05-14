<?php
/**
 * Обработчик ошибок
 */
namespace Tools;

class ErrorHandler
{

	/**
	 * @var callback
	 */
	private static $callback_500;

	/**
	 * @var boolean
	 */
	private static $is_development;

	/**
	 * Зарегистрировать автоподгрузчик
	 *
	 * @static
	 * @param boolean $is_development
	 * @param callback|null $callback_500
	 * @return void
	 */
	static public function register ($is_development, $callback_500 = null)
	{
		self::$is_development = $is_development;

		set_error_handler (array (__CLASS__, 'error'), E_ALL | E_STRICT);

		if ($is_development === false) {
			set_exception_handler (array (__CLASS__, 'exceptionProduction'));

			if (is_callable ($callback_500)) {
				self::$callback_500 = $callback_500;
			}
		}
	}

	/**
	 * Отлов ошибок, бой
	 *
	 * @static
	 * @param int $errno
	 * @param int $errstr
	 * @param int $errfile
	 * @param int $errline
	 * @return false
	 */
	static public function error ($errno, $errstr, $errfile, $errline, $errcontext)
	{
		if (error_reporting () === 0) {
			//однако собака (@), ничего не делаем
			return false;
		}

//		$a = func_get_args ();
//		var_dump ($a);
//		var_dump (error_reporting ());
//		echo 123;
//		die;

		//сверяемся с текущим уровнем обработки ошибок - надо ли обрабатывать ошибку?
		if (error_reporting () & $errno) {
			//обрабатываем

			switch ($errno) {
				case E_ERROR:
					$errno_str = 'E_ERROR';
				   break;
				case E_WARNING:
					$errno_str = 'E_WARNING';
				   break;
				case E_PARSE:
					$errno_str = 'E_PARSE';
				   break;
				case E_NOTICE:
					$errno_str = 'E_NOTICE';
				   break;
				case E_CORE_ERROR:
					$errno_str = 'E_CORE_ERROR';
				   break;
				case E_CORE_WARNING:
					$errno_str = 'E_CORE_WARNING';
					break;
				case E_COMPILE_ERROR:
					$errno_str = 'E_COMPILE_ERROR';
				   break;
				case E_COMPILE_WARNING:
					$errno_str = 'E_COMPILE_WARNING ';
				   break;
				case E_USER_ERROR  :
					$errno_str = 'E_USER_ERROR';
				   break;
				case E_USER_WARNING:
					$errno_str = 'E_USER_WARNING';
				   break;
				case E_USER_NOTICE:
					$errno_str = 'E_USER_NOTICE';
				   break;
				case E_STRICT:
					$errno_str = 'E_STRICT';
				   break;
				case E_DEPRECATED:
					$errno_str = 'E_DEPRECATED';
				   break;
				case E_USER_DEPRECATED:
					$errno_str = 'E_USER_DEPRECATED';
				   break;
				default:
					$errno_str = 'UNKNOWN';
			}

			$log = 'Error (' . $errno_str . ', ' . $errno . '): ' .
				$errstr . ' in ' . $errfile . ' on line ' . $errline;

			if (self::$is_development) {
				//девел

				flush ();

				if (function_exists ('xdebug_print_function_stack')) {
					//xdebug
					xdebug_print_function_stack ($log . '<br/>');
				} else {
					//no xdebug
					print ($log);
				}

				die;
			} else {
				//бой

				//пишем в лог:
				//место ошибки
				$log .= "\n";

				//трассировку
				$log .= self::trace2log (debug_backtrace  ());

				//запрошенная страница
				$log .= self::enviromentToString ();

//				print ("<pre>$log</pre>");
//				die;

				error_log (rtrim ($log));

				if ($errno !== E_NOTICE && self::$callback_500) {
					//если не нотайс - показываем страницу 500 ошибки
					call_user_func (self::$callback_500);
					die;
				}
			}
		} else {
			//ошибка не подпадает под текущий уровень отлова
			return false;
		}
	}

	/**
	 * Заглушка для обработчика ошибок, сбрасывающий буфер вывода при ошибки
	 *
	 * @static
	 * @return false
	 */
	static public function exceptionProduction (\Exception $exception)
	{
		//пишем в лог:
		//место ошибки
		$log = 'Exception: ' . $exception->getMessage () .
			' in ' . $exception->getFile () . ' on line ' . $exception->getLine () . "\n";

		//трассировку
		$log .= self::trace2log ($exception->getTrace ());

		//запрошенная страница
		$log .= self::enviromentToString ();

//		print ("<pre>$log</pre>");
//		die;

		error_log (rtrim ($log));

		if (self::$callback_500) {
			//показываем страницу 500 ошибки
			call_user_func (self::$callback_500);
		}

		//execute PHP internal error handler
		return false;
	}

	/**
	 * Запрошенная страница
	 *
	 * @return string
	 */
	static private function enviromentToString ()
	{
		$log = '';

		if (isset ($_SERVER['SERVER_NAME'], $_SERVER['SERVER_PORT'], $_SERVER['REQUEST_URI'])) {
			$log .= "URL: " . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'] . "\n";

			if (isset ($_SERVER['HTTP_REFERER']) && !empty ($_SERVER['HTTP_REFERER'])) {
				$log .= 'HTTP_REFERER: ' . $_SERVER['HTTP_REFERER'] . "\n";
			}

			//post/cookies/session/files
			if (isset ($_POST) && !empty ($_POST)) {
				$log .= '$_POST: ' . print_r ($_POST, true);
			}
			if (isset ($_FILES) && !empty ($_FILES)) {
				$log .= '$_FILES: ' . print_r ($_FILES, true);
			}
			if (isset ($_COOKIE) && !empty ($_COOKIE)) {
				$log .= '$_COOKIE: ' . print_r ($_COOKIE, true);
			}
			if (isset ($_SESSION) && !empty ($_SESSION)) {
				$log .= '$_SESSION: ' . print_r ($_SESSION, true);
			}
		}

		return rtrim ($log);
	}

	/**
	 * Текстовое представления массива трассировки
	 *
	 * @param array $trace
	 * @return string
	 */
	static private function trace2log (array $trace)
	{
//		var_dump ($trace);
		array_shift ($trace);

		$log = '';

		foreach ($trace as $key => $tmp) {
			$log .= "#$key ";

			if (isset ($tmp['class'])) {
				$log .= $tmp['class'];
			}

			if (isset ($tmp['type'])) {
				$log .= $tmp['type'];
			}

			if (isset ($tmp['function'])) {
				$log .= $tmp['function'];
			}

			if (isset ($tmp['file'])) {
				$log .= ' in ' . $tmp['file'];
			}

			if (isset ($tmp['line'])) {
				$log .= ' on line ' . $tmp['line'];
			}

			$log = rtrim ($log) . "\n";
		}

		return $log;
	}

}
