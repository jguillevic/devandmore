<?php

namespace BLL;

use \DAL\ImageDAL;

class ImageBLL
{
	private $imageDAL;

	public function __construct()
	{
		$this->imageDAL = new ImageDAL();
	}

	public function Add($image)
	{
		$this->imageDAL->Add([ $image ]);
	}

	public function Update($image)
	{
		$this->imageDAL->Update([ $image ]);
	}

	public function Delete($imageIds)
	{
		$this->imageDAL->Delete($imageIds);
	}

	public function LoadAll()
	{
		return $this->imageDAL->Load();
	}

	public function LoadByIds($ids)
	{
		return $this->imageDAL->Load([ "ids" => $ids ]);
	}
}