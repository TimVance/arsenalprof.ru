BX.ready(function() {
    BX.bindDelegate(
        document.body, 'click', {className: 'dlay-cookienotice-button' },
        function(e){
            if(!e)
                e = window.event;

            BX.setCookie('cookienotice', 'Y', {expires: 1209600});
            BX("dlay-cookienotice-modal").style.display = "none";
            return BX.PreventDefault(e);
        }
    );
    BX.addClass("dlay-cookienotice-modal", "dlay-cookienotice-load");
});