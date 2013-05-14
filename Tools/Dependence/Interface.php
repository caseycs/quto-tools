<?php
/**
 * Интерфейс описания прямых связей объектов
 */
namespace Tools;

interface Dependence_Interface
{

	/**
	 * Имя класса, связи которого описываются
	 *
	 * @return string
	 */
	public function getClassName ();

	/**
	 * Получить зависимые объекты
	 *
	 * @param $object
	 * @return ArrayObject
	 */
	public function getDependencies ($object);

	/**
	 * Есть ли зависимые объекты
	 *
	 * @param $object
	 * @return boolean
	 */
	public function isDependencies ($object);

	/**
	 * Грохнуть зависимости
	 *
	 * @param $object
	 * @return boolean
	 */
	public function removeDependencies ($object);

}
