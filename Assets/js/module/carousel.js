$(document).ready(function()
{
	var canChange = true;
	var currentCarouselItemIndex = -1;
	var imgAnimationDelay = 400;

	var carouselItems = $(".carousel__item");
	var carouselItemsTitles = $(".carousel__item__title");
	var carouselItemsDescriptions = $(".carousel__item__description");

	var carouselButtonPrevious = $(".carousel__button--previous");
	var carouselButtonNext = $(".carousel__button--next");

	carouselButtonPrevious.click(movePreviousOnClick);
	carouselButtonNext.click(moveNextOnClick);

	moveNext();

	// Démarrage d'une boucle sur les images.
	var imgLoopInterval = 10000;
	var intervalId = setInterval(moveNext, imgLoopInterval);

	function movePreviousOnClick()
	{
		// Arrêt de la boucle.
		clearInterval(intervalId);

		movePrevious();

		intervalId = setInterval(moveNext, imgLoopInterval);
	}

	function movePrevious()
	{
		if (canChange)
		{
			canChange = false;

			var currentCarouselItemTitle = carouselItemsTitles[currentCarouselItemIndex];
			$(currentCarouselItemTitle).fadeOut(imgAnimationDelay);
	
			var currentCarouselItemDescription = carouselItemsDescriptions[currentCarouselItemIndex];
			$(currentCarouselItemDescription).delay(imgAnimationDelay).fadeOut(imgAnimationDelay);
	
			var currentCarouselItem = carouselItems[currentCarouselItemIndex];
			$(currentCarouselItem).delay(2 * imgAnimationDelay).fadeOut(imgAnimationDelay);
	
			if (currentCarouselItemIndex <= 0)
			{
				currentCarouselItemIndex = $(carouselItems).length - 1;
			}
			else
			{
				currentCarouselItemIndex--;
			}
	
			currentCarouselItem = carouselItems[currentCarouselItemIndex];
			$(currentCarouselItem).delay(3 * imgAnimationDelay).fadeIn(imgAnimationDelay);
	
			currentCarouselItemTitle = carouselItemsTitles[currentCarouselItemIndex];
			$(currentCarouselItemTitle).delay(4 * imgAnimationDelay).fadeIn(imgAnimationDelay);
	
			currentCarouselItemDescription = carouselItemsDescriptions[currentCarouselItemIndex];
			$(currentCarouselItemDescription).delay(5 * imgAnimationDelay).fadeIn(imgAnimationDelay, setCanChangeTrue);
		}
	}

	function moveNextOnClick()
	{
		// Arrêt de la boucle.
		clearInterval(intervalId);

		moveNext();

		intervalId = setInterval(moveNext, imgLoopInterval);
	}

	function moveNext()
	{
		if (canChange)
		{
			canChange = false;

			var currentCarouselItemTitle = carouselItemsTitles[currentCarouselItemIndex];
			$(currentCarouselItemTitle).fadeOut(imgAnimationDelay);
	
			var currentCarouselItemDescription = carouselItemsDescriptions[currentCarouselItemIndex];
			$(currentCarouselItemDescription).delay(imgAnimationDelay).fadeOut(imgAnimationDelay);
	
			var currentCarouselItem = carouselItems[currentCarouselItemIndex];
			$(currentCarouselItem).delay(2 * imgAnimationDelay).fadeOut(imgAnimationDelay);
	
			if (currentCarouselItemIndex >= $(carouselItems).length - 1)
			{
				currentCarouselItemIndex = 0;
			}
			else
			{
				currentCarouselItemIndex++;
			}
	
			currentCarouselItem = carouselItems[currentCarouselItemIndex];
			$(currentCarouselItem).delay(3 * imgAnimationDelay).fadeIn(imgAnimationDelay);
	
			currentCarouselItemTitle = carouselItemsTitles[currentCarouselItemIndex];
			$(currentCarouselItemTitle).delay(4 * imgAnimationDelay).fadeIn(imgAnimationDelay);
	
			currentCarouselItemDescription = carouselItemsDescriptions[currentCarouselItemIndex];
			$(currentCarouselItemDescription).delay(5 * imgAnimationDelay).fadeIn(imgAnimationDelay, setCanChangeTrue);
		}
	}

	function setCanChangeTrue()
	{
		canChange = true;
	}
});