<?php

namespace Kirasin\Model;

use Kirasin\Kirasin; 

/**
 * @author Даниил Керасиди
 * Класс который реализует паттерн ActiveRecord
 * от него наследуются все кастомные модели приложения
 * этот класс напрямую взаимодействует с классом для работы с БД
 */
abstract class ActiveRecord
{
	/**
	 * @var object $db - содержит екземпляр обьекта класса по работе с БД
	 */
	private $db;

	/**
	 * @var array $data - содержит данные извлеченные из БД, или созданные в приложении на основании данных пришедших от пользователя для загрузки в БД
	 */
	protected $data = [];

	/**
	 * @var array $taken_fields - список полей которые были извлечены из БД
	 */
	private $taken_fields = [];

	/**
	 * @var array $changed_fields - список полей которые были изменены в процессе работы скрипта
	 */
	private $changed_fields = [];

	/**
	 * @var string $tbl_name - название текущей таблицы БД
	 */
	private $tbl_name;

	/**
	 * @var array $where - массив содержащий данные в формате <имя поля> : <значение поля> для дальнейшего использования в sql запросе
	 */
	private $where = [];

	/**
	 * @var array $order_by - массив содержащий данные в формате <имя поля> : <способ сортировки> для дальнейшего использования в sql запросе
	 */
	private $order_by = [];

	/**
	 * @var mixed $limit - содержит значение limit для дальнейшего использования в sql запросе
	 */
	private $limit = null;

	/**
	 * @var mixed $offset - содержит значение offset для дальнейшего использования в sql запросе
	 */
	private $offset = null;

	/**
	 * Конструктор закрыт
	 */
	private function __construct(){}

	/**
	 * Создание объекта происходит через статический метод.
	 * Он и устанавливает все начальные свойства
	 * @return object static - возвращает объект класса (дочернего по отношению к этому) который был вызван. 
	 */
	private static function setInstance()
	{
		$instance = new static();
		$instance->db = Kirasin::$app->db;
		$class_path = explode('\\', get_called_class());
		$instance->tbl_name = strtolower(array_pop($class_path));
		return $instance;
	}

	/**
	 * Создает совершенно чистый екземпляр, и устанавливает в свойство data пустой елемент
	 * @return object static - возвращает объект класса (дочернего по отношению к этому) который был вызван. 
	 */
	public static function new()
	{
		$instance = static::setInstance();
		$instance->data[0] = (object)[
			'id' => null,
		];
		return $instance;
	}

	/**
	 * Создает совершенно чистый екземпляр для дальнейшего поиска в БД и загрузки данных
	 * @return object static - возвращает объект класса (дочернего по отношению к этому) который был вызван. 
	 */
	public static function find()
	{
		return static::setInstance();
	}

	/**
	 * Задает в свойство $order_by значение
	 * @param string $field_name - поле по которому будет производится сортировка
	 * @param string $sort_desc - способ сортировки (ASC | DESC)
	 * @return object $this - возвращает себя (объект класса, дочернего по отношению к этому, который был вызван). 
	 */
	public function order_by(string $field_name, bool $sort_desc = false)
	{
		if (preg_match("/[a-z0-9_]/", $field_name)) $this->order_by[$field_name] = ($sort_desc)? ' DESC ' : ' ASC ';
		return $this;
	}

	/**
	 * Задает в свойство $offset значение
	 * @param string $offset - значение смещения строки результата
	 * @return object $this - возвращает себя (объект класса, дочернего по отношению к этому, который был вызван). 
	 */
	public function offset(int $offset)
	{
		$this->offset = $offset;
		return $this;
	}

	/**
	 * Задает в свойство $limit значение
	 * @param string $limit - значение ограничения количества строк результата
	 * @return object $this - возвращает себя (объект класса, дочернего по отношению к этому, который был вызван). 
	 */
	public function limit(int $limit)
	{
		$this->limit = $limit;
		return $this;
	}

	/**
	 * Задает в свойство $where значение
	 * @param string $field_name - поле которое проверяется на соответствие значению
	 * @param string $field_value - требуемое значение поля
	 * @return object $this - возвращает себя (объект класса, дочернего по отношению к этому, который был вызван). 
	 */
	public function where(string $field_name, string $field_value)
	{
		if (preg_match("/[a-z0-9_]/", $field_name)) $this->where[$field_name] = $field_value;
		return $this;
	}

	/**
	 * Возвращает одну строку из таблицы БД
	 * @param int $id - необязательный параметр 'id' по которому в случае его наличия будет производится поиск
	 * @return object $this|null - если поиск в БД успешен вернет себя (объект текущего класса), иначе null
	 */
	public function one(int $id = null)
	{
		if ($id) $this->where('id', $id);
		$result = $this->db->select($this->tbl_name, $this->where, $this->order_by, $this->limit, $this->offset);
		if(!$result) return null;
		$this->taken_fields = array_keys((array)$result[0]);
		$this->data = array_map([$this, 'afterFind'], $result);
		return $this;
	}

	/**
	 * Возвращает все строки из таблицы БД
	 * @param int $id - необязательный параметр 'id' по которому в случае его наличия будет производится поиск
	 * @return array $this->data|null - если поиск в БД успешен вернет массив data содержащий результат запроса, иначе null
	 */
	public function all()
	{
		$result = $this->db->select($this->tbl_name, $this->where, $this->order_by, $this->limit, $this->offset);
		if(!$result) return null;
		$this->taken_fields = array_keys((array)$result[0]);
		$this->data = array_map([$this, 'afterFind'], $result);
		return $this->data;
	}

	/**
	 * Сохраняет заданные на данный момент данные массива data в БД
	 * @return bool true|false - успешность выполнения метода
	 */
	public function save()
	{
		$this->beforeSave($this);
		$instance_data = (array)$this->data[0];
		if ($instance_data['id']) {
			$save_keys = array_intersect($this->changed_fields, $this->taken_fields);
			foreach ($instance_data as $field_name => $field_value) {
				if (!in_array($field_name, $save_keys)) unset($instance_data[$field_name]);
			}
			if (!$instance_data) die('Fields empty');
			return $this->db->update($this->tbl_name, $this->data[0]->id, $instance_data);
		} else return $this->db->insert($this->tbl_name, $instance_data);
	}

	/**
	 * Если идет запрос на свойство которого не существует - мы вызываем такое свойство из массива data
	 * Используется для доступа к заданным елементам или полученным из БД
	 * @param string $name - имя свойства
	 * @return возвращаем елемент из data если такой там есть, иначе false
	 */
	public function __get($name)
	{
		if (count($this->data) != 1) return false;
		return $this->data[0]->$name;
	}

	/**
	 * При попытке задать значение несуществующему елементу - мы задаем значение елементу из массива data
	 * @param string $name - имя свойства
	 * @param string $name - значение свойства
	 * @return При неудаче вернем false
	 */
	public function __set($name, $value)
	{
		if (count($this->data) != 1) return false;
		$this->data[0]->$name = $value;
		$this->changed_fields[] = $name;
	}

	/**
	 * Этот метод вызывается перед тем как будет произведено сохранение данных в БД
	 * Служит для того, чтобы можна было произвести какие-то действия с данными перед попыткой сохранения
	 * @param object $fields - екземпляр самого себя (объекта текущего класса)
	 * @return object $fields - екземпляр самого себя (объекта текущего класса)
	 */
	protected function beforeSave($fields)
	{
		return $fields;
	}

	/**
	 * Этот метод вызывается сразу после выборки из БД
	 * Служит для того, чтобы можна было произвести какие-то действия с данными перед тем, как к ним будет предоставлен общий доступ
	 * @param текущий по итерации елемент из массива data
	 * @return текущий по итерации елемент из массива data
	 */
	protected function afterFind($row)
	{
		return $row;
	}
}