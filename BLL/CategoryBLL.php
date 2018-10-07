<?php

namespace BLL;

use \DAL\CategoryDAL;

class CategoryBLL
{
	private $categoryDAL;

	public function __construct()
	{
		$this->categoryDAL = new CategoryDAL();
	}

	public function Add($category)
	{
		$this->categoryDAL->Add([ $category ]);
	}

	public function Update($category)
	{
		$this->categoryDAL->Update([ $category ]);
	}

	public function Delete($categoryIds)
	{
		$this->categoryDAL->Delete($categoryIds);
	}

	public function LoadAll()
	{
		return $this->categoryDAL->Load();
	}

	public function LoadByIds($ids)
	{
		return $this->categoryDAL->Load([ "ids" => $ids ]);
	}
}