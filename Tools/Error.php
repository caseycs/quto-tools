<?php
/**
 * Контейнер ошибок
 */
namespace Tools;

class Error
{

	/**
	 * Объект, к которму относятся ошибки
	 * Актуально для вложенных ошибок
	 *
	 * @var object
	 */
	private $object;

	/**
	 * Ошибки
	 *
	 * @var array
	 */
	private $errors = array ();

	/**
	 * Ошибки вложенных объектов
	 *
	 * @var array
	 */
	private $nested = array ();


	/**
	 * Ассоциативный массив дополнительных свойств ошибки
	 *
	 * @var array
	 */
	private $properties = array ();

	/**
	 * Добавить ошибку
	 *
	 * @param int $id
	 * @return void
	 */
	public function append ($id)
	{
		$this->errors[$id] = true;
	}

	/**
	 * Добавить ошибку связанного объекта
	 *
	 * @param Error ошибка
	 * @return void
	 */
	public function appendNested (Error $error)
	{
		$this->nested[] = $error;
	}

	/**
	 * Есть ли ошибка
	 *
	 * @param int $id
	 * @return bool
	 */
	public function isExists ($id)
	{
		return isset ($this->errors[$id]);
	}

	/**
	 * Получить все ошибки
	 *
	 * @return array
	 */
	public function getAllErrors ()
	{
		return array_keys ($this->errors);
	}

	/**
	 * Получить все ошибки
	 *
	 * @return array
	 */
	public function getNested ()
	{
		return $this->nested;
	}

	/**
	 * Есть ли ошибки
	 *
	 * @return bool
	 */
	public function isEmpty ()
	{
		return count ($this->errors) === 0 && count ($this->nested) === 0;
	}

	/**
	 * Объект, с которым связана ошибка
	 *
	 * @return object
	 */
	public function getObject ()
	{
		return $this->object;
	}

	public function setObject ($object)
	{
		$this->object = $object;
	}

	/**
	 * Дополнительные свойства ошибки
	 *
	 * @param mixed $key
	 * @return mixed
	 */
	public function getProperty ($key)
	{
		return isset ($this->properties[$key]) ? $this->properties[$key] : null;
	}

	/**
	 * Дополнительные свойства ошибки
	 *
	 * @param mixed $key
	 * @param mixed $value
	 * @return void
	 */
	public function setProperty ($key, $value)
	{
		$this->properties[$key] = $value;
		return null;
	}

}
