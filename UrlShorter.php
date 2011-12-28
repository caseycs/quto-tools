<?php
namespace Tools;

class UrlShorter
{

	static $codeset = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	static $base = 62;

	/**
	 * id2hash
	 *
	 * @param int $id
	 * @return string|false
	 */
	public static function id2hash ($id)
	{
		if ((string)(int)$id !== (string)$id) {
			return false;
		}

		$result = '';

		while ($id > 0) {
		  $result = substr (self::$codeset, ($id % self::$base), 1) . $result;
		  $id = floor ($id / self::$base);
		}

		return $result;
	}

	/**
	 * hash2id
	 *
	 * @param string $id
	 * @return int|false
	 */
	public static function hash2id ($hash)
	{
		if (preg_match ("|^[" . self::$codeset . "]+$|", $hash) !== 1) {
			return false;
		}

		$result = 0;

		for ($i = strlen ($hash); $i; $i--) {
			$result += strpos (self::$codeset, substr ($hash, (-1 * ( $i - strlen ($hash) )),1))  *
				pow (self::$base, $i-1);
		}

		return $result;
	}

}
