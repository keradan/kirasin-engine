<?php

namespace Kirasin\Valid;

use Kirasin\Kirasin;

/**
 * @author Даниил Керасиди
 * Класс валидатор. Осуществляет валидацию данных пришедших из формы
 */
class Validator extends BaseValidator
{
	/**
	 * @var array $errors - Массив который содержит все ошибки, вызванные по ходу работы метода validate()
	 */
	private $errors = [];

	/**
	 * Основной метод, который производит валидацию
	 * @param array $rules - массив содержащий правила, которым должны соответствовать данные пришедшие из формы
	 * @param array $data - данные пришедшие из формы
	 * @return bool true|false - успешность валидации
 	 */
	public function validate($rules, $data)
	{
		$validate = function($rules, $data) {
			if (!$data) return $this->addError("Вы отправили пустые данные.", 'common');
			if (!$rules) return $this->addError("Системная ошибка.", 'common');
			foreach (array_keys($data) as $key) {
				if (!array_key_exists($key, $rules)) return $this->addError("Системная ошибка неверные поля.", 'common');
				$results[] = $this->verifyParam($key, $data[$key], $rules[$key]); //return $this->addError("GAVNO", 'common');
			}
			return (in_array(false, $results))? false : true;
		};
		if ($validate($rules, $data)) return true;
		Kirasin::$app->flash->setValidateErrors($this->errors);
		return false;
	}

	/**
	 * Проверка текущего по итерации елемента данных формы
	 * @param string $param_name - имя елемента данных формы
	 * @param mixed $param_value - значение елемента данных формы
	 * @param array $param_rules - список правил елемента данных формы
	 * @return bool true|false - успешность валидации
	 */
	private function verifyParam($param_name, $param_value, $param_rules)
	{
		return (in_array(false, array_map(function ($rule_name, $rule_value) use ($param_name, $param_value) {
			$verify = false;
			if (is_string($rule_name)){
				$method_name = 'baseValidate' . ucfirst($rule_name);
				if (!method_exists($this, $method_name)) die('Системная ошибка - проверь правила валидации');
				$verify = $this->$method_name($param_value, $rule_value);
			} else {
				if (is_callable($rule_value)) {
					$verify = $rule_value($param_value, $this);
				}
				else {
					$method_name = 'baseValidate' . ucfirst($rule_value);
					if (!method_exists($this, $method_name)) die('Системная ошибка - проверь правила валидации');
					$verify = $this->$method_name($param_value);
				}
			}
			if (!$verify) $this->setLastErrorParamName($param_name);
			return $verify;
		}, array_keys($param_rules), array_values($param_rules))))? false : true;
	}

	/**
	 * Добавление ошибки в свойство $this->errors
	 * @param string $message - Текст сообщения
	 * @param string $param_name - имя елемента формы
	 * @return false - Всегда возвращает false
	 */
	public function addError($message, $param_name = null)
	{
		if(!$param_name) $this->errors[] = $message;
		else $this->errors[$param_name] = $message;
		return false;
	}

	/**
	 * Устанавливает имя для последней добавленной ошибки
	 * @param string $param_name - имя елемента формы
	 */
	private function setLastErrorParamName($param_name)
	{
		$error = array_pop($this->errors);
		$this->errors[$param_name] = $error;
	}

}