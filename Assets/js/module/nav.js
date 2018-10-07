$(document).ready(function()
{
	$(".nav__burger").click(function()
	{
		$(".nav__overlay").toggleClass("nav__overlay--open");
	});

	$(".nav__overlay__close").click(function()
	{
		$(".nav__overlay").toggleClass("nav__overlay--open");
	});
});