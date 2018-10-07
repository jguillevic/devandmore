<?php

namespace DAL;

use \Framework\DAL\Database;
use \Model\Category;

class CategoryDAL
{
	private $db;

	public function __construct()
	{
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
		$query = "SELECT Id, Name, HEX(Color) AS Color, IsVisible
			FROM Category ";

		$params = null;

		if (isset($filter))
		{
			$params = array();

			$query .= "WHERE ";

			if (array_key_exists("ids", $filter))
			{
				$ids = $filter["ids"];

				$query .= "Id IN (";

				for ($i=0; $i < count($ids); $i++) 
				{ 
					if ($i > 0)
					{
						$query .= ", ";
					}
					
					$query .= ":Id".$i;
				
					$params["Id".$i] = $ids[$i];
				}

				$query .= ")";
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