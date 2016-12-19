<?php

namespace Kirasin\DependencyInjection;

/**
 * @author Даниил Керасиди
 * Клас через который осуществляется инъекция зависимостей
 * реализует интерфейс ArrayAccess для доступа к компонентам контейнера как к елементам массива
 */
class Container implements \ArrayAccess
{
	
	/**
	 * @var array $services - Содержит компоненты
	 * Каждый компонент - это колбэк функция, которая создает и возвращает екземпляр объекта какого либо класса.
	 */
	private $services;

	/**
	 * При создании устанавливает переданные компоненты в свойство $services
	 * @param array $services - массив компонентов
	 */
	public function __construct(array $services = array())
	{
		$this->services = $services;
	}

	/**
	 * Добавляет новый компонент в контейнер
	 * @param string $offset - ключ компонента
	 * @param callable $value - коллбек функция создающая и возвращающая екземпляр обьекта компонента
	 */
	public function offsetSet ($offset, $value)
	{
		if (!$this->offsetExists($offset)) $this->services[$offset] = $value;	
	}

	/**
	 * Берет компонент из контейнера
	 * @param string $offset - ключ компонента
	 * @return object - Вызывает по ключу из контейнера колбек функцию, и возвращает результат ее выполнения - новый екземпляр обьекта компонента
	 */
	public function offsetGet ($offset)
	{
		return ($this->offsetExists($offset))? $this->services[$offset]($this) : null;
	}
	
	/**
	 * Проверяет на наличие компонента в контейнере
	 * @param string $offset - ключ компонента
	 */
	public function offsetExists ($offset)
	{
		return isset($this->services[$offset]);
	}
	
	/**
	 * Метод должен быть по условиям интерфейса, но в приложении нигде не используется
	 */
	public function offsetUnset ($offset){}

}