<?php

namespace DAL;

use \Framework\DAL\Database;
use \Framework\DAL\DALHelper;
use \Model\Image;

class ImageDAL
{
	private $db;

	public function __construct($db = null)
	{
		if (isset($db))
			$this->db = $db;
		else
			$this->db = new Database();
	}

	public function Add($images)
	{
		$query = "INSERT INTO Image (Name, Path, Extension, IsVisible)
			VALUES (:Name, :Path, :Extension, :IsVisible);";

		foreach ($images as $image) 
		{
			$params = [ 
				'Name' => $image->GetName()
				, 'Path' => $image->GetPath()
				, 'Extension' => $image->GetExtension()
				, 'IsVisible' => $image->GetIsVisible() ? 1 : 0
			];

			$this->db->Execute($query, $params);
		}
	}

	public function Update($images)
	{
		$query = "UPDATE Image 
			SET Name = :Name
			, Path = :Path
			, Extension = :Extension
			, IsVisible = :IsVisible
			WHERE Id = :Id;";

		foreach ($images as $image) 
		{
			$params = [ 
				'Id' => $image->GetId()
				, 'Name' => $image->GetName()
				, 'Path' => $image->GetPath()
				, 'Extension' => $image->GetExtension()
				, 'IsVisible' => $image->GetIsVisible() ? 1 : 0
			];

			$this->db->Execute($query, $params);
		}
	}


	public function Delete($imageIds)
	{
		$query = "DELETE FROM Image
			WHERE Id IN (";

		$params = array();

		for ($i=0; $i < count($imageIds); $i++) 
		{ 
			if ($i > 0)
			{
				$query .= ", ";
			}
			
			$query .= ":Id".$i;

			$params["Id".$i] = $imageIds[$i];
		}

		$query .= ");";
		
		$this->db->Execute($query, $params);
	}

	public function Load($filter = null)
	{
		$query = "SELECT I.Id, I.Name, I.Path, I.Extension, I.IsVisible
				FROM Image AS I ";

		$params = null;

		if (isset($filter))
		{
			$params = array();

			$query .= "WHERE ";
			$firstCond = true;

			if (array_key_exists("ids", $filter))
			{
				$ids = $filter["ids"];
				$query .= DALHelper::SetArrayParams($ids, "I", "Id", $params);
				$firstCond = false;
			}

			if (array_key_exists("isVisible", $filter))
			{
				if ($firstCond)
					$query .= " AND ";

				$isVisible = $filter["isVisible"];
				$query .= DALHelper::SetArrayParams($isVisible, "I", "IsVisible", $params);
				$firstCond = false;
			}
		}

		$query .= ";";

		$rows = $this->db->Read($query, $params);

		$images = array();

		foreach ($rows as $row) 
		{
			$image = new Image();
			$image->SetId($row['Id']);
			$image->SetName($row['Name']);
			$image->SetPath($row['Path']);
			$image->SetExtension($row['Extension']);
			$image->SetIsVisible($row['IsVisible']);

			$images[$image->GetId()] = $image;
		}

		return $images;
	}
}