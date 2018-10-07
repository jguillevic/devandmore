<?php

namespace DAL;

use \Framework\DAL\Database;
use \Model\Image;

class ImageDAL
{
	private $db;

	public function __construct()
	{
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
		$query = "SELECT Id, Name, Path, Extension, IsVisible
				FROM Image ";

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