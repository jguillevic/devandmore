<?php

namespace Model;

use \Framework\Tools\Helper\PathHelper;

class Image
{
	private $id;
	private $name;
	private $path;
	private $extension;
	private $isVisible;

	public function __construct()
	{
		$this->SetIsVisible(false);
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

	public function GetName()
	{
		return $this->name;
	}

	public function SetName($value)
	{
		$this->name = $value;

		return $this;
	}

	public function GetPath()
	{
		return $this->path;
	}

	public function SetPath($value)
	{
		$this->path = $value;

		return $this;
	}

	public function GetExtension()
	{
		return $this->extension;
	}

	public function SetExtension($value)
	{
		$this->extension = $value;

		return $this;
	}

	public function GetIsVisible()
	{
		return (bool)$this->isVisible;
	}

	public function SetIsVisible($value)
	{
		$this->isVisible = $value;

		return $this;
	}

	public function GetFullPath()
	{
		return PathHelper::GetPath([ $this->path, $this->name.".".$this->extension ]);
	}

	public function GetRelativePath()
	{
		return str_replace($_SERVER['DOCUMENT_ROOT'], "", $this->GetFullPath());
	}
}