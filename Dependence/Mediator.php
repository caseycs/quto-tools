<?php
/**
 * Медиатор зависимостей
 */
namespace Tools;

class Dependence_Mediator
	extends \Iir\SingletonAbstract
{

	/**
	 * Лямбда-функция для установки зависимостей
	 */
	private static $configurator;

	/**
	 * @var array
	 */
	private $dependecies = null;

	protected function configure ()
	{
		if (self::$configurator !== null) {
			$this->dependecies = array ();

			$tmp = self::$configurator;
			$tmp ($this);

			unset ($tmp);
		} else {
			throw new Exception ('Dependence_Mediator configurator does not presented');
		}
	}

	/**
	 * @static
	 * @return Dependence_Mediator
	 */
	public static function getInstance ()
	{
		return parent::getInstance ();
	}

	public static function setConfigurator (\Closure $configurator)
	{
		self::$configurator = $configurator;
	}

	/**
	 * Зарегистрировать зависимость
	 *
	 * @param Interface_Dependence $dependence
	 * @return void
	 */
	public function registerDependence (Dependence_Interface $dependence)
	{
		$this->dependecies[$dependence->getClassName ()] = $dependence;
	}

	/**
	 * Получить все зависимые объекты
	 *
	 * @param $object
	 * @return ArrayObject of object
	 */
	public function getDependencies ($object)
	{
		if ($this->dependecies === null) {
			$this->configure ();
		}

		//для исключения дублей при перекрестных зависимостях
		$dependencies_hashes = array ();

		if (isset ($this->dependecies[get_class ($object)])) {
			//зависимости есть

			//прямые зависимости, 1-й уровень вложенности
			$result = $this->dependecies[get_class ($object)]->getDependencies ($object);

			//копаем глубже

			//для результатов более глубокого поиска
			$result2 = new \ArrayObject;

			foreach ($result as $dependence) {
				//чуточку оптимизации
				if (isset ($this->dependecies[get_class ($dependence)])) {
					foreach ($this->getDependencies ($dependence) as $tmp) {
						if (in_array (spl_object_hash ($tmp), $dependencies_hashes) === false) {
							$result2->append ($tmp);

							$dependencies_hashes[] = spl_object_hash ($tmp);
						}
					}
				}
			}

			//добавляем в основной массив
			foreach ($result2 as $tmp) {
				if (in_array (spl_object_hash ($tmp), $dependencies_hashes) === false) {
					$result->append ($tmp);

					$dependencies_hashes[] = spl_object_hash ($tmp);
				}
			}

			return $result;
		} else {
			//нет зависимостей
			return new \ArrayObject;
		}
	}

	/**
	 * Если ли завсимые объекты
	 *
	 * @param $object
	 * @return boolean
	 */
	public function isDependencies ($object)
	{
		if ($this->dependecies === null) {
			$this->configure ();
		}

		return isset ($this->dependecies[get_class ($object)]) ?
			$this->dependecies[get_class ($object)]->isDependencies ($object) : false;
	}

	/**
	 * Удалить все зависимые объекты
	 *
	 * @return boolean
	 */
	public function removeDependencies ($object)
	{
		if ($this->dependecies === null) {
			$this->configure ();
		}

		if (isset ($this->dependecies[get_class ($object)])) {
			//заивисимости есть
			return $this->dependecies[get_class ($object)]->removeDependencies ($object);
		} else {
			//нет зависимостей
			return true;
		}
	}

}
