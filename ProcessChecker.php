<?php
namespace Tools;

class ProcessChecker
{

	/**
	 * Проверить работает ли процесс, pid которого записал в файле $pidfile
	 *
	 * @static
	 * @param string $pidfile пусть к файлу с pid процесса
	 * @param boolean $unlink_on_not_running грохать ли файд, если процесса нет
     * @return boolean
	 */
	public static function isRunning ($pidfile, $unlink_on_not_running)
	{
		if (is_file ($pidfile)) {
			$pid = (int)substr (file_get_contents ($pidfile), 0, 10);

			if (is_dir ('/proc/' . $pid) === true) {
				//процесс крутится
				return true;
			} else {
				//нет такого процесса§
				if ($unlink_on_not_running) {
					unlink ($pidfile);
				}
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	 * Сохранить pid текущего процесса в файл
	 *
	 * @static
	 * @param string $filename
	 * @return boolean
	 */
	public static function saveCurrentPid ($filename)
	{
		return file_put_contents ($filename, getmypid ()) !== false;
	}

}
