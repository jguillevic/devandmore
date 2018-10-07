<?php

namespace DAL;

use \Framework\DAL\Database;
use \Model\User;

class UserDAL
{
	private $db;

	public function __construct()
	{
		$this->db = new Database();
	}

	public function Exists($user)
	{
		$query = "SELECT 1 FROM User WHERE Username=:Username AND Password=:Password;";

		$params = [
			"Username" => $user->GetUsername()
			, "Password" => $user->GetPassword()
		];

		$rows = $this->db->Read($query, $params);

		return count($rows) === 1;
	}
}