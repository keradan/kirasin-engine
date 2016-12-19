<?php

namespace Kirasin\Http;

use Kirasin\Kirasin;

/**
 * @author Даниил Керасиди
 * Класс, реализующий удобный интерфейс для системных сообщений
 * Имеет в себе расширяющие методы событий (Event) и валидации (Validate),
 * которые упрощают регистрацию и вызов сообщений специальных сообщений
 */
class FlashMasseges
{
	/**
	 * При создании проверяет сессию на наличие в ней елемента "flash", и если нет его тогда создает его и помещает в него пустой массив
	 */
	public function __construct() {
		if (!isset($_SESSION['flash'])) $_SESSION['flash'] = [];
	}

	/**
	 * При завершении скрипта если "flash" пустой - удаляет его
	 */
	public function __destruct() {
		if (isset($_SESSION['flash']) && empty($_SESSION['flash'])) unset($_SESSION['flash']);
	}

	/**
	 * Проверяет "flash" на наличие в нем елемента
	 * @param string $name - имя елемента
	 * @return bool true|false - Результат выполнения метода
	 */
	public function has(string $name)
	{
		if(!$name) return false;
		return (isset($_SESSION['flash'][$name]) && !empty($_SESSION['flash'][$name]))? true : false;
	}

	/**
	 * Извлекает с последующим удалением елмент из "flash"
	 * @param string $name - имя елемента
	 * @return string $message|false - елемент или false, если он небыл найден
	 */
	public function get(string $name)
	{
		if(!$name) return false;
		if (!isset($_SESSION['flash'][$name])  || empty($_SESSION['flash'][$name])) return false;
		$message = $_SESSION['flash'][$name];
		unset($_SESSION['flash'][$name]);
		return $message;
	}

	/**
	 * Добавляет елемент в "flash"
	 * @param string $name - Имя елемента
	 * @param mixed $value - Значение елемента
	 * @return bool true|false - Результат выполнения методв
	 */
	public function add(string $name, $value)
	{
		if(!$name || !$value) return false;
		$_SESSION['flash'][$name] = $value;
		return true;
	}

	/**
	 * Проверяет на наличее во "flash" елемента по имени 'event'
	 * @return bool true|false
	 */
	public function hasEvent()
	{
		return $this->has('event');
	}

	/**
	 * Добавляет во "flash" елемента по имени 'event'
	 * @param bool $success - успешность события
	 * @param string $headline - Заголовок сообщения
	 * @param string $message - Текст сообщения
	 */
	public function addEvent(bool $success, string $headline, string $message)
	{
		return $this->add('event', (object)[
			'success' => $success,
			'head' => $headline,
			'message' => $message,
		]);
	}

	/**
	 * Извлекает из "flash" елемент по имени 'event'
	 * @return Возвращает выполнение метода get()
	 */
	public function getEvent()
	{
		return $this->get('event');
	}

	/**
	 * Устанавливает во "flash" ошибки валидации
	 * @param array $errors - массив содержащий ошибки, которые были инициированы валидатором во время проверки данных из формы
	 * @return Возвращает выполнение метода add()
	 */
	public function setValidateErrors($errors)
	{
		return $this->add('validate_errors', (object)$errors);
	}

	/**
	 * Извлекает из "flash" ошибки валидации
	 * @return Возвращает выполнение метода get()
	 */
	public function getValidateErrors()
	{
		return $this->get('validate_errors');
	}

}