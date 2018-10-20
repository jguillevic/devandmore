<?php

namespace Controller;

use \Framework\View\View;
use \Tools\Helper\UserHelper;
use \Framework\Tools\Helper\RoutesHelper;
use \Framework\Tools\Helper\PathHelper;
use \BLL\CategoryBLL;
use \Model\Category;

class CategoryController
{
	public function Manage($queryParameters)
	{
		if (UserHelper::IsLogin())
		{
			$categoryBLL = new CategoryBLL();
			$categories = $categoryBLL->LoadAll();

			$path = PathHelper::GetPath([ "Category", "ManageCategory" ]);
			$view = new View($path);
			return $view->Render(["categories" => $categories]);
		}

		RoutesHelper::Redirect("DisplayHome");
	}

	public function Add($queryParameters)
	{
		if (UserHelper::IsLogin())
		{
			$path = PathHelper::GetPath([ "Category", "AddCategory" ]);
			$category = new Category();

			if ($_SERVER['REQUEST_METHOD'] === 'GET')
			{
				$category->SetIsVisible(true);

				$view = new View($path);
				return $view->Render([ "category" => $category ]);
			}
			else if ($_SERVER['REQUEST_METHOD'] === 'POST') 
			{
				$category->SetName($queryParameters["category-name"]->GetValue());
				$category->SetColor($queryParameters["category-color"]->GetValue());
				if (array_key_exists("category-is-visible", $queryParameters))
				{
					$category->SetIsVisible($queryParameters["category-is-visible"]->GetValue() === "on");
				}

				$categoryBLL = new CategoryBLL();
				$categoryBLL->Add($category);

				RoutesHelper::Redirect("ManageCategory");
			}
		}

		RoutesHelper::Redirect("DisplayHome");
	}	

	public function Update($queryParameters)
	{
		if (UserHelper::IsLogin())
		{
			$path = PathHelper::GetPath([ "Category", "UpdateCategory" ]);
			$categoryBLL = new CategoryBLL();

			if ($_SERVER['REQUEST_METHOD'] === 'GET')
			{
				$categoryId = $queryParameters["id"]->GetValue();	
				$categories = $categoryBLL->LoadByIds([ $categoryId ]);
				$category = array_pop($categories);

				$view = new View($path);
				return $view->Render([ "category" => $category ]);
			}
			else if ($_SERVER['REQUEST_METHOD'] === 'POST') 
			{
				$category = new Category();
				$category->SetId($queryParameters["category-id"]->GetValue());
				$category->SetName($queryParameters["category-name"]->GetValue());
				$category->SetColor($queryParameters["category-color"]->GetValue());
				if (array_key_exists("category-is-visible", $queryParameters))
				{
					$category->SetIsVisible($queryParameters["category-is-visible"]->GetValue() === "on");
				}

				$categoryBLL->Update($category);

				RoutesHelper::Redirect("ManageCategory");
			}
		}

		RoutesHelper::Redirect("DisplayHome");
	}
}