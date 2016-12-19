<?php

namespace Models;

use Kirasin\Model\ActiveRecord;
use Kirasin\Kirasin;

class Reviews extends ActiveRecord
{
	
	protected function afterFind($review)
	{
		$review->date = date(' d.m.Y в H:i ', $review->date);
		$review->link = Kirasin::$app->urlManager->generateLink('/admin/edit/' . $review->id);
		if ($review->img) $review->img = Kirasin::$app->urlManager->generateImgSrc('reviews/' . $review->img);
		return $review;
	}

}