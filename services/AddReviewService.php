<?php

namespace Services;

use Kirasin\Kirasin;

/**
 * 
 */
class AddReviewService
{
	private $validator;

	private $review_data;

	private $is_valid;

	public function __construct($form_data)
	{
		$this->validator = Kirasin::$app->validator;
		$this->is_valid = $this->validator->validate($this->rules(), $form_data);
		if ($this->is_valid) {
			if (isset($form_data['img'])) {
				$upload_from = $form_data['img']['tmp_name'];
				$extension = explode('/', $form_data['img']['type'])[1];
				$new_name = $this->getImgName($form_data['author']) . '.' . $extension;
				$upload_to = Kirasin::$app->request->public_dir . Kirasin::$app->config['img_path'] . 'reviews' . '/' . $new_name;
				$form_data['img_data'] = (object)['upload_from' => $upload_from, 'upload_to' => $upload_to, 'new_name' => $new_name, 'extension' => $extension];
				$form_data['img'] = $new_name;
			} else $form_data['img'] = null;
			$this->review_data = (object)$form_data;
		}
	}

	private function rules()
	{
		return [
			'author' => ['required', 'string', 'min' => 3, 'max' => 50],
			'email' => ['required', 'email', 'min' => 3, 'max' => 50],
			'text' => ['required', 'string'],
			'img' => [$this->validator->custom($this, 'validateImg')],
		];
	}

	public function validateImg($img, $validator)
	{
		if (!$img) return true;
		if (!is_array($img)) return $validator->addError("Ошибка в формате данных");
		if ($img['error'] != 0) return $validator->addError("Ошибка при загрузке файла на сервер");
		if (!$img['tmp_name']) return $validator->addError("Файл не был загружен на сервер");
		if (!$img['type']) return $validator->addError("Ошибка в формате данных");
		if (!in_array($img['type'], Kirasin::$app->config['img_allowed_types'])) return $validator->addError("Запрещенный тип файла");
		return true;
	}	

	public function __get($name)
	{
		return $this->review_data->$name ?? null;
	}

	public function isValid()
	{
		return $this->is_valid;
	}

	private function getImgName($str)
	{
		return time() . '_' . trim(preg_replace('/__+/', '_', preg_replace('/[^a-z0-9_]+/u', '_', strtolower($this->translit($str)))), '_');
	}

	private function translit($str)
	{
    	return str_replace(['є', 'ї', 'і', 'Є', 'Ї', 'І', 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я'], ['e', 'i', 'i', 'E', 'I', 'I', 'A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya'], $str);
	}

	public function loadImg()
	{
		if(!move_uploaded_file($this->img_data->upload_from, $this->img_data->upload_to)) return false;
		$config = Kirasin::$app->config;
		$this->imgResize($this->img_data->upload_to, $this->img_data->extension, $config['img_max_width'], $config['img_max_height']);
		return true;
	}

	private function imgResize($img_name, $extension, $max_width, $max_height)
	{
		list($width, $height) = getimagesize($img_name);
		
		if($width <= $max_width && $height <= $max_height) return true;
		$new_width = ((($max_width*100)/$width)/100) * $width;
		$new_height = ((($max_width*100)/$width)/100) * $height;
		
		if($new_height >= $max_height){
			$new_width = ((($max_height*100)/$new_height)/100) * $new_width;
			$new_height = ((($max_height*100)/$new_height)/100) * $new_height;
		}

		$thumb = imagecreatetruecolor($new_width, $new_height);
		$source = $this->imageCreateFrom($img_name, $extension);
		imagecopyresized($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		$this->imageWrite($thumb, $img_name, $extension);
		return true;
	}

	private function imageWrite($image, $to, $extension)
	{
		switch ($extension) {
	    case 'jpeg':
	        return imagejpeg($image, $to, 100);
	        break;
	    case 'jpg':
	        return imagejpeg($image, $to, 100);
	        break;
	    case 'png':
	        return imagepng($image, $to, 100);
	        break;
	    case 'gif':
	        return imagegif($image, $to, 100);
	        break;
		}
	}

	private function imageCreateFrom($img_name, $extension)
	{
		switch ($extension) {
	    case 'jpeg':
	        return imagecreatefromjpeg($img_name);
	        break;
	    case 'jpg':
	        return imagecreatefromjpeg($img_name);
	        break;
	    case 'png':
	        return imagecreatefrompng($img_name);
	        break;
	    case 'gif':
	        return imagecreatefromgif($img_name);
	        break;
		}
	}

}