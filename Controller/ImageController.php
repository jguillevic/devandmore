<?php

namespace Controller;

use \Framework\View\View;
use \Tools\Helper\UserHelper;
use \Framework\Tools\Helper\RoutesHelper;
use \Framework\Tools\Helper\PathHelper;
use \BLL\ImageBLL;
use \Model\Image;
use \Config\PathConfig;

class ImageController
{
	public function Manage($queryParameters)
	{
		if (UserHelper::IsLogin())
		{
			$imageBLL = new ImageBLL();
			$images = $imageBLL->LoadAll();

			$path = PathHelper::GetPath([ "Image", "ManageImage" ]);
			$view = new View($path);
			return $view->Render(["images" => $images]);
		}

		RoutesHelper::Redirect("DisplayHome");
	}

	public function Add($queryParameters)
	{
		if (UserHelper::IsLogin())
		{
			$path = PathHelper::GetPath([ "Image", "AddImage" ]);
			$image = new Image();
			$mode = "ADD";
			$source = $queryParameters["source"]->GetValue();

			if ($_SERVER['REQUEST_METHOD'] === 'GET')
			{
				$image->SetIsVisible(true);

				$view = new View($path);
				return $view->Render([ "image" => $image, "mode" => $mode, "source" => $source ]);
			}
			else if ($_SERVER['REQUEST_METHOD'] === 'POST') 
			{
				$image->SetName($queryParameters["image-name"]->GetValue());
				$image->SetExtension($queryParameters["image-extension"]->GetValue());
				$image->SetPath(PathConfig::GetImgDownloadPath());
				if (array_key_exists("image-is-visible", $queryParameters))
				{
					$image->SetIsVisible($queryParameters["image-is-visible"]->GetValue() === "on");
				}

				if ($source === "URL")
				{
					$url = $queryParameters["image-url"]->GetValue();
					file_put_contents($image->GetFullPath(), file_get_contents($url));
				}
				else if ($source === "LOCAL")
				{
					move_uploaded_file($_FILES['image-local-chooser']['tmp_name'], $image->GetFullPath());
				}

				$imageBLL = new ImageBLL();
				$imageBLL->Add($image);

				RoutesHelper::Redirect("ManageImage");
			}
		}

		RoutesHelper::Redirect("DisplayHome");
	}

	public function Update($queryParameters)
	{
		if (UserHelper::IsLogin())
		{
			$path = PathHelper::GetPath([ "Image", "UpdateImage" ]);
			$mode = "UPDATE";
			$imageBLL = new ImageBLL();

			if ($_SERVER['REQUEST_METHOD'] === 'GET')
			{
				$imageId = $queryParameters["id"]->GetValue();	
				$images = $imageBLL->LoadByIds([ $imageId ]);
				$image = array_pop($images);

				$view = new View($path);
				return $view->Render([ "image" => $image, "mode" => $mode ]);
			}
			else if ($_SERVER['REQUEST_METHOD'] === 'POST') 
			{
				$image = new Image();
				$image->SetId($queryParameters["image-id"]->GetValue());
				$image->SetPath($queryParameters["image-path"]->GetValue());
				$image->SetExtension($queryParameters["image-extension"]->GetValue());
				$image->SetName($queryParameters["image-name"]->GetValue());
				if (array_key_exists("image-is-visible", $queryParameters))
				{
					$image->SetIsVisible($queryParameters["image-is-visible"]->GetValue() === "on");
				}

				$imageBLL->Update($image);

				RoutesHelper::Redirect("ManageImage");
			}
		}

		RoutesHelper::Redirect("DisplayHome");
	}
}