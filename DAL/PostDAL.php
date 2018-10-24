<?php

namespace DAL;

use \Framework\DAL\Database;
use \Framework\DAL\DALHelper;
use \DAL\CategoryDAL;
use \DAL\ImageDAL;
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
			$post = self::ReadPost($row, $categoryId, $imageId);

			$categoryDAL = new CategoryDAL($this->db);
			$categories = $categoryDAL->Load([ "ids" => [ $categoryId ] ]);
			$post->SetCategory($categories[$categoryId]);

			$imageDAL = new ImageDAL($this->db);
			$images = $imageDAL->Load([ "ids" => [ $imageId ] ];) 
			$post->SetImage($images[$imageId]);

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
		$query = "SELECT P.Id AS P_Id
		, P.Title AS P_Title
		, P.Slug AS P_Slug
		, P.Description AS P_Description
		, P.Content AS P_Content
		, P.CreationDate AS P_CreationDate
		, P.LastUpdateDate AS P_LastUpdateDate
		, P.IsPublished AS P_IsPublished
		, P.CategoryId AS P_CategoryId
		, P.ImageId AS P_ImageId
		FROM Post AS P ";
		
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
	private static function ReadPost($row, &$categoryId, &$imageId)
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
		$categoryId = $row["P_CategoryId"];
		$imageId = $row["P_ImageId"];

		return $post;
	}
}