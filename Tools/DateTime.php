<?php
namespace Tools;

class DateTime
{
	/**
	 * Перейти на начало недели
	 *
	 * @param DateTime $datetime
	 * @return DateTime
	 */
	public static function weekStart (\DateTime $datetime)
	{
		return $datetime->sub (new \DateInterval ('P' . ($datetime->format ('N') - 1) . 'D'));
	}

	/**
	 * Перейти на конец недели
	 *
 	 * @param DateTime $datetime
	 * @return Tools\DateTime
	 */
	public static function weekEnd (\DateTime $datetime)
	{
		return $datetime->add (new \DateInterval ('P' . (7 - $datetime->format ('N')) . 'D'));
	}

	/**
	 * Установить последний день месяца
	 *
	 * @param DateTime $datetime
	 * @return void
	 */
	public static function lastDayInMonth (\DateTime $datetime)
	{
		$year = $datetime->format ('Y');
		$month = $datetime->format ('m');

		return $datetime->setDate ($year, $month, cal_days_in_month (CAL_GREGORIAN, $month, $year));
	}

	/**
	 * Установить последний день года
	 *
	 * @param DateTime $datetime
	 * @return void
	 */
	public static function lastDayInYear (\DateTime $datetime)
	{
		$year = $datetime->format ('Y');

		return $datetime->setDate ($year, 12, 31);
	}

	/**
	 * Принадлежит ли дата к текущей неделе
	 *
	 * @return boolean
	 */
	public static function isCurrentWeek (\DateTime $datetime)
	{
		return $datetime->format ('W') === date ('W');
	}

}
