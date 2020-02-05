$(document).ready(function(){
	$(document).on('click','.sotbit_reviews_user_reviews .pagination button:not(.current)',
		function(){
			MaxPage=parseInt($(".sotbit_reviews_user_reviews .pagination .last").attr("data-number"));
			CurrentPage=parseInt($(this).attr("data-number"));
			ReloadReviews(CurrentPage);
	});
});
	function ReloadReviews(FilterPage)
	{
		var data=$('#ReviewsParams').html(); //Params of component
		Url = $(".sotbit_reviews_user_reviews .pagination").data("url");
		SiteDir = $(".sotbit_reviews_user_reviews .pagination").attr("data-site-dir");
		TEMPLATE = $(".sotbit_reviews_user_reviews .pagination").attr("data-template");
		BX.showWait();
		$.ajax({
			type: 'POST',
			url: SiteDir+'bitrix/components/sotbit/reviews.user.reviews/ajax/reload_reviews.php',
			data: {data:data,TEMPLATE:TEMPLATE,Url:Url,FilterPage:FilterPage},
			success: function(data){
				$('.sotbit_reviews_user_reviews').wrap('<div></div>').parent().html(data);
				BX.closeWait();
			},
			error:  function (jqXHR, exception) {
			}
		});
	}