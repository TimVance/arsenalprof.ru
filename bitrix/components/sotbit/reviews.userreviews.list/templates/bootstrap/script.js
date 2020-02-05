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
		SiteDir = $("#reviews-user-list").attr("data-site-dir");
		
		if(SiteDir===undefined)
			{
			SiteDir='/';
			}
		
		TEMPLATE = $(".sotbit_reviews_user_reviews .pagination").attr("data-template");
		FilterRating=$("#current-option-select-rating").attr("data-value");
		FilterImages=$("#filter-images").attr("data-value");
		FilterSortBy=$("#current-option-select-sort").attr("data-sort-by");
		FilterSortOrder=$("#current-option-select-sort").attr("data-sort-order");
		BX.showWait();
		$.ajax({
			type: 'POST',
			url: SiteDir+'bitrix/components/sotbit/reviews.userreviews.list/ajax/reload_reviews.php',
			data: {data:data,TEMPLATE:TEMPLATE,Url:Url,FilterPage:FilterPage,FilterRating:FilterRating,FilterImages:FilterImages,FilterSortBy:FilterSortBy,FilterSortOrder:FilterSortOrder},
			success: function(data){
				$('#reviews-user-list').wrap('<div></div>').parent().html(data);
				$('#reviews-user-list').unwrap();
				BX.closeWait();
			},
			error:  function (jqXHR, exception) {
			}
		});
	}