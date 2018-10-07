<?php

namespace Model;

class Category
{
	private $id;
	private $name;
	private $color;
	private $isVisible;

	public function GetId()
	{
		return $this->id;
	}

	public function SetId($value)
	{
		$this->id = $value;

		return $this;
	}

	public function GetName()
	{
		return $this->name;
	}

	public function SetName($value)
	{
		$this->name = $value;

		return $this;
	}

	public function GetColor()
	{
		return $this->color;
	}

	public function SetColor($value)
	{
		$this->color = $value;

		return $this;
	}

	public function GetIsVisible()
	{
		return $this->isVisible;
	}

	public function SetIsVisible($value)
	{
		$this->isVisible = $value;

		return $this;
	}
}