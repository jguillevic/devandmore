<?php

namespace Model;

use \Model\Category;
use \Model\Image;

class Post
{
	private $id;
	private $slug;
	private $title;
	private $description;
	private $content;
	private $creationDate;
	private $lastUpdateDate;
	private $isPublished;
	private $category;
	private $image;

	public function __construct()
	{
		$this->SetCategory(new Category());
		$this->SetImage(new Image());
	}

	public function GetId()
	{
		return $this->id;
	}

	public function SetId($value)
	{
		$this->id = $value;

		return $this;
	}

	public function GetSlug()
	{
		return $this->slug;
	}

	public function SetSlug($value)
	{
		$this->slug = $value;

		return $this;
	}

	public function GetTitle()
	{
		return $this->title;
	}

	public function SetTitle($value)
	{
		$this->title = $value;

		return $this;
	}

	public function GetDescription()
	{
		return $this->description;
	}

	public function SetDescription($value)
	{
		$this->description = $value;

		return $this;
	}

	public function GetContent()
	{
		return $this->content;
	}

	public function SetContent($value)
	{
		$this->content = $value;

		return $this;
	}

	public function GetCreationDate()
	{
		return $this->creationDate;
	}

	public function SetCreationDate($value)
	{
		$this->creationDate = $value;

		return $this;
	}

	public function GetLastUpdateDate()
	{
		return $this->lastUpdateDate;
	}

	public function SetLastUpdateDate($value)
	{
		$this->lastUpdateDate = $value;

		return $this;
	}

	public function GetIsPublished()
	{
		return $this->isPublished;
	}

	public function SetIsPublished($value)
	{
		$this->isPublished = $value;

		return $this;
	}

	public function GetCategory()
	{
		return $this->category;
	}

	public function SetCategory($value)
	{
		$this->category = $value;

		return $this;
	}

	public function GetImage()
	{
		return $this->image;
	}

	public function SetImage($value)
	{
		$this->image = $value;

		return $this;
	}
}