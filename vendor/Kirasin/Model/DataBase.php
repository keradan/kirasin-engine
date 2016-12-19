<?php

namespace Kirasin\Model;

use Kirasin\Kirasin;

/**
 * @author Даниил Керасиди
 * Клас по работе с базой данных
 * Содержит методы для выполнения sql запросов
 */
class DataBase
{
	/**
	 * @var pdo $connection - екземпляр PDO
	 */	
	private $connection;

	/**
	 * @var string $db_pref - префикс таблиц БД
	 */
	private $db_pref;

	/**
	 * При создании создает новый объект PDO и помещает его в свойство $connection
	 */
	public function __construct()
	{
		extract(Kirasin::$app->config['db']);
		$this->db_pref = $db_pref;
		try{
			$this->connection = new \PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
		} catch (PDOException $e) {
			die("<pre>" . $e->getMessage());
		}
	}

	/**
	 * Метод выполняющий SQL запрос SELECT
	 * @param string $tbl_name - имя таблицы к которой будет отправлен запрос
	 * @param array $where - условие выборки. Массив формата <имя поля> : <значение поля>
	 * @param array $order_by - сортироовка. Массив формата <имя поля> : <способ сортировки>
	 * @param int $limit - ограничение количества строк выборки
	 * @param int $offset - смещение строк в выборке
	 * @return Возвращает результат выборки при успешном запросе, иначе false
	 */
	public function select(string $tbl_name, array $where, array $order_by, int $limit = null,  int $offset = null)
	{
		$from_str = " FROM " . $this->db_pref . $tbl_name;
		$fields_str = " * ";
		$where_str = $this->setWhereStr($where);
		$limit_str = $this->setLimitStr($limit, $offset);
		$order_by_str = $this->setOrderByStr($order_by);

		$where_values = $this->setWhereValues($where);

		$query = "SELECT" . $fields_str . $from_str . $where_str . $order_by_str . $limit_str;

		$statement = $this->connection->prepare($query);
		if(!$statement->execute($where_values)) return false;

		$rows = [];
		while ($row = $statement->fetch(\PDO::FETCH_OBJ)) {
			$rows[] = $row;
		}
		return ($rows)? $rows : false;
	}

	/**
	 * Метод выполняющий SQL запрос INSERT
	 * @param string $tbl_name - имя таблицы к которой будет отправлен запрос
	 * @param array $params - поля для вставки. Массив формата <имя поля> : <значение поля>
	 * @return Возвращает результат sql запроса, иначе false
	 */
	public function insert(string $tbl_name, array $params)
	{
		$into = " INTO " . $this->db_pref . $tbl_name;
		
		unset($params['id']);
		foreach ($params as $field_name => $field_value) if (!$field_value) unset($params[$field_name]);
		$keys = implode(', ', array_map(function ($key) { return "`$key`"; }, array_keys($params)));
		$placeholders = implode(', ', array_fill(0, count($params), '?'));
		$values = array_values($params);

		$query = "INSERT $into (`id`, $keys) VALUES (NULL, $placeholders)";
		$statement = $this->connection->prepare($query);
		return $statement->execute($values);
	}

	/**
	 * Метод выполняющий SQL запрос UPDATE
	 * @param string $tbl_name - имя таблицы к которой будет отправлен запрос
	 * @param int $id - значение поля id в строке, которую нужно изменить.
	 * @param array $params - поля для изменения. Массив формата <имя поля> : <значение поля>
	 * @return Возвращает результат sql запроса, иначе false
	 */
	public function update(string $tbl_name, int $id, array $params)
	{
		$table = $this->db_pref . $tbl_name;

		unset($params['id']);
		$set_values = implode(', ', array_map(function ($field_name) { return "`$field_name` = ?"; }, array_keys($params)));
		$values = array_values($params);
		$values[] = $id;

		$query = "UPDATE $table SET $set_values WHERE `id` = ? ";
		$statement = $this->connection->prepare($query);
		return $statement->execute($values);
	}

	/**
	 * Вспомогательный метод, преобразующий исходный массив сортировки в строку пригодную для sql запроса
	 * @param array $order_by - Массив формата <имя поля> : <способ сортировки>
	 * @return string|null - Возвращает часть будущего sql запроса либо null
	 */
	private function setOrderByStr($order_by)
	{
		if (!$order_by) return null;
		$order_by = implode(', ', (array_map(function ($field_name, $sort) {
			return "`$field_name` $sort";
		}, array_keys($order_by), array_values($order_by))));
		return " ORDER BY $order_by ";
	}

	/**
	 * Вспомогательный метод, преобразующий числовые параметры лимита и смещения в строку пригодную для sql запроса
	 * @param int $limit - ограничение количества строк выборки
	 * @param int $offset - смещение строк в выборке
	 * @return string|null - Возвращает часть будущего sql запроса либо null
	 */
	private function setLimitStr($limit, $offset)
	{
		if (!$limit) return null;
		if ($offset) $limit = "$offset, $limit";
		return " LIMIT $limit ";
	}

	/**
	 * Вспомогательный метод, преобразующий исходный массив условий в строку пригодную для sql запроса
	 * @param array $where - Массив формата <имя поля> : <значение поля>
	 * @return string|null - Возвращает часть будущего sql запроса либо null
	 */
	private function setWhereStr($where)
	{
		if (empty($where)) return null;
		return ' WHERE ' . implode(' AND ', array_map(function ($field_name) {
			return $field_name . ' = ? ';
		}, array_keys($where)));
	}

	/**
	 * Вспомогательный метод, преобразующий исходный массив условий в набор значений для замены плейсхолдеров в sql запросе
	 * @param array $where - Массив формата <имя поля> : <значение поля>
	 * @return array - Возвращает массив содержащий значения для вставки в sql запрос 
	 */
	private function setWhereValues($where)
	{
		if (empty($where)) return [];
		return array_values($where);
	}

}