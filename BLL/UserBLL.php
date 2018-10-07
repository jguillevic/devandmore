<?php

namespace BLL;

use \DAL\UserDAL;
use \Framework\BLL\CheckHelper;
use \Framework\BLL\BusinessViolation;

class UserBLL
{
	const USERNAME_MAX_LENGTH = 35;
	const PASSWORD_MAX_LENGTH = 100;
	const USER_PWD_NOT_FOUND = "L'identifiant et/ou le mot de passe sont incorrectes.";

	private $userDAL;

	public function __construct()
	{
		$this->userDAL = new UserDAL();
	}

	public function Exists($user)
	{	
		$violations = self::CheckFields($user);
		
		if (count($violations) === 0)
		{
			$exists = $this->userDAL->Exists($user);
	
			if ($exists)
			{
				return true;
			}
			else
			{
				$violation = BusinessViolation::CreateBusinessError(self::USER_PWD_NOT_FOUND);
				array_push($violations, $violation);
	
				return $violations;
			}
		}

		return $violations;
	}

	private static function CheckFields($user)
	{
		$violations = array();

		self::CheckUsername($user, $violations);
		self::CheckPassword($user, $violations);

		return $violations;
	}

	private static function CheckUsername($user, &$violations)
	{
		$result = CheckHelper::CheckStringLength(
			$user->GetUsername()
			, CheckHelper::DEFAULT_MIN_LENGTH
			, sprintf(CheckHelper::MIN_LENGTH_MESSAGE, "Identifiant", CheckHelper::DEFAULT_MIN_LENGTH)
			, self::USERNAME_MAX_LENGTH
			, sprintf(CheckHelper::MAX_LENGTH_MESSAGE, "Identifiant", self::USERNAME_MAX_LENGTH)
		);

		if ($result !== true)
		{
			$violation = BusinessViolation::CreateBusinessError($result);
			array_push($violations, $violation);
		}
	}

	private static function CheckPassword($user, &$violations)
	{
		$result = CheckHelper::CheckStringLength(
			$user->GetPassword()
			, CheckHelper::DEFAULT_MIN_LENGTH
			, sprintf(CheckHelper::MIN_LENGTH_MESSAGE, "Mot de passe", CheckHelper::DEFAULT_MIN_LENGTH)
			, self::PASSWORD_MAX_LENGTH
			, sprintf(CheckHelper::MAX_LENGTH_MESSAGE, "Mot de passe", self::PASSWORD_MAX_LENGTH)
		);

		if ($result !== true)
		{
			$violation = BusinessViolation::CreateBusinessError($result);
			array_push($violations, $violation);
		}
	}
}