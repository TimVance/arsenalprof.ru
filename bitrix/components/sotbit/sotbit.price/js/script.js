function ajaxCheckRefresh(button)
{
	var parent = BX.findParent(button.target, {className : 'sizes-block-cnt'});
	var products = BX.findChildren(document, {className : 'sizes-block-cnt'}, true);
	var idClick = parent.getAttribute('data-id');
	var action = '', postData = 'ajaxSotbitPriceProducts=true';

	if(BX.hasClass(button.target, 'sizes-block-cnt-plus'))
		action = 'plus';
	else if(BX.hasClass(button.target, 'sizes-block-cnt-minus'))
		action = 'minus';


	for(var i = 0; i < products.length; i++)
	{
		var price = parseFloat(products[i].getAttribute('data-price'));
		var id = parseInt(products[i].getAttribute('data-id'));
		var child = BX.findChild(products[i], {className : 'sizes-block-cnt-value'}, true);
		var count = parseInt(child.innerHTML);

		if(count > 0)
			postData += "&SotbitPriceProducts["+id+"][PRICE]="+price+"&SotbitPriceProducts["+id+"][COUNT]="+count;
	}

	BX.ajax({
		url: document.location.href,
		data: postData,
		method: 'POST',
		dataType: 'json',
		timeout: 30,
		async: true,
		processData: true,
		cache: false,
		onsuccess: function(data){
			if(data.refresh != 'undefined' && data.refresh == true)
			{
				//recalcBasketAjax();
				BX.clearCache();

				var alertBlock = document.getElementById('alertBlock');
				BX.scrollToNode(BX.findChild(document, alertBlock, true));

				//BX.reload(document.location.href, false);
			}
		},
		onfailure: function(){}
	});
}

function resetBlock(block)
{
	var postData = "SotbitMessage[ajax]=1&SotbitMessage[reset]=1&SotbitMessage[sessid]="+BX.bitrix_sessid();

	var xhr = new XMLHttpRequest();
	xhr.open('POST', document.location.href, true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(postData);

	block.removeAttribute('style');
	BX.show(BX.findChild(document, {className : 'alertShortContent'}, true));
	BX.hide(BX.findChild(document, {className : 'message'}, true));
}

function showAlert()
{
	var alertBlock = document.getElementById('alertBlock');
	var messageBlock = BX.findChild(alertBlock, {className : 'message'}, true);

	if(BX.isNodeHidden(alertBlock))
		BX.show(alertBlock);

	if(messageBlock.style.display == 'none')
	{
		var right = alertBlock.style.right;

		var easing = new BX.easing({
			duration : 700,
			start : { right : right},
			finish : { right: 90},
			step : function(state){
				alertBlock.style.right = state.right + "px";
			},
			complete : function() {}
		});
		easing.animate();
	}
}

function checkMessageStatus()
{
	var postData = "SotbitMessage[ajax]=1&SotbitMessage[check]= 1&SotbitMessage[sessid]="+BX.bitrix_sessid();
	var xhr = new XMLHttpRequest();

	xhr.open('POST', document.location.href, true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

	xhr.onreadystatechange = function() {
		if(xhr.readyState == 4 && xhr.status == 200) {
			if (xhr.status != 200)
			{
				// console.log(xhr);
			}
			else
			{
				if(xhr.responseText)
				{
					var data = JSON.parse(xhr.responseText);
					// console.log(data);
					if(typeof data != undefined && data.error != true && data.SotbitMessage.show == true)
					{
						showAlert();
					}
					else
					{
						var alertBlock = document.getElementById('alertBlock');
						BX.hide(alertBlock);
					}
					if(typeof data.SotbitMessage.shortMessage != 'undefined')
					{
						var alertBlock = document.getElementById('alertBlock');
						var messageShortTextBlock = BX.findChild(alertBlock, {className : 'shortMessage'}, true);
						messageShortTextBlock.innerHTML = data.SotbitMessage.shortMessage;
					}
					if(typeof data.SotbitMessage.message != 'undefined')
					{
						var alertBlock = document.getElementById('alertBlock');
						var messageTextBlock = BX.findChild(alertBlock, {className : 'messageText'}, true);
						messageTextBlock.innerHTML = data.SotbitMessage.message;
					}
				}
			}
		}
	}
	xhr.send(postData);
	//  BX.ajax({
	//  	url: document.location.href,
	//      data: postData,
	//      method: 'POST',
	//      dataType: 'json',
	//      timeout: 30,
	//      async: true,
	//      onsuccess: function(data){
	// 		  if(data.error != true && data.SotbitMessage.show == true)
	// 		  {
	// 		  showAlert();
	// 	  }
	// 		  else
	// 		  {
	// 			  var alertBlock = document.getElementById('alertBlock');
	// 			  BX.hide(alertBlock);
	// 	  }
	//    	  if(typeof data.SotbitMessage.shortMessage != 'undefined')
	// 	  {
	//    		  var alertBlock = document.getElementById('alertBlock');
	// 			  var messageShortTextBlock = BX.findChild(alertBlock, {className : 'shortMessage'}, true);
	// 			  messageShortTextBlock.innerHTML = data.SotbitMessage.shortMessage;
	// 	  }
	// 		  if(typeof data.SotbitMessage.message != 'undefined')
	// 		  {
	// 			  var alertBlock = document.getElementById('alertBlock');
	// 			  var messageTextBlock = BX.findChild(alertBlock, {className : 'messageText'}, true);
	// 			  messageTextBlock.innerHTML = data.SotbitMessage.message;
	// 		  }
	//      },
	//      onfailure: function(){}
	// });
}

BX.ready(function(){

	var block = BX.create('div',
		{
			id: 'qwe',
			html: '<div class="alertShortContent"><div class="notice"></div><div class="alertBeforeContent"></div>'+
			'<p class="shortMessage">'+
			""+
			'</p>'+
			'<div class="alertAfterContent"></div></div><div class="message" style="display: none;"><a href="#" class="close"></a><div class="messageText"></div></div>'
		});
	block.id = 'alertBlock';
	BX.append(block, document.body);

	BX.bindDelegate(
		document,
		'click',
		{
			className : "sizes-block-cnt-plus"
		},
		function(button)
		{
			ajaxCheckRefresh(button);
		}
	);

	BX.bindDelegate(
		document,
		'click',
		{
			className : "sizes-block-cnt-minus"
		},
		function(button)
		{
			ajaxCheckRefresh(button);
		}
	);

	BX.bindDelegate(
		document.getElementById('alertBlock'),
		'click',
		{
			className: 'close'
		},
		function()
		{
			var alertBlock = document.getElementById('alertBlock');
			var messageBlock = BX.findChild(alertBlock, {className : 'message'}, true);

			if(!BX.isNodeHidden(messageBlock))
				$(alertBlock).fadeOut(400, function(){
					resetBlock(alertBlock);
				});
		}
	);

	var alertBlock = document.getElementById('alertBlock');

	BX.bindDelegate(
		document.getElementById('alertBlock'),
		'click',
		{
			className: 'close'
		},
		function()
		{
			var alertBlock = document.getElementById('alertBlock');
			var messageBlock = BX.findChild(alertBlock, {className : 'message'}, true);

			if(!BX.isNodeHidden(messageBlock))
				$(alertBlock).fadeOut(400, function(){
					resetBlock(alertBlock);
				});
		}
	);

	BX.bindDelegate(
		alertBlock,
		'click',
		alertBlock,
		function()
		{
			var alertBlock = document.getElementById('alertBlock');
			var messageBlock = BX.findChild(alertBlock, {className : 'message'}, true);

			if(BX.isNodeHidden(messageBlock))
			{
				//calculate parameters for diferent devices
				BX.hide(BX.findChild(alertBlock, {className : 'alertShortContent'}, true));
				messageBlock.style.overflow = 'hidden';
				$(messageBlock).fadeIn(200);

				var widthStart = alertBlock.offsetWidth;
				var heightStart = alertBlock.offsetHeight;

				var widthBlock = messageBlock.offsetWidth;
				var heightBlock = messageBlock.offsetHeight;

				var w_width = window.innerWidth;
				var w_height = window.innerHeight;

				var widthFinish = 0;
				var heightFinish = 0;

				if(w_width < widthBlock)
					widthFinish = w_width;
				else
					widthFinish = widthBlock;

				var right = 40;
				if(alertBlock.style.right > 0)
					right = alertBlock.style.right;

				if((right+widthFinish) > w_width)
					right = (w_width - widthFinish) / 2;

				var top = 15;

				if(w_height < (heightBlock + top))
					top = (w_height - heightBlock) / 2;

				if(top < 0) top = 0;
				if(right < 0) right = 0;

				alertBlock.style.bottom = 0;

				var easing = new BX.easing({
					duration : 300,
					start : { height : heightStart, bottom: 0, right : right, width: widthStart},
					finish : { height : heightBlock, bottom: top, right: right, width: widthBlock },
					step : function(state){
						alertBlock.style.height = state.height + "px";
						alertBlock.style.width = state.width + "px";
						alertBlock.style.bottom = state.bottom + "px";
						alertBlock.style.right = state.right + "px";
					},
					complete : function() {}
				});
				easing.animate();
			}
		}
	);

	//var intervalTime = setInterval(checkMessageStatus,5000);
});