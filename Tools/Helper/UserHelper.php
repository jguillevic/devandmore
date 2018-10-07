<?php

namespace Tools\Helper;

class UserHelper
{
	const USER_KEY = "user";

	public static function IsLogin()
	{
		return isset($_SESSION[self::USER_KEY]);
	}

	public static function Logout()
	{
		if (self::IsLogin())
		{
			unset($_SESSION[self::USER_KEY]);

			return true;
		}
		
		return false;
	}

	public static function Login($user)
	{
		$_SESSION[self::USER_KEY] = $user;
	}
}