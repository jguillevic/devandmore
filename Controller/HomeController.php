<?php

namespace Controller;

use \Model\Post;
use \BLL\PostBLL;
use \Framework\View\View;
use \Framework\Tools\Helper\PathHelper;

class HomeController
{
	public function Display($queryParameters)
	{
		$postBLL = new PostBLL();
		$posts = $postBLL->LoadAllByCreationDateDesc();
		
		$path = PathHelper::GetPath([ "Home", "DisplayHome" ]);
		$view = new View($path);
		return $view->Render([ "posts" => $posts ]);
	}
}