<?php

namespace Kirasin\Http;

/**
 * @author Даниил Керасиди
 * Класс предоставляющий удобный интерфейс для управления сессией
 */
class SessionStorage
{
	/**
	 * Метод осуществляющий удаление елемента из $_SESSION
	 * @param string $name - ключ елемента
	 * @return bool true|false - результат выполнения метода
	 */
	public function remove(string $name)
	{
		if(!$name) return false;
		if (!isset($_SESSION[$name])) return false;
		unset($_SESSION[$name]);
		return true;
	}

	/**
	 * Проверка на наличие елемента в $_SESSION
	 * @param string $name - ключ елемента
	 * @return bool true|false - результат выполнения метода
	 */
	public function has(string $name)
	{
		if(!$name) return false;
		return (isset($_SESSION[$name]) && !empty($_SESSION[$name]))? true : false;
	}

	/**
	 * Берем елемент из $_SESSION
	 * @param string $name - ключ елемента
	 * @return false|mixed - Если елемент есть тогда возвращает его, иначе false
	 */
	public function get(string $name)
	{
		if(!$name) return false;
		if (!isset($_SESSION[$name])  || empty($_SESSION[$name])) return false;
		return $_SESSION[$name];
	}

	/**
	 * Устанавливаем новый елемент в $_SESSION
	 * @param string $name - ключ елемента
	 * @param mixed $name - значение елемента
	 * @return bool true|false - результат метода
	 */
	public function set(string $name, $value)
	{
		if(!$name || !$value) return false;
		$_SESSION[$name] = $value;
		return true;
	}

}