<?php

namespace Controller;

use \Framework\View\View;
use \Framework\Tools\Helper\PathHelper;

class AboutController
{
	public function Display($queryParameters)
	{
		$path = PathHelper::GetPath([ "About", "DisplayAbout" ]);
		$view = new View($path);
		return $view->Render();
	}
}