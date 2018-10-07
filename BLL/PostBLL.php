<?php

namespace BLL;

use \DAL\PostDAL;
use \Framework\BLL\CheckHelper;
use \Framework\BLL\BusinessViolation;

class PostBLL
{
	const TITLE_MAX_LENGTH = 100;
	const SLUG_MAX_LENGTH = 100;
	const IMAGE_PATH_MAX_LENGTH = 255;

	private $postDAL;

	public function __construct()
	{
		$this->postDAL = new PostDAL();
	}

	public function Add($post)
	{
		$violations = self::CheckFields($post);
		
		if (count($violations) === 0)
		{
			$this->postDAL->Add([ $post ]);
			return true;
		}

		return $violations;
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

	public function LoadByIds($ids)
	{
		return $this->postDAL->Load([ "ids" => $ids ]);
	}

	public function LoadBySlugs($slugs)
	{
		return $this->postDAL->Load([ "slugs" => $slugs ]);
	}

	private static function CheckFields($post)
	{
		$violations = array();

		self::CheckTitle($post, $violations);
		self::CheckSlug($post, $violations);

		return $violations;
	}

	private static function CheckTitle($post, &$violations)
	{
		$result = CheckHelper::CheckStringLength(
			$post->GetTitle()
			, CheckHelper::DEFAULT_MIN_LENGTH
			, sprintf(CheckHelper::MIN_LENGTH_MESSAGE, "Titre", CheckHelper::DEFAULT_MIN_LENGTH)
			, self::TITLE_MAX_LENGTH
			, sprintf(CheckHelper::MAX_LENGTH_MESSAGE, "Titre", self::TITLE_MAX_LENGTH)
		);

		if ($result !== true)
		{
			$violation = BusinessViolation::CreateBusinessError($result);
			array_push($violations, $violation);
		}
	}

	private static function CheckSlug($post, &$violations)
	{
		$result = CheckHelper::CheckStringLength(
			$post->GetSlug()
			, CheckHelper::DEFAULT_MIN_LENGTH
			, sprintf(CheckHelper::MIN_LENGTH_MESSAGE, "Slug", CheckHelper::DEFAULT_MIN_LENGTH)
			, self::SLUG_MAX_LENGTH
			, sprintf(CheckHelper::MAX_LENGTH_MESSAGE, "Slug", self::SLUG_MAX_LENGTH)
		);

		if ($result !== true)
		{
			$violation = BusinessViolation::CreateBusinessError($result);
			array_push($violations, $violation);
		}
	}
}