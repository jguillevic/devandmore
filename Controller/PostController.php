<?php

namespace Controller;

use \Model\Post;
use \Model\Category;
use \Model\Image;
use \BLL\PostBLL;
use \BLL\CategoryBLL;
use \BLL\ImageBLL;
use \Framework\View\View;
use \Tools\Helper\UserHelper;
use \Framework\Tools\Helper\RoutesHelper;
use \Framework\Tools\Helper\PathHelper;
use \Framework\Tools\Helper\SlugHelper;
use \Config\PathConfig;

class PostController
{
	public function Manage($queryParameters)
	{
		if (UserHelper::IsLogin())
		{
			$postBLL = new PostBLL();
			$posts = $postBLL->LoadAll();
		
			$path = PathHelper::GetPath([ "Post", "ManagePost" ]);
			$view = new View($path);
			return $view->Render(["posts" => $posts]);
		}

		RoutesHelper::Redirect("DisplayHome");
	}

	public function Display($queryParameters)
	{
		$slug = $queryParameters["slug"]->GetValue();

		$postBLL = new PostBLL();
		$posts = $postBLL->LoadBySlugs([ $slug ]);
		$post = array_pop($posts);
		
		$path = PathHelper::GetPath([ "Post", "DisplayPost" ]);
		$view = new View($path);
		return $view->Render(["post" => $post]);
	}

	public function Add($queryParameters)
	{
		if (UserHelper::IsLogin())
		{
			$path = PathHelper::GetPath([ "Post", "AddPost" ]);

			$post = new Post();

			$categoryBLL = new CategoryBLL();
			$categories = $categoryBLL->LoadAll();

			$imageBLL = new ImageBLL();
			$images = $imageBLL->LoadAll();

			if ($_SERVER['REQUEST_METHOD'] === 'GET')
			{
				$view = new View($path);
				return $view->Render([ "post" => $post, "categories" => $categories, "images" => $images ]);
			}
			else if ($_SERVER['REQUEST_METHOD'] === 'POST') 
			{
				self::MapPost($post, $queryParameters, $categories, $images);
				$dateNow = date('Y-m-d H:i:s');
				$post->SetCreationDate($dateNow);
				$post->SetLastUpdateDate($dateNow);

				$postBLL = new PostBLL();
				$result = $postBLL->Add($post);

				RoutesHelper::Redirect("ManagePost");
			}
		}

		RoutesHelper::Redirect("DisplayHome");
	}

	public function Update($queryParameters)
	{
		if (UserHelper::IsLogin())
		{
			$path = PathHelper::GetPath([ "Post", "UpdatePost" ]);
			$postBLL = new PostBLL();

			$categoryBLL = new CategoryBLL();
			$categories = $categoryBLL->LoadAll();

			$imageBLL = new ImageBLL();
			$images = $imageBLL->LoadAll();

			if ($_SERVER['REQUEST_METHOD'] === 'GET')
			{
				$postId = $queryParameters["id"]->GetValue();	
				$posts = $postBLL->LoadByIds([ $postId ]);
				$post = array_pop($posts);

				$view = new View($path);
				return $view->Render([ "post" => $post, "categories" => $categories, "images" => $images ]);
			}
			else if ($_SERVER['REQUEST_METHOD'] === 'POST') 
			{
				$post = new Post();
				
				self::MapPost($post, $queryParameters, $categories, $images);
				$dateNow = date('Y-m-d H:i:s');
				$post->SetLastUpdateDate($dateNow);

				$postBLL->Update($post);

				RoutesHelper::Redirect("ManagePost");
			}
		}

		RoutesHelper::Redirect("DisplayHome");
	}

	private static function MapPost($post, $queryParameters, $categories, $images)
	{
		$post->SetId($queryParameters["post-id"]->GetValue());
		$post->SetTitle($queryParameters["post-title"]->GetValue());
		$post->SetSlug(SlugHelper::Slugify($post->GetTitle()));
		$post->SetDescription($queryParameters["post-description"]->GetValue());
		$post->SetContent($queryParameters["post-content"]->GetValue());
		$post->SetCreationDate($queryParameters["post-creation-date"]->GetValue());
		if (array_key_exists("post-is-published", $queryParameters))
		{
			$post->SetIsPublished($queryParameters["post-is-published"]->GetValue() === "on");
		}

		$categoryId = $queryParameters["post-category-id"]->GetValue();
		$post->SetCategory($categories[$categoryId]);

		$imageId = $queryParameters["post-image-id"]->GetValue();
		$post->SetImage($images[$imageId]);
	}

	public function Delete($queryParameters)
	{
		if (UserHelper::IsLogin())
		{
			$id = $queryParameters["id"]->GetValue();

			$postBLL = new PostBLL();
			$postBLL->Delete([ $id ]);
		}

		RoutesHelper::Redirect("DisplayHome");
	}
}