<?php

namespace DAL;

use \Framework\DAL\Database;
use \Framework\DAL\DALHelper;
use \Model\Category;

class CategoryDAL
{
	private $db;

	public function __construct($db = null)
	{
		if (isset($db))
			$this->db = $db;
		else
			$this->db = new Database();
	}

	public function Add($categories)
	{
		$query = "INSERT INTO Category (Name, Color, IsVisible)
			VALUES (:Name, UNHEX(:Color), :IsVisible);";

		foreach ($categories as $category) 
		{
			$params = [ 
				'Name' => $category->GetName()
				, 'Color' => str_replace("#", "", $category->GetColor())
				, 'IsVisible' => $category->GetIsVisible() ? 1 : 0
			];

			$this->db->Execute($query, $params);
		}
	}

	public function Update($categories)
	{
		$query = "UPDATE Category
			SET Name = :Name
			, Color = UNHEX(:Color)
			, IsVisible = :IsVisible
			WHERE Id = :Id;";

		foreach ($categories as $category) 
		{
			$params = [ 
				'Id' => $category->GetId()
				, 'Name' => $category->GetName()
				, 'Color' => str_replace("#", "", $category->GetColor())
				, 'IsVisible' => $category->GetIsVisible() ? 1 : 0
			];

			$this->db->Execute($query, $params);
		}
	}

	public function Load($filter = null)
	{
		$query = "SELECT C.Id, C.Name, HEX(C.Color) AS Color, C.IsVisible
			FROM Category AS C";

		$params = null;

		if (isset($filter))
		{
			$params = array();

			$query .= "WHERE ";
			$firstCond = true;

			if (array_key_exists("ids", $filter))
			{
				$ids = $filter["ids"];
				$query .= DALHelper::SetArrayParams($ids, "C", "Id", $params)
				$firstCond = false;
			}
		}

		$query .= ";";

		$rows = $this->db->Read($query, $params);

		$categories = array();

		foreach ($rows as $row) 
		{
			$category = new Category();
			$category->SetId($row['Id']);
			$category->SetName($row['Name']);
			$category->SetColor("#".$row['Color']);
			$category->SetIsVisible($row['IsVisible']);

			$categories[$category->GetId()] = $category;
		}

		return $categories;
	}
}