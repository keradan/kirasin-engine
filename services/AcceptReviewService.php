<?php

namespace Services;

use Kirasin\Kirasin;

/**
 * 
 */
class AcceptReviewService
{
	
	private $validator;

	public $status;
	private $is_valid;

	public function __construct($form_data)
	{
		$this->validator = Kirasin::$app->validator;
		$this->is_valid = $this->validator->validate($this->rules(), $form_data);
		if ($this->is_valid) $this->status = $form_data['status'];
	}

	private function rules()
	{
		return [
			'status' => ['required', 'int'],
		];
	}

	public function isValid()
	{
		return $this->is_valid;
	}

}