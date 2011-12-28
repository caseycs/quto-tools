<?php
namespace Tools;

class ArrayObject
{

	/**
	 * Проверка наличия дубликатов в ArrayObject
	 *
	 * @param ArrayObject $a
	 * @return bool
	 */
	public static function isDuplicates (\ArrayObject $a)
	{
		$tmp = array ();
		foreach ($a as $b) {
			if (in_array ($b, $tmp, true)) {
				return true;
			}
			$tmp[] = $b;
		}
		return false;
	}

	/**
	 * Поиск в ArrayObject элемента $item, в случае нахождения возврат найденного индекса
	 *
	 * @param ArrayObject $a, mixed $item
	 * @return int|bool
	 */
	public static function searchOffset (\ArrayObject $a, $item)
	{
		foreach ($a as $i => $b) {
			if ($b === $item) {
				return $i;
			}
		}

		return false;
	}
	
}

