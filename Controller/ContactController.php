<?php

namespace Controller;

use \Framework\View\View;
use \Framework\Tools\Helper\PathHelper;

class ContactController
{
	public function Display()
	{
		$path = PathHelper::GetPath([ "Contact", "DisplayContact" ]);
		$view = new View($path);
		return $view->Render();
	}
}