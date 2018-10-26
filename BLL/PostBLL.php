<?php

namespace BLL;

use \DAL\PostDAL;
use \Framework\BLL\CheckHelper;
use \Framework\BLL\BusinessViolation;

class PostBLL
{
	private $postDAL;

	public function __construct()
	{
		$this->postDAL = new PostDAL();
	}

	public function Add($post)
	{
		$this->postDAL->Add([ $post ]);
	}

	public function Update($post)
	{
		$this->postDAL->Update([ $post ]);
	}

	public function Delete($postIds)
	{
		$this->postDAL->Delete($postIds);
	}

	public function LoadAll()
	{
		return $this->postDAL->Load();
	}

	public function LoadByIsPublishedByCreationDateDesc()
	{
		return $this->postDAL->Load([ "isPublished" => true ], [ "creationDate" => "DESC" ]);
	}

	public function LoadByIds($ids)
	{
		return $this->postDAL->Load([ "ids" => $ids ]);
	}

	public function LoadBySlugs($slugs)
	{
		return $this->postDAL->Load([ "slugs" => $slugs ]);
	}
}