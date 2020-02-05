<? if ($APPLICATION->GetCurPage(false) !== '/'): ?>
        </div>
    </section>
<? endif; ?>


    <section class="mailing">
        <div class="container">

            <div class="mailing__wrap">
                <div class="mailing__item">
                    <h2 class="title"><?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            Array(
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "inc",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => "/include/templates/arsenal/main/subscribe_title.php"
                            )
                        );?></h2>
                    <div class="mailing-subtitle">
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            Array(
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "inc",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => "/include/templates/arsenal/main/subscribe_subtitle.php"
                            )
                        );?>
                    </div>
                </div>
                <div class="mailing__item mailing__item_form">
                    <?$APPLICATION->IncludeComponent(
	"sotbit:sotbit.mailing.email.get", 
	"subscribe", 
	array(
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CATEGORIES_ID" => array(
			0 => "1",
		),
		"CATEGORIES_SHOW" => "N",
		"COLOR_BORDER_PANEL" => "FFFFFF",
		"COLOR_BUTTON" => "6e7278",
		"COLOR_MODAL_BG" => "FFFFFF",
		"COLOR_MODAL_BORDER" => "2B5779",
		"COLOR_PANEL" => "2B5779",
		"COLOR_PANEL_OPEN" => "FFFFFF",
		"DISPLAY_IF_ADMIN" => "Y",
		"DISPLAY_NO_AUTH" => "Y",
		"ELEMENT_CLICK" => ".mailing_click_popup",
		"EMAIL_SEND_END" => "Вы подписались на рассылку, на почту выслан купон",
		"INFO_TEXT" => "Подписка на акции",
		"JQUERY" => "Y",
		"MODAL_BG_PADDING" => "10",
		"MODAL_BG_WIDTH" => "485",
		"MODAL_BORDER_PADDING" => "3",
		"MODAL_TEXT" => "Подпишись! Получи скидку 5% и будь в курсе новых акций!<br />Оставьте свой e-mail.",
		"MODAL_TIME_DAY_NOW" => "5",
		"MODAL_TIME_SECOND_OPEN" => "180",
		"PANEL_TEXT" => "Подпишись! Получи скидку 5% и будь в курсе новых акций!",
		"SUBSCRIBED" => "Вы подписались на рассылку",
		"TYPE" => "FIELD",
		"COMPONENT_TEMPLATE" => "subscribe"
	),
	false
);?>
                    <div class="mailing__text">
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            Array(
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "inc",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => "/include/templates/arsenal/main/text_subscribe.php"
                            )
                        );?>
                    </div>
                </div>
            </div>

        </div>
    </section>


    <footer class="footer">
        <div class="container">

            <div class="footer__wrapper">
                <div class="footer__wrap">
                    <div class="footer__item">
                        <h2>О компании</h2>
                        <div class="footer__menu">
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "footer",
                                Array(
                                    "ALLOW_MULTI_SELECT" => "N",
                                    "CHILD_MENU_TYPE" => "footer_about",
                                    "COMPONENT_TEMPLATE" => "footer",
                                    "DELAY" => "N",
                                    "MAX_LEVEL" => "1",
                                    "MENU_CACHE_GET_VARS" => array(),
                                    "MENU_CACHE_TIME" => "3600",
                                    "MENU_CACHE_TYPE" => "A",
                                    "MENU_CACHE_USE_GROUPS" => "Y",
                                    "ROOT_MENU_TYPE" => "footer_about",
                                    "USE_EXT" => "N"
                                )
                            );?>
                        </div>
                    </div>
                    <div class="footer__item">
                        <h2>Помощь</h2>
                        <div class="footer__menu">
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "footer",
                                Array(
                                    "ALLOW_MULTI_SELECT" => "N",
                                    "CHILD_MENU_TYPE" => "footer_help",
                                    "COMPONENT_TEMPLATE" => "footer",
                                    "DELAY" => "N",
                                    "MAX_LEVEL" => "1",
                                    "MENU_CACHE_GET_VARS" => array(),
                                    "MENU_CACHE_TIME" => "3600",
                                    "MENU_CACHE_TYPE" => "A",
                                    "MENU_CACHE_USE_GROUPS" => "Y",
                                    "ROOT_MENU_TYPE" => "footer_help",
                                    "USE_EXT" => "N"
                                )
                            );?>
                        </div>
                    </div>
                    <div class="footer__item">
                        <h2>Клиентам</h2>
                        <div class="footer__menu">
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "footer",
                                Array(
                                    "ALLOW_MULTI_SELECT" => "N",
                                    "CHILD_MENU_TYPE" => "footer_clients",
                                    "COMPONENT_TEMPLATE" => "footer",
                                    "DELAY" => "N",
                                    "MAX_LEVEL" => "1",
                                    "MENU_CACHE_GET_VARS" => array(),
                                    "MENU_CACHE_TIME" => "3600",
                                    "MENU_CACHE_TYPE" => "A",
                                    "MENU_CACHE_USE_GROUPS" => "Y",
                                    "ROOT_MENU_TYPE" => "footer_clients",
                                    "USE_EXT" => "N"
                                )
                            );?>
                        </div>
                    </div>
                    <div class="footer__item">
                        <h2>Мой кабинет</h2>
                        <div class="footer__menu">
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "footer",
                                Array(
                                    "ALLOW_MULTI_SELECT" => "N",
                                    "CHILD_MENU_TYPE" => "footer_lk",
                                    "COMPONENT_TEMPLATE" => "footer",
                                    "DELAY" => "N",
                                    "MAX_LEVEL" => "1",
                                    "MENU_CACHE_GET_VARS" => array(),
                                    "MENU_CACHE_TIME" => "3600",
                                    "MENU_CACHE_TYPE" => "A",
                                    "MENU_CACHE_USE_GROUPS" => "Y",
                                    "ROOT_MENU_TYPE" => "footer_lk",
                                    "USE_EXT" => "N"
                                )
                            );?>
                        </div>
                    </div>
                </div>
                <div class="footer__contacts">
                    <div class="footer__contacts_wrap">
                        <h2>Контакты</h2>
                        <div class="footer__contacts_mail">
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                Array(
                                    "AREA_FILE_SHOW" => "file",
                                    "AREA_FILE_SUFFIX" => "/include/templates/arsenal/header/email.php",
                                    "EDIT_TEMPLATE" => "",
                                    "PATH" => "/include/templates/arsenal/header/email.php"
                                )
                            );?>
                        </div>
                        <a href="#" class="footer__contacts_phone">8 800 250 57-14</a>
                        <a href="#" class="footer__contacts_phone">+7 391 290 29-22</a>
						<a href="#" class="footer__contacts_phone">+7 965 890 14-42</a>
                        <div class="footer__social">
                            <a href="#">
                                <svg>
                                    <use xlink:href="#vk"/>
                                </svg>
                            </a>
                            <a href="#">
                                <svg>
                                    <use xlink:href="#facebook"/>
                                </svg>
                            </a>
                            <a href="#">
                                <svg>
                                    <use xlink:href="#youtube"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </footer>
    </div>

    <?$APPLICATION->IncludeComponent(
        "bitrix:menu",
        "catalog_mobile",
        Array(
            "ALLOW_MULTI_SELECT" => "N",
            "CHILD_MENU_TYPE" => "left",
            "DELAY" => "N",
            "MAX_LEVEL" => "2",
            "MENU_CACHE_GET_VARS" => array(""),
            "MENU_CACHE_TIME" => "3600",
            "MENU_CACHE_TYPE" => "N",
            "MENU_CACHE_USE_GROUPS" => "Y",
            "ROOT_MENU_TYPE" => "left",
            "USE_EXT" => "Y"
        )
    );?>

    <div class="basket">
        <div class="closed-basket"><i></i></div>
        <div class="basket__wrap">
            <? $APPLICATION->IncludeComponent("dlay:basket.over", ".default", Array(), false); ?>
        </div>
    </div>

</div>
<div class="overlay"></div>
<div class="callback_form_modal">
    <?$APPLICATION->IncludeComponent(
        "bitrix:form",
        "call_back",
        Array(
            "AJAX_MODE" => "Y",
            "AJAX_OPTION_ADDITIONAL" => "",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "CACHE_TIME" => "3600",
            "CACHE_TYPE" => "A",
            "CHAIN_ITEM_LINK" => "",
            "CHAIN_ITEM_TEXT" => "",
            "COMPONENT_TEMPLATE" => "call_back",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            "EDIT_ADDITIONAL" => "N",
            "EDIT_STATUS" => "Y",
            "IGNORE_CUSTOM_TEMPLATE" => "N",
            "NOT_SHOW_FILTER" => array(0=>"",1=>"",),
            "NOT_SHOW_TABLE" => array(0=>"",1=>"",),
            "RESULT_ID" => $_REQUEST[RESULT_ID],
            "SEF_MODE" => "N",
            "SHOW_ADDITIONAL" => "N",
            "SHOW_ANSWER_VALUE" => "N",
            "SHOW_EDIT_PAGE" => "N",
            "SHOW_LIST_PAGE" => "N",
            "SHOW_STATUS" => "Y",
            "SHOW_VIEW_PAGE" => "N",
            "START_PAGE" => "new",
            "SUCCESS_URL" => "",
            "USE_EXTENDED_ERRORS" => "Y",
            "VARIABLE_ALIASES" => array("action"=>"action",),
            "WEB_FORM_ID" => "2"
        )
    );?>
</div>
<div class="change-basket-wrap">
    <?$APPLICATION->IncludeComponent(
        "redsign:vbasket.select",
        "",
        Array(
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO"
        )
    );?>
</div>
    <?echo '
    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="0" height="0" style="position:absolute">

        <symbol id="facebook" viewBox="0 0 96.124 96.123">
            <g>
                <path d="M72.089,0.02L59.624,0C45.62,0,36.57,9.285,36.57,23.656v10.907H24.037c-1.083,0-1.96,0.878-1.96,1.961v15.803   c0,1.083,0.878,1.96,1.96,1.96h12.533v39.876c0,1.083,0.877,1.96,1.96,1.96h16.352c1.083,0,1.96-0.878,1.96-1.96V54.287h14.654   c1.083,0,1.96-0.877,1.96-1.96l0.006-15.803c0-0.52-0.207-1.018-0.574-1.386c-0.367-0.368-0.867-0.575-1.387-0.575H56.842v-9.246   c0-4.444,1.059-6.7,6.848-6.7l8.397-0.003c1.082,0,1.959-0.878,1.959-1.96V1.98C74.046,0.899,73.17,0.022,72.089,0.02z"
                      fill="#FFFFFF"/>
            </g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
        </symbol>

        <symbol id="vk" viewBox="0 0 548.358 548.358">
            <g>
                <path d="M545.451,400.298c-0.664-1.431-1.283-2.618-1.858-3.569c-9.514-17.135-27.695-38.167-54.532-63.102l-0.567-0.571   l-0.284-0.28l-0.287-0.287h-0.288c-12.18-11.611-19.893-19.418-23.123-23.415c-5.91-7.614-7.234-15.321-4.004-23.13   c2.282-5.9,10.854-18.36,25.696-37.397c7.807-10.089,13.99-18.175,18.556-24.267c32.931-43.78,47.208-71.756,42.828-83.939   l-1.701-2.847c-1.143-1.714-4.093-3.282-8.846-4.712c-4.764-1.427-10.853-1.663-18.278-0.712l-82.224,0.568   c-1.332-0.472-3.234-0.428-5.712,0.144c-2.475,0.572-3.713,0.859-3.713,0.859l-1.431,0.715l-1.136,0.859   c-0.952,0.568-1.999,1.567-3.142,2.995c-1.137,1.423-2.088,3.093-2.848,4.996c-8.952,23.031-19.13,44.444-30.553,64.238   c-7.043,11.803-13.511,22.032-19.418,30.693c-5.899,8.658-10.848,15.037-14.842,19.126c-4,4.093-7.61,7.372-10.852,9.849   c-3.237,2.478-5.708,3.525-7.419,3.142c-1.715-0.383-3.33-0.763-4.859-1.143c-2.663-1.714-4.805-4.045-6.42-6.995   c-1.622-2.95-2.714-6.663-3.285-11.136c-0.568-4.476-0.904-8.326-1-11.563c-0.089-3.233-0.048-7.806,0.145-13.706   c0.198-5.903,0.287-9.897,0.287-11.991c0-7.234,0.141-15.085,0.424-23.555c0.288-8.47,0.521-15.181,0.716-20.125   c0.194-4.949,0.284-10.185,0.284-15.705s-0.336-9.849-1-12.991c-0.656-3.138-1.663-6.184-2.99-9.137   c-1.335-2.95-3.289-5.232-5.853-6.852c-2.569-1.618-5.763-2.902-9.564-3.856c-10.089-2.283-22.936-3.518-38.547-3.71   c-35.401-0.38-58.148,1.906-68.236,6.855c-3.997,2.091-7.614,4.948-10.848,8.562c-3.427,4.189-3.905,6.475-1.431,6.851   c11.422,1.711,19.508,5.804,24.267,12.275l1.715,3.429c1.334,2.474,2.666,6.854,3.999,13.134c1.331,6.28,2.19,13.227,2.568,20.837   c0.95,13.897,0.95,25.793,0,35.689c-0.953,9.9-1.853,17.607-2.712,23.127c-0.859,5.52-2.143,9.993-3.855,13.418   c-1.715,3.426-2.856,5.52-3.428,6.28c-0.571,0.76-1.047,1.239-1.425,1.427c-2.474,0.948-5.047,1.431-7.71,1.431   c-2.667,0-5.901-1.334-9.707-4c-3.805-2.666-7.754-6.328-11.847-10.992c-4.093-4.665-8.709-11.184-13.85-19.558   c-5.137-8.374-10.467-18.271-15.987-29.691l-4.567-8.282c-2.855-5.328-6.755-13.086-11.704-23.267   c-4.952-10.185-9.329-20.037-13.134-29.554c-1.521-3.997-3.806-7.04-6.851-9.134l-1.429-0.859c-0.95-0.76-2.475-1.567-4.567-2.427   c-2.095-0.859-4.281-1.475-6.567-1.854l-78.229,0.568c-7.994,0-13.418,1.811-16.274,5.428l-1.143,1.711   C0.288,140.146,0,141.668,0,143.763c0,2.094,0.571,4.664,1.714,7.707c11.42,26.84,23.839,52.725,37.257,77.659   c13.418,24.934,25.078,45.019,34.973,60.237c9.897,15.229,19.985,29.602,30.264,43.112c10.279,13.515,17.083,22.176,20.412,25.981   c3.333,3.812,5.951,6.662,7.854,8.565l7.139,6.851c4.568,4.569,11.276,10.041,20.127,16.416   c8.853,6.379,18.654,12.659,29.408,18.85c10.756,6.181,23.269,11.225,37.546,15.126c14.275,3.905,28.169,5.472,41.684,4.716h32.834   c6.659-0.575,11.704-2.669,15.133-6.283l1.136-1.431c0.764-1.136,1.479-2.901,2.139-5.276c0.668-2.379,1-5,1-7.851   c-0.195-8.183,0.428-15.558,1.852-22.124c1.423-6.564,3.045-11.513,4.859-14.846c1.813-3.33,3.859-6.14,6.136-8.418   c2.282-2.283,3.908-3.666,4.862-4.142c0.948-0.479,1.705-0.804,2.276-0.999c4.568-1.522,9.944-0.048,16.136,4.429   c6.187,4.473,11.99,9.996,17.418,16.56c5.425,6.57,11.943,13.941,19.555,22.124c7.617,8.186,14.277,14.271,19.985,18.274   l5.708,3.426c3.812,2.286,8.761,4.38,14.853,6.283c6.081,1.902,11.409,2.378,15.984,1.427l73.087-1.14   c7.229,0,12.854-1.197,16.844-3.572c3.998-2.379,6.373-5,7.139-7.851c0.764-2.854,0.805-6.092,0.145-9.712   C546.782,404.25,546.115,401.725,545.451,400.298z"
                      fill="#FFFFFF"/>
            </g>
            <g>
                <g></g>
                <g></g>
                <g></g>
                <g></g>
                <g></g>
                <g></g>
                <g></g>
                <g></g>
                <g></g>
                <g></g>
                <g></g>
                <g></g>
                <g></g>
            </g>
        </symbol>

        <symbol id="youtube" viewBox="0 0 310 310">
            <g id="XMLID_822_">
                <path id="XMLID_823_"
                      d="M297.917,64.645c-11.19-13.302-31.85-18.728-71.306-18.728H83.386c-40.359,0-61.369,5.776-72.517,19.938   C0,79.663,0,100.008,0,128.166v53.669c0,54.551,12.896,82.248,83.386,82.248h143.226c34.216,0,53.176-4.788,65.442-16.527   C304.633,235.518,310,215.863,310,181.835v-53.669C310,98.471,309.159,78.006,297.917,64.645z M199.021,162.41l-65.038,33.991   c-1.454,0.76-3.044,1.137-4.632,1.137c-1.798,0-3.592-0.484-5.181-1.446c-2.992-1.813-4.819-5.056-4.819-8.554v-67.764   c0-3.492,1.822-6.732,4.808-8.546c2.987-1.814,6.702-1.938,9.801-0.328l65.038,33.772c3.309,1.718,5.387,5.134,5.392,8.861   C204.394,157.263,202.325,160.684,199.021,162.41z"
                      fill="#FFFFFF"/>
            </g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
        </symbol>

        <symbol id="advantages_1" viewBox="0 0 64 55">
            <metadata><?xpacket begin="﻿" id="W5M0MpCehiHzreSzNTczkc9d"?>
                <x:xmpmeta xmlns:x="adobe:ns:meta/"
                           x:xmptk="Adobe XMP Core 5.6-c138 79.159824, 2016/09/14-01:09:01        ">
                    <rdf:RDF>
                        <rdf:Description rdf:about=""/>
                    </rdf:RDF>
                </x:xmpmeta>
                <?xpacket end="w"?></metadata>
            <image id="Vector_Smart_Object" data-name="Vector Smart Object" width="64" height="55"
                   xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAAA3CAMAAACLkLyVAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAANlBMVEW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucUAAAC8L4p3AAAAEHRSTlMAQO/PgCCfUI+/EN9wrzBgbGwscQAAAAFiS0dEEeK1PboAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfjARUKJhzc6xcsAAACC0lEQVRIx51X2baDIAwsGHaQ/P/X3gAueLU9MfNkLZmGbJN+Pt+hNCIuYD5SAA6AlMDiopwnN6yQIPTfjhbRywgS5s0TVCKCwy5hkdiviFsCKqKEwB1mBnEVEACG22VeoZzpkxHsSZASmMlKRHDGcMrHG/gzhk6UxnQW8JQPWQiCpBkA9f4YEavkBkcVUAji8V41MOqyToGnuUCZcFCCxh06wE+WqKe40XPAB4QfxeEnB+p+3kJVwyaq6hP+GDR1HoT0YfHuXklq+T4udbv1gTU+n4rla4VqzSpditTotzJFRkeO6QY7Zl2+xPZN24waJ0eKOvCq7QYBiJr1vAI5IFWez9LymOUOUJe5xiJ2oHeZ6SwTDF8Cepfls+8HNF9Eeh2V/7rHn8CuR2+a/m8JSu+Y23k2gRmTTk4Ao29uI59NsBXgfj5WGPsYl2Cfmtv5tc/L/IJg2abmyAI1BIbUa4pJoPZzYwuobQeJS6sJJoHFNB5GJe6NHbgE5lCrte8/uaek8AngnH1dwygGCUJn5RHoWbsb11APy83CrHzbbYwPpd+KRXBZPY94DnAI6uXQipeGpO9M9gXcD4J0XVksifUJxDQEIn3dKuCfl+Ym3Lr4penx87Rdb/8gHFzRf9qQLzo/2FdKOU//XGuwRGsBbFuNUQoKvdTcqWns02KC9oWORJXBzgtOsPVq/gfNih+SJcjnzQAAAABJRU5ErkJggg=="/>
        </symbol>

        <symbol id="advantages_2" viewBox="0 0 79 44">
            <metadata><?xpacket begin="﻿" id="W5M0MpCehiHzreSzNTczkc9d"?>
                <x:xmpmeta xmlns:x="adobe:ns:meta/"
                           x:xmptk="Adobe XMP Core 5.6-c138 79.159824, 2016/09/14-01:09:01        ">
                    <rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
                        <rdf:Description rdf:about=""/>
                    </rdf:RDF>
                </x:xmpmeta>
                <?xpacket end="w"?></metadata>
            <image id="Vector_Smart_Object" data-name="Vector Smart Object" width="79" height="44"
                   xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAAE8AAAAsCAMAAAATihVAAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAOVBMVEW2ucX///+2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucUAAADV6UpHAAAAEXRSTlMAAECAv89QIHCv7xCf3zBgj3slNvEAAAABYktHRBJ7vGwAAAAACXBIWXMAAAsSAAALEgHS3X78AAAAB3RJTUUH4wEVCicWJSXPcwAAAoBJREFUSMedltl2hSAMRUWQWZD//9kmTIKicutDV1vDNic5iS7Lx0Uu1/j+ulLKthD4F+6DJ1ZJFdOhXPSfPLNyatkeLpc2v/KKsOGlHZnlXYXF41cwJ5+8oTDGqFwdtw0eyZ688EbCNnZQvsbHeNXmah080Y7av4yFWepXUfohJEv/31OYFQYerPr2vwjrGuJoTnqTx55ohFj402Teu7C+v/zID1Te0UpDnDbZTnogbOgXU+qvrTeG6vibwPs+OiXzWmHPfhFe5ZIdMFKZRk28z5NTMo/r9zFBmCwlO9CwLQ3uO52cUsbH4JM398RzpWRMxgb3NDius1POcZSPKVbLKp/qfaUtp1Oa8RboJyZ6VrWstqU6NxrkUp3SrR9MUcvG1dWyR7XfgAYKwt7iqp+7FGv9pVteaSjjaT3SUFJ0uWTi9EulPezn0bp1mJUyWGO0bBN/0sgPvJQiv8WL46T9xlvWu2+EDQ3tRx5MnxrQdj84YDX/5m1he6WdB2yW8s6D2Xul1QNxp3zzoCPujVYO4E4Z7McbD+J8S3t4/8adMtMPl6pCC23ME4A7pvoLDWbxZwhyGI8HcKfYOb8sLOzRhpCee+Ix3CmTPIsNBrm6ZHjnQch+boUPHsW+QQJxa+Mw33iyOGWKt2LjYhFl0nzl+eqUKZ6ABucpjlWUl/jGKVM8zC1qXkRaqqpbmK1T5njQYIY9wUS2pPmM7pwyx4uDwRLOLPjd1GjunDLHi6NBseoWT+Y+p2B7x33yYhcwyayLnJovTpnjifxpRGt80Yw5ux/3M1zpJe6b+KR5Td8pP/PwRax9Hy9zzuQfvGM0T1Hz8AP5k8f7ohfnqbCRCd4fzsY6SF74llgAAAAASUVORK5CYII="/>
        </symbol>

        <symbol id="advantages_3" viewBox="0 0 37 60">
            <metadata><?xpacket begin="﻿" id="W5M0MpCehiHzreSzNTczkc9d"?>
                <x:xmpmeta xmlns:x="adobe:ns:meta/"
                           x:xmptk="Adobe XMP Core 5.6-c138 79.159824, 2016/09/14-01:09:01        ">
                    <rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
                        <rdf:Description rdf:about=""/>
                    </rdf:RDF>
                </x:xmpmeta>
                <?xpacket end="w"?></metadata>
            <image id="Vector_Smart_Object" data-name="Vector Smart Object" width="37" height="60"
                   xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAACUAAAA8CAMAAADWmYNiAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAOVBMVEW2ucX///+2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucUAAADV6UpHAAAAEXRSTlMAACBwr9+/j1Bgz++AEECfMF4d4voAAAABYktHRBJ7vGwAAAAACXBIWXMAAAsSAAALEgHS3X78AAAAB3RJTUUH4wEVCigAVmlm7QAAAnBJREFUSMeNlte2rSAMRQWk9///2ZtAkKL7jJsnyzSkLILXtRgXt6xoSpvxjKEtjHV1MR/iB5WQcSFxvLbZI/eiAjCZr55hbcd3KkMsK4MmfPVlpQAS18uKBGxSANnrw6KrPg7KTKho5aTKNhLmqyIKLvOSJ9WBnoCDToUq+6caXt82JaMxv/FMNgpc2RHdPfIMA8O3SNkqe4FqDUt+EBHxDqm76muLbkbUelsrB6rW0u8oumG6r8EcLInf0Bo7hE7wc6ZrQEpd36ZaP5iAkkF0v6jQsmHp/ylBlA4/KINUonLVekQPcTUq1JtdkV7LUxaRcoQkoF6uvw7kc1mw1wtghtW7qfZbZIVqb6rHDhVaUmxShD460kBuypFTE3pkYEHznKIzjdKjYoB5bUpMAvVVSCldX6NhGIN8Nu3wKqGLXfdqasZmELVXYqgxoW6e3XFWdNiNsdNOkzV8QxBMeigL2+6Tyn2jsesvZ8MVUeLbGbha58TZnseV3WZO+HKWsUkrFT8iw6iOKfeRZm6d2yem7Jt3WmquDgoGwD4MVRfBMaNdV+Piin9QtMKMoEvgnPdq3ZmCVPii+CLpWeaTwikTX5cvajoo0+2kYooUDFVjhHjfUxPBjzPB9bdm7AQ83kqfvqB0WZ5qmNZU6kOBbdWmL0Kz0RmjXgXCFWKQjS97AmXb5CxjsH6fNZCA2+YeYzihxvCdHXg2MVE4LU8plLrrkcGSGb51u/xM3u5Zy7v0g/enQaCqnUPPef+2pPDYwtoXPBWzeYMcJ1TVzwSQ/VdDpJFdSqH/gGS+aCLhD8TLHEyoQznFajWnnLpDF0qj/gGWhSVksTX4ugAAAABJRU5ErkJggg=="/>
        </symbol>

        <symbol id="advantages_4" viewBox="0 0 65 47">
            <metadata><?xpacket begin="﻿" id="W5M0MpCehiHzreSzNTczkc9d"?>
                <x:xmpmeta xmlns:x="adobe:ns:meta/"
                           x:xmptk="Adobe XMP Core 5.6-c138 79.159824, 2016/09/14-01:09:01        ">
                    <rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
                        <rdf:Description rdf:about=""/>
                    </rdf:RDF>
                </x:xmpmeta>
                <?xpacket end="w"?></metadata>
            <image id="Vector_Smart_Object" data-name="Vector Smart Object" width="65" height="47"
                   xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEEAAAAvCAMAAACL11ddAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAANlBMVEW2ucX///+2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucUAAADG4ju6AAAAEHRSTlMAADC/gO+vYCAQz4+fQN9waxovfwAAAAFiS0dEEeK1PboAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfjARUKKB6sZluOAAABFklEQVRIx6XW2Q6EIAwFUEBxG3T6/187RWFERNPlPhFijoCANaYV15HTtwEP5HQPgO8pGQC8ewCcIWR/0GoBY1v9MNKBuzAxgZswL0ygFvhAJQiAqyABLgIHgCHvqkkItHY1awr5WKHwUa1BAAhWAMBaAHklWcBYAkkgA/NWA4dAB5YbsAsqIAo6AAUlgAL2e9KlvDUBFOi3cgmcCcatKTiYsL7FtQAY/tMsR/aacL4Jm8ssAlJzjIDVAvlcyIEkKIBD0AC7oAKiIAR8+ow29pN/9SVw3vaMXZ0BV5YNOAZyvZNv5Wt5YA07VX3BF+oCJQvTd5YBWcD+XgYkgVk63QUNsAsqIAo6AAUlgAKjhG6+iSO0h2p/z0EnPbT9iRMAAAAASUVORK5CYII="/>
        </symbol>

        <symbol id="advantages_5" viewBox="0 0 74 52">
            <metadata><?xpacket begin="﻿" id="W5M0MpCehiHzreSzNTczkc9d"?>
                <x:xmpmeta xmlns:x="adobe:ns:meta/"
                           x:xmptk="Adobe XMP Core 5.6-c138 79.159824, 2016/09/14-01:09:01        ">
                    <rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
                        <rdf:Description rdf:about=""/>
                    </rdf:RDF>
                </x:xmpmeta>
                <?xpacket end="w"?></metadata>
            <image id="Vector_Smart_Object" data-name="Vector Smart Object" width="74" height="52"
                   xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEoAAAA0CAMAAAAaJl7yAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAOVBMVEW2ucX///+2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucUAAADV6UpHAAAAEXRSTlMAACBQgK+/nxBgz9/vQI8wcMuk36EAAAABYktHRBJ7vGwAAAAACXBIWXMAAAsSAAALEgHS3X78AAAAB3RJTUUH4wEVCig752KPyQAAAyZJREFUSMetl9uCqyAMRSlyE0HM///sSUAgKHbaOZOXVsEFhp2LQnSTi9LGWLU4dvN1MXGx2cjqoZle/wO1bkjYtELTgf7GX6KcBgh7qsPJIsy636CSb08mWdxmALz7HuUChOX0PIRzynGyvkN5CPXdIkDzHnr/W9QO0LzEUMQ6vkNJACVmKFwjuK9QFjYxR6EPlXgdJDSTPkDhppYHlFB4CK8li3b7AHXwTXWUO3ABh8vkWQnd+TNKw35HRdIo/uJgmbZB/BkV+PtllCtBdGRxmTLNfIICkCPKUgia9bwMX6HEiKJolP2yoo5YzX2Gkm1DV1Q3/4waVkn8dTtKMdRwTiMqikdbwd/UrgYhcpSnQHuyHfQ9cAwEOUXtOf4fbIODJknTbaV48lNUHNUwGI3RpIM5CiM8cnfx/W5gn1AGjChO91UJFOHkrjhDrSxdjbbQEzRpB1XnK9oWuctNUOh472Yk9IkuU01H5cRDQ2aGSjD1vPN56VPrL7at4kU1QeUs7iak7JAxL+RtUTigu9YJilj+4i/0b5lLk4ChELKVI8EYU/FWUld80DJNJCqxUXSU6w/IsgZVYSi4MWdQUQV/UNS7qOjCyOaLCBc7825UhuPaRnK+a2a4bmS4oJjYO27JbimHkfZyNxjFHHePwdFo1xkX8mEY8WwfoIpGTEbZv0JpVp1/jSoQ8xeoLSvEv8ujH6JkSVU8JTvSVGCt6BwVqcn0uTCVuytWuYxqOl+qgnocTVCu1R9Vxy3YgqqxjLEYVExU5lvzdkdRoNslRRL1LlqMEyrUQiFr/ylST993lG7tpq3tCb6fK13fGbnYadX9pZpD7qg+RNTcLDlM6uXVPbkQG3Ze4Cx4dTeZS1QPQXwPepDK2dkhNzf2g1tgZpiXRxnWL5C1ySTRknaIeHzubi6jmAyxJtH9eFHc0Dy2i5uvDC/nPOszlONa3Wu6uKF2lkhkbyzHONB9kgyPJxjZirq3uyOKWr6T5Jsu7royTb9H8fcsOlEO2+qEVOGd2ik/01faYmjpp0BvfUZ4F4OpfYTu4hElZG5qN9Ur7DQzlC9aHYfxf+BHNpRFjGd7AAAAAElFTkSuQmCC"/>
        </symbol>

        <symbol id="advantages_6" viewBox="0 0 48 52">
            <metadata><?xpacket begin="﻿" id="W5M0MpCehiHzreSzNTczkc9d"?>
                <x:xmpmeta xmlns:x="adobe:ns:meta/"
                           x:xmptk="Adobe XMP Core 5.6-c138 79.159824, 2016/09/14-01:09:01        ">
                    <rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
                        <rdf:Description rdf:about=""/>
                    </rdf:RDF>
                </x:xmpmeta>
                <?xpacket end="w"?></metadata>
            <image id="Vector_Smart_Object" data-name="Vector Smart Object" width="48" height="52"
                   xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAA0CAMAAAD7TUujAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAANlBMVEW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucW2ucUAAAC8L4p3AAAAEHRSTlMA76+/nxDPQDDfcCCAj2BQImAqkwAAAAFiS0dEEeK1PboAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfjARUKKTf3z/KjAAAAqUlEQVRIx+WWzQ7DIAyDCWn4KXTz+z/txrSJ7gQ+TKKabxw+yXFIwIl3nKD/CBDarAGiswLEKEsWsHE1mMC4ohVxSYBTZPqgKosWvSBAx0o3LsR5hUVTuhaQcm+AtzHw3Cq591jHwA5/hssQqKjf9i8JHP0QkIZAAo76lkcex+pup8mRNAG40mfHPr6Yu3RvvogRrcDOvaKv1ObXjK+l+Vrx6/B7gPzCPQAJKRuVQVFmAAAAAABJRU5ErkJggg=="/>
        </symbol>

        <symbol id="basket" viewBox="0 0 19.25 19.25">
            <g>
                <g id="Layer_1_107_">
                    <g>
                        <path d="M19.006,2.97c-0.191-0.219-0.466-0.345-0.756-0.345H4.431L4.236,1.461     C4.156,0.979,3.739,0.625,3.25,0.625H1c-0.553,0-1,0.447-1,1s0.447,1,1,1h1.403l1.86,11.164c0.008,0.045,0.031,0.082,0.045,0.124     c0.016,0.053,0.029,0.103,0.054,0.151c0.032,0.066,0.075,0.122,0.12,0.179c0.031,0.039,0.059,0.078,0.095,0.112     c0.058,0.054,0.125,0.092,0.193,0.13c0.038,0.021,0.071,0.049,0.112,0.065c0.116,0.047,0.238,0.075,0.367,0.075     c0.001,0,11.001,0,11.001,0c0.553,0,1-0.447,1-1s-0.447-1-1-1H6.097l-0.166-1H17.25c0.498,0,0.92-0.366,0.99-0.858l1-7     C19.281,3.479,19.195,3.188,19.006,2.97z M17.097,4.625l-0.285,2H13.25v-2H17.097z M12.25,4.625v2h-3v-2H12.25z M12.25,7.625v2     h-3v-2H12.25z M8.25,4.625v2h-3c-0.053,0-0.101,0.015-0.148,0.03l-0.338-2.03H8.25z M5.264,7.625H8.25v2H5.597L5.264,7.625z      M13.25,9.625v-2h3.418l-0.285,2H13.25z"/>
                        <circle cx="6.75" cy="17.125" r="1.5"/>
                        <circle cx="15.75" cy="17.125" r="1.5"/>
                    </g>
                </g>
            </g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
        </symbol>

        <symbol id="star" viewBox="0 0 19.481 19.481">
            <g>
                <path d="m10.201,.758l2.478,5.865 6.344,.545c0.44,0.038 0.619,0.587 0.285,0.876l-4.812,4.169 1.442,6.202c0.1,0.431-0.367,0.77-0.745,0.541l-5.452-3.288-5.452,3.288c-0.379,0.228-0.845-0.111-0.745-0.541l1.442-6.202-4.813-4.17c-0.334-0.289-0.156-0.838 0.285-0.876l6.344-.545 2.478-5.864c0.172-0.408 0.749-0.408 0.921,0z"/>
            </g>
        </symbol>

        <symbol id="logo" viewBox="0 0 244 29">
            <metadata><?xpacket begin="﻿" id="W5M0MpCehiHzreSzNTczkc9d"?>
                <x:xmpmeta xmlns:x="adobe:ns:meta/"
                           x:xmptk="Adobe XMP Core 5.6-c138 79.159824, 2016/09/14-01:09:01        ">
                    <rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
                        <rdf:Description rdf:about=""/>
                    </rdf:RDF>
                </x:xmpmeta>
                <?xpacket end="w"?></metadata>
            <image id="Vector_Smart_Object_copy" data-name="Vector Smart Object copy"
                   xlink:href="data:img/png;base64,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"/>
        </symbol>

        <symbol id="location" x="0px" y="0px" viewBox="0 0 413.099 413.099" style="enable-background:new 0 0 413.099 413.099;" >
            <g> <g> <path d="M206.549,0L206.549,0c-82.6,0-149.3,66.7-149.3,149.3c0,28.8,9.2,56.3,22,78.899l97.3,168.399c6.1,11,18.4,16.5,30,16.5
			c11.601,0,23.3-5.5,30-16.5l97.3-168.299c12.9-22.601,22-49.601,22-78.901C355.849,66.8,289.149,0,206.549,0z M206.549,193.4
			c-30,0-54.5-24.5-54.5-54.5s24.5-54.5,54.5-54.5s54.5,24.5,54.5,54.5C261.049,169,236.549,193.4,206.549,193.4z"/> </g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g>
        </symbol>

        <symbol id="locked" x="0px" y="0px" viewBox="0 0 486.733 486.733" style="enable-background:new 0 0 486.733 486.733;" >
            <g> <path d="M403.88,196.563h-9.484v-44.388c0-82.099-65.151-150.681-146.582-152.145c-2.225-0.04-6.671-0.04-8.895,0
		C157.486,1.494,92.336,70.076,92.336,152.175v44.388h-9.485c-14.616,0-26.538,15.082-26.538,33.709v222.632
		c0,18.606,11.922,33.829,26.539,33.829h321.028c14.616,0,26.539-15.223,26.539-33.829V230.272
		C430.419,211.646,418.497,196.563,403.88,196.563z M273.442,341.362v67.271c0,7.703-6.449,14.222-14.158,14.222H227.45
		c-7.71,0-14.159-6.519-14.159-14.222v-67.271c-7.477-7.36-11.83-17.537-11.83-28.795c0-21.334,16.491-39.666,37.459-40.513
		c2.222-0.09,6.673-0.09,8.895,0c20.968,0.847,37.459,19.179,37.459,40.513C285.272,323.825,280.919,334.002,273.442,341.362z
		 M331.886,196.563h-84.072h-8.895h-84.072v-44.388c0-48.905,39.744-89.342,88.519-89.342c48.775,0,88.521,40.437,88.521,89.342
		V196.563z"/> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g>
        </symbol>

        <symbol id="user" x="0px" y="0px" viewBox="0 0 350 350" style="enable-background:new 0 0 350 350;" xml:space="preserve">
        <g> <path d="M175,171.173c38.914,0,70.463-38.318,70.463-85.586C245.463,38.318,235.105,0,175,0s-70.465,38.318-70.465,85.587
		C104.535,132.855,136.084,171.173,175,171.173z"/> <path d="M41.909,301.853C41.897,298.971,41.885,301.041,41.909,301.853L41.909,301.853z"/>
            <path d="M308.085,304.104C308.123,303.315,308.098,298.63,308.085,304.104L308.085,304.104z"/> <path d="M307.935,298.397c-1.305-82.342-12.059-105.805-94.352-120.657c0,0-11.584,14.761-38.584,14.761
		s-38.586-14.761-38.586-14.761c-81.395,14.69-92.803,37.805-94.303,117.982c-0.123,6.547-0.18,6.891-0.202,6.131
		c0.005,1.424,0.011,4.058,0.011,8.651c0,0,19.592,39.496,133.08,39.496c113.486,0,133.08-39.496,133.08-39.496
		c0-2.951,0.002-5.003,0.005-6.399C308.062,304.575,308.018,303.664,307.935,298.397z"/> </g>
            <g> </g> <g> </g> <g>
            </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g>
    </symbol>

        <symbol id="heart" x="0px" y="0px" viewBox="0 0 510 510" style="enable-background:new 0 0 510 510;" >
            <g> <g id="favorite"> <path d="M255,489.6l-35.7-35.7C86.7,336.6,0,257.55,0,160.65C0,81.6,61.2,20.4,140.25,20.4c43.35,0,86.7,20.4,114.75,53.55
			C283.05,40.8,326.4,20.4,369.75,20.4C448.8,20.4,510,81.6,510,160.65c0,96.9-86.7,175.95-219.3,293.25L255,489.6z"/>
                </g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g>
        </symbol>

        <symbol id="statistic" x="0px" y="0px" viewBox="0 0 512 512">
            <g>
                <g>
                    <path d="M501.333,448h-10.667V32c0-5.896-4.771-10.667-10.667-10.667H373.333c-5.896,0-10.667,4.771-10.667,10.667v416H320
			V138.667c0-5.896-4.771-10.667-10.667-10.667H202.667c-5.896,0-10.667,4.771-10.667,10.667V448h-42.667V245.333
			c0-5.896-4.771-10.667-10.667-10.667H32c-5.896,0-10.667,4.771-10.667,10.667V448H10.667C4.771,448,0,452.771,0,458.667V480
			c0,5.896,4.771,10.667,10.667,10.667h490.667c5.896,0,10.667-4.771,10.667-10.667v-21.333C512,452.771,507.229,448,501.333,448z"/>
                </g>
            </g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
        </symbol>

        <symbol id="phone" x="0px" y="0px" viewBox="0 0 35 35" style="enable-background:new 0 0 35 35;" >
            <g> <path d="M25.302,0H9.698c-1.3,0-2.364,1.063-2.364,2.364v30.271C7.334,33.936,8.398,35,9.698,35h15.604
		c1.3,0,2.364-1.062,2.364-2.364V2.364C27.666,1.063,26.602,0,25.302,0z M15.004,1.704h4.992c0.158,0,0.286,0.128,0.286,0.287
		c0,0.158-0.128,0.286-0.286,0.286h-4.992c-0.158,0-0.286-0.128-0.286-0.286C14.718,1.832,14.846,1.704,15.004,1.704z M17.5,33.818
		c-0.653,0-1.182-0.529-1.182-1.183s0.529-1.182,1.182-1.182s1.182,0.528,1.182,1.182S18.153,33.818,17.5,33.818z M26.021,30.625
		H8.979V3.749h17.042V30.625z"/> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g>
        </symbol>

        <symbol id="switch_table" x="0px" y="0px" viewBox="0 0 341.333 341.333">
            <g>
                <g>
                    <g>
                        <rect x="128" y="128" width="85.333" height="85.333"/>
                        <rect x="0" y="0" width="85.333" height="85.333"/>
                        <rect x="128" y="256" width="85.333" height="85.333"/>
                        <rect x="0" y="128" width="85.333" height="85.333"/>
                        <rect x="0" y="256" width="85.333" height="85.333"/>
                        <rect x="256" y="0" width="85.333" height="85.333"/>
                        <rect x="128" y="0" width="85.333" height="85.333"/>
                        <rect x="256" y="128" width="85.333" height="85.333"/>
                        <rect x="256" y="256" width="85.333" height="85.333"/>
                    </g>
                </g>
            </g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
        </symbol>

        <symbol id="switch_list" x="0px" y="0px" viewBox="0 0 344.339 344.339">
            <g>
                <g>
                    <g>
                        <rect y="46.06" width="344.339" height="29.52"/>
                    </g>
                    <g>
                        <rect y="156.506" width="344.339" height="29.52"/>
                    </g>
                    <g>
                        <rect y="268.748" width="344.339" height="29.531"/>
                    </g>
                </g>
            </g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
            <g></g>
        </symbol>

        <symbol id="arrow" viewBox="0 0 129 129">
            <g>
                <path d="m40.4,121.3c-0.8,0.8-1.8,1.2-2.9,1.2s-2.1-0.4-2.9-1.2c-1.6-1.6-1.6-4.2 0-5.8l51-51-51-51c-1.6-1.6-1.6-4.2 0-5.8 1.6-1.6 4.2-1.6 5.8,0l53.9,53.9c1.6,1.6 1.6,4.2 0,5.8l-53.9,53.9z"/>
            </g>
        </symbol>

        <symbol id="right-arrow" x="0px" y="0px" viewBox="0 0 31.49 31.49" style="enable-background:new 0 0 31.49 31.49;" > <path d="M21.205,5.007c-0.429-0.444-1.143-0.444-1.587,0c-0.429,0.429-0.429,1.143,0,1.571l8.047,8.047H1.111
	C0.492,14.626,0,15.118,0,15.737c0,0.619,0.492,1.127,1.111,1.127h26.554l-8.047,8.032c-0.429,0.444-0.429,1.159,0,1.587
	c0.444,0.444,1.159,0.444,1.587,0l9.952-9.952c0.444-0.429,0.444-1.143,0-1.571L21.205,5.007z"/> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> </symbol>

        <symbol id="basket_list" x="0px" y="0px" viewBox="0 0 60.123 60.123" style="enable-background:new 0 0 60.123 60.123;" xml:space="preserve">
        <g> <path d="M57.124,51.893H16.92c-1.657,0-3-1.343-3-3s1.343-3,3-3h40.203c1.657,0,3,1.343,3,3S58.781,51.893,57.124,51.893z"/> <path d="M57.124,33.062H16.92c-1.657,0-3-1.343-3-3s1.343-3,3-3h40.203c1.657,0,3,1.343,3,3
		C60.124,31.719,58.781,33.062,57.124,33.062z"/> <path d="M57.124,14.231H16.92c-1.657,0-3-1.343-3-3s1.343-3,3-3h40.203c1.657,0,3,1.343,3,3S58.781,14.231,57.124,14.231z"/> <circle cx="4.029" cy="11.463" r="4.029"/> <circle cx="4.029" cy="30.062" r="4.029"/> <circle cx="4.029" cy="48.661" r="4.029"/>
        </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g>
            <g> </g> <g> </g> <g> </g> <g> </g> </symbol>

        <symbol id="add" x="0px" y="0px" viewBox="0 0 491.86 491.86" style="enable-background:new 0 0 491.86 491.86;" xml:space="preserve">
        <g> <g> <path d="M465.167,211.614H280.245V26.691c0-8.424-11.439-26.69-34.316-26.69s-34.316,18.267-34.316,26.69v184.924H26.69
			C18.267,211.614,0,223.053,0,245.929s18.267,34.316,26.69,34.316h184.924v184.924c0,8.422,11.438,26.69,34.316,26.69
                s34.316-18.268,34.316-26.69V280.245H465.17c8.422,0,26.69-11.438,26.69-34.316S473.59,211.614,465.167,211.614z"/>
            </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g>
            </g> <g> </g> <g> </g> <g> </g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g>
            </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g>
    </symbol>

    </svg>';?>

    <!-- Owl Carousel-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.3/owl.carousel.js"></script>
    <link rel="stylesheet prefetch"
          href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.3/assets/owl.carousel.css">

    <!-- Fotorama-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.min.js"></script>
    <link rel="stylesheet prefetch" href="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.min.css">

    <!-- Sly -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sly/1.6.1/sly.js"></script> <!-- Menu -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.mmenu/7.2.2/jquery.mmenu.all.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jQuery.mmenu/7.2.2/jquery.mmenu.all.css">

	<?
    $asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/site_files/plugins/fancybox2/lib/jquery.mousewheel-3.0.6.pack.js");
    $asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/site_files/plugins/fancybox2/source/jquery.fancybox.pack.js?v=2.1.5");
    $asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/site_files/plugins/fancybox2/source/jquery.fancybox.min.css?v=2.1.5");

	$asset->addJs(SITE_TEMPLATE_PATH . '/js/main.js');
    $asset->addCss(SITE_TEMPLATE_PATH."/jquery.formstyler.css");
    $asset->addCss(SITE_TEMPLATE_PATH."/jquery.formstyler.theme.css");
    $asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/jquery.formstyler.min.js");
	?>



    </body>
    </html>
