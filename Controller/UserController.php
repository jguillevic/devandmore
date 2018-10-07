<?php

namespace Controller;

use \Framework\View\View;
use \Tools\Helper\UserHelper;
use \Model\User;
use \BLL\UserBLL;
use \Framework\Tools\Helper\RoutesHelper;
use \Framework\Tools\Helper\PathHelper;

class UserController
{
	public function Login($queryParameters)
	{
		if (!UserHelper::IsLogin())
		{
			$path = PathHelper::GetPath([ "User", "LoginUser" ]);

			if ($_SERVER['REQUEST_METHOD'] === 'GET')
			{
				$view = new View($path);
				return $view->Render();
			}
			else if ($_SERVER['REQUEST_METHOD'] === 'POST') 
			{
				$user = new User();

				if (array_key_exists("username", $queryParameters))
				{
					$user->SetUsername($queryParameters["username"]->GetValue());
				}
				if (array_key_exists("password", $queryParameters))
				{
					$user->SetPassword($queryParameters["password"]->GetValue());
				}

				$userBLL = new UserBLL();
				$result = $userBLL->Exists($user);

				if ($result === true)
				{
					UserHelper::Login($user);
				}
				else
				{
					$view = new View($path);
					return $view->Render([ "user" => $user, "errors" => $result ]);
				}
			}
		}

		RoutesHelper::Redirect("DisplayHome");
	}

	public function Logout($queryParameters)
	{
		UserHelper::Logout();

		RoutesHelper::Redirect("DisplayHome");
	}
}