<?php
/**
 * Created by PhpStorm.
 * User: Siebe
 * Date: 6/11/2016
 * Time: 15:22
 */

namespace App;


class ISO639 extends \Matriphe\ISO639\ISO639
{
	public function getLanguages () {
		return $this->languages;
	}
}