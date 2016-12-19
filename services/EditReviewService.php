<?php

namespace Services;

use Kirasin\Kirasin;

/**
 * 
 */
class EditReviewService
{
	private $validator;

	private $review_data;

	private $is_valid;

	public function __construct($form_data)
	{
		$this->validator = Kirasin::$app->validator;
		$this->is_valid = $this->validator->validate($this->rules(), $form_data);
		if ($this->is_valid) $this->review_data = (object)$form_data;
	}

	private function rules()
	{
		return [
			'author' => ['required', 'string', 'min' => 3, 'max' => 50],
			'email' => ['required', 'email', 'min' => 3, 'max' => 50],
			'text' => ['required', 'string'],
		];
	}

	public function __get($name)
	{
		return $this->review_data->$name ?? null;
	}

	public function isValid()
	{
		return $this->is_valid;
	}

}