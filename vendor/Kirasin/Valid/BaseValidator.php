<?php

namespace Kirasin\Valid;

use Kirasin\Kirasin;

/**
 * @author Даниил Керасиди
 * Класс который содержит набор стандартных методов валидации
 */
class BaseValidator
{
	/**
	 * Вспомогательный метод который необходим для вызова кастомной валидации из класса сервиса
	 * По сути он вызывает метод из передаваемого аргументом объекта, и возвращает этот метод в качестве колбек функции.
	 * Таким образом он скрывает часть логики и упрощая доступ к кастомной валидации в описании правил валидации
	 * @param object $obj - объект, метод которого будет вызываться как колбек
	 * @param string $name - имя самого метода
	 * @return callable - будет вызыватся из класса сервиса для кастомной валидации
	 */
	public function custom($obj, string $name)
	{
		return array($obj, $name);
	}

	/**
	 * Стандартный метод валидации
	 * @param $param_value - валидируемое значение переданное формой
	 * @return bool true|false Если валидация провалена - вызываем метод addError, который всегда возвращает false
	 */
	protected function baseValidateRequired($param_value)
	{
		return (empty(trim($param_value)))? $this->addError("Это поле обязательно!") : true;
	}

	/**
	 * Стандартный метод валидации
	 * @param $param_value - валидируемое значение переданное формой
	 * @return bool true|false Если валидация провалена - вызываем метод addError, который всегда возвращает false
	 */
	protected function baseValidateInt($param_value)
	{
		return (!is_int((int)$param_value))? $this->addError("Это поле должно целым числом!") : true;
	}

	/**
	 * Стандартный метод валидации
	 * @param $param_value - валидируемое значение переданное формой
	 * @return bool true|false Если валидация провалена - вызываем метод addError, который всегда возвращает false
	 */
	protected function baseValidateString($param_value)
	{
		return (!is_string($param_value))? $this->addError("Это поле должно быть текстовой строкой!") : true;
	}

	/**
	 * Стандартный метод валидации
	 * @param $param_value - валидируемое значение переданное формой
	 * @return bool true|false Если валидация провалена - вызываем метод addError, который всегда возвращает false
	 */
	protected function basevalidateLogin($param_value)
	{
		return (!$this->baseValidateString($param_value))? $this->addError("Поле \"логин\" может содержать только буквы, цыфры и пробел.") : true; 
	}

	/**
	 * Стандартный метод валидации
	 * @param $param_value - валидируемое значение переданное формой
	 * @return bool true|false Если валидация провалена - вызываем метод addError, который всегда возвращает false
	 */
	protected function basevalidatePassword($param_value)
	{
		return (!$this->baseValidateString($param_value))? $this->addError("Поле \"логин\" может содержать только буквы, цыфры дефис и нижнее подчеркивание.") : true; 
	}

	/**
	 * Стандартный метод валидации
	 * @param $param_value - валидируемое значение переданное формой
	 * @return bool true|false Если валидация провалена - вызываем метод addError, который всегда возвращает false
	 */	
	protected function baseValidateEmail($param_value)
	{
		return (!is_string($param_value) || !filter_var($param_value, FILTER_VALIDATE_EMAIL) )? $this->addError("Не корректный email адресс!") : true;
	}

	/**
	 * Стандартный метод валидации
	 * @param $param_value - валидируемое значение переданное формой
	 * @param $rule_value - значение указанное в описании правила
	 * @return bool true|false Если валидация провалена - вызываем метод addError, который всегда возвращает false
	 */
	protected function baseValidateMin($param_value, $rule_value)
	{
		return (mb_strlen($param_value) < $rule_value)? $this->addError("Минимальный размер этого поля - $rule_value символов!") : true;
	}

	/**
	 * Стандартный метод валидации
	 * @param $param_value - валидируемое значение переданное формой
	 * @param $rule_value - значение указанное в описании правила
	 * @return bool true|false Если валидация провалена - вызываем метод addError, который всегда возвращает false
	 */
	protected function baseValidateMax($param_value, $rule_value)
	{
		return (mb_strlen($param_value) > $rule_value)? $this->addError("Максимальный размер этого поля - $rule_value символов!") : true;
	}

}