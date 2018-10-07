<?php

namespace Model;

class User
{
	private $username;
	private $password;

	public function GetUsername()
	{
		return $this->username;
	}

	public function SetUsername($value)
	{
		$this->username = $value;

		return $this;
	}

	public function GetPassword()
	{
		return $this->password;
	}

	public function SetPassword($value)
	{
		$this->password = $value;

		return $this;
	}
}