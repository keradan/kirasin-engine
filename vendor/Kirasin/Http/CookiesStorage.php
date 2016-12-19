<?php

namespace Kirasin\Http;

/**
 * @author Даниил Керасиди
 * Класс предоставляющий удобный интерфейс для управления куками
 */
class CookiesStorage
{
	/**
	 * Метод осуществляющий удаление елемента из $_COOKIE
	 * @param string $name - ключ елемента
	 * @return bool true|false - результат выполнения метода
	 */
	public function remove(string $name)
	{
		if(!$name) return false;
		if (!isset($_COOKIE[$name])) return false;
		setcookie ($name, '', 1);
		return true;
	}

	/**
	 * Проверка на наличие елемента в $_COOKIE
	 * @param string $name - ключ елемента
	 * @return bool true|false - результат выполнения метода
	 */
	public function has(string $name)
	{
		if(!$name) return false;
		return (isset($_COOKIE[$name]) && !empty($_COOKIE[$name]))? true : false;
	}

	/**
	 * Берем елемент из $_COOKIE
	 * @param string $name - ключ елемента
	 * @return false|mixed - Если елемент есть тогда возвращает его, иначе false
	 */
	public function get(string $name)
	{
		if(!$name) return false;
		if (!isset($_COOKIE[$name])  || empty($_COOKIE[$name])) return false;
		return $_COOKIE[$name];
	}

	/**
	 * Устанавливаем новый елемент в $_COOKIE
	 * @param string $name - ключ елемента
	 * @param mixed $name - значение елемента
	 * @return bool true|false - результат метода
	 */
	public function set(string $name, $value)
	{
		if(!$name || !$value) return false;
		setcookie($name, $value);
		return true;
	}

}