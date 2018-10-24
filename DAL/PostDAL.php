<?php

namespace DAL;

use \Framework\DAL\Database;
use \Framework\DAL\DALHelper;
use \Model\Post;
use \Model\Category;
use \Model\Image;

class PostDAL
{
	private $db;

	public function __construct($db = null)
	{
		if (isset($db))
			$this->db = $db;
		else
			$this->db = new Database();
	}

	public function Add($posts)
	{
		$query = "INSERT INTO Post (Title, Slug, Description, Content, CreationDate, LastUpdateDate, IsPublished, CategoryId, ImageId)
			VALUES (:Title, :Slug, :Description, :Content, :CreationDate, :LastUpdateDate, :IsPublished, :CategoryId, :ImageId);";

		foreach ($posts as $post) 
		{
			$params = [ 
				'Title' => $post->GetTitle()
				, 'Slug' => $post->GetSlug()
				, 'Description' => $post->GetDescription()
				, 'Content' => $post->GetContent()
				, 'CreationDate' => $post->GetCreationDate()
				, 'LastUpdateDate' => $post->GetLastUpdateDate()
				, 'IsPublished' => $post->GetIsPublished() ? 1 : 0
				, 'CategoryId' => $post->GetCategory()->GetId()
				, 'ImageId' => $post->GetImage()->GetId()
			];

			$this->db->Execute($query, $params);
		}
	}

	public function Update($posts)
	{
		$query = "UPDATE Post 
			SET Title = :Title
			, Slug = :Slug
			, Description = :Description
			, Content = :Content
			, CreationDate = :CreationDate
			, LastUpdateDate = :LastUpdateDate
			, IsPublished = :IsPublished
			, CategoryId = :CategoryId
			, ImageId = :ImageId
			WHERE Id = :Id;";

		foreach ($posts as $post) 
		{
			$params = [ 
				'Id' => $post->GetId()
				, 'Title' => $post->GetTitle()
				, 'Slug' => $post->GetSlug()
				, 'Description' => $post->GetDescription()
				, 'Content' => $post->GetContent()
				, 'CreationDate' => $post->GetCreationDate()
				, 'LastUpdateDate' => $post->GetLastUpdateDate()
				, 'IsPublished' => $post->GetIsPublished() ? 1 : 0
				, 'CategoryId' => $post->GetCategory()->GetId()
				, 'ImageId' => $post->GetImage()->GetId()
			];

			$this->db->Execute($query, $params);
		}
	}

	public function Delete($postIds)
	{
		$query = "DELETE FROM Post
			WHERE Id IN (";

		$params = array();

		for ($i=0; $i < count($postIds); $i++) 
		{ 
			if ($i > 0)
			{
				$query = $query.", ";
			}
			
			$query = $query.":Id".$i;

			$params["Id".$i] = $postIds[$i];
		}

		$query .= ");";
		
		$this->db->Execute($query, $params);
	}

	public function Load($filter = null)
	{
		$query = self::BuildLoadQuery();

		$rows = $this->db->Read($query, $params);

		$posts = array();

		foreach ($rows as $row) 
		{
			$post = self::ReadPost($row);

			$category = new Category();
			$category->SetId($row['C_Id']);
			$category->SetName($row['C_Name']);
			$category->SetColor($row['C_Color']);
			$category->SetIsVisible($row['C_IsVisible']);
			$post->SetCategory($category);

			$image = new Image();
			$image->SetId($row["I_Id"]);
			$image->SetName($row["I_Name"]);
			$image->SetPath($row["I_Path"]);
			$image->SetExtension($row["I_Extension"]);
			$image->SetIsVisible($row["I_IsVisible"]);
			$post->SetImage($image);

			$posts[$post->GetId()] = $post;
		}

		return $posts;
	}

	/**
	 * Build query for load
	 * @param array $filter
	 * @return string query
	 */
	private static function BuildLoadQuery($filter)
	{
		$query = "SELECT P.Id AS P_Id, P.Title AS P_Title, P.Slug AS P_Slug, P.Description AS P_Description, P.Content AS P_Content, P.CreationDate AS P_CreationDate, P.LastUpdateDate AS P_LastUpdateDate, P.IsPublished AS P_IsPublished, C.Id AS C_Id, C.Name AS C_Name, C.Color AS C_Color, C.IsVisible AS C_IsVisible, I.Id AS I_Id, I.Name AS I_Name, I.Path AS I_Path, I.Extension AS I_Extension, I.IsVisible AS I_IsVisible
			FROM Post AS P
			INNER JOIN Category AS C ON P.CategoryId = C.Id
			INNER JOIN Image AS I ON P.ImageId = I.Id ";
		
		$params = null;

		if (isset($filter))
		{
			$params = array();

			$query .= "WHERE ";
			$firstCond = true;

			if (array_key_exists("ids", $filter))
			{
				$ids = $filter["ids"];
				$query .= DALHelper::SetArrayParams($ids, "P", "Id", $params)
				$firstCond = false;
			}

			if (array_key_exists("slugs", $filter))
			{
				if ($firstCond)
					$query .= " AND ";

				$slugs = $filter["slugs"];
				$query .= DALHelper::SetArrayParams($slugs, "P", "Slug", $params)
				$firstCond = false;
			}
		}

		$query .= ";";

		return $query;
	}

	/**
	 * Read a post from row data
	 * @param array $row data from the row
	 * @return Post post with read data
	 */
	private static function ReadPost($row)
	{
		$post = new Post();

		$post->SetId($row['P_Id']);
		$post->SetTitle($row['P_Title']);
		$post->SetSlug($row['P_Slug']);
		$post->SetDescription($row['P_Description']);
		$post->SetContent($row['P_Content']);
		$post->SetCreationDate($row['P_CreationDate']);
		$post->SetLastUpdateDate($row['P_LastUpdateDate']);
		$post->SetIsPublished($row['P_IsPublished']);

		return $post;
	}
}