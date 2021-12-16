/*
 *  Document   : app.js
 *  Author     : pixelcave
 *  Description: Custom scripts and plugin initializations (available to all pages)
 *
 *  Feel free to remove the plugin initilizations from uiInit() if you would like to
 *  use them only in specific pages. Also, if you remove a js plugin you won't use, make
 *  sure to remove its initialization from uiInit().
 */

var App = function () {

    /* Helper variables - set in uiInit() */
    var page, pageContent, header, footer, sidebar, sidebarAlt, sScroll, lasturl, base_url;


    /* Initialization UI Code */
    var uiInit = function () {


        /*
         const target = $('document')[0]; // Get DOM element from jQuery collection

         $('#fullscreen').on('click', function () {
         if (screenfull.enabled) {
         screenfull.toggle(target);
         screenfull.onerror(event, function () {
         console.error('Failed to enable fullscreen', event);
         });
         }
         });*/
        base_url = $("#base_url").val();
        // Set variables - Cache some often used Jquery objects in variables */
        page = $('#page-container');
        pageContent = $('#page-content');
        header = $('header');
        footer = $('#page-content + footer');
        sidebar = $('#sidebar');
        sidebarAlt = $('#sidebar-alt');
        sScroll = $('.sidebar-scroll');

        //Iicialia la carga del menu con ajax
        menulinks();
        // Initialize sidebars functionality
        handleSidebar('init');

        // Sidebar navigation functionality
        handleNav();

        // Interactive blocks functionality
        interactiveBlocks();

        // Scroll to top functionality
        scrollToTop();


        // Resize #page-content to fill empty space if exists (also add it to resize and orientationchange events)
        resizePageContent();

        inizializePlugins();


        // Template Options, change features
        templateOptions();
    };

    var events = function (cajasabiertas, aperturocaja, caja_id) {
        $(".myadmin-alert .closed").click(function (event) {
            $(this).parents(".myadmin-alert").fadeToggle(350);
            return false;
        });

        $('#fullscreen').on('click', function () {
            pantallaCompleta();
        });


        $.ajaxSetup({
            headers: {
                'X-api-key': sessionStorage.api_key
                //'x-api-key': $('meta[name="x-api-key"]').attr('content')
            }
        });

        //checkTimeout = setTimeout(checkSession, 60000);

        $(document).ajaxComplete(function (event, xhr, settings) {
            //  Utilities.hiddePreloader();
        });
        $(document).ajaxStop(function (event, xhr, settings) {
            //  Utilities.hiddePreloader();
        });
        $(document).ajaxError(function (event, xhr, settings) {
            // Utilities.hiddePreloader();
        });


        $(document).ajaxSend(function (event, request, settings) {


            if (settings.url.indexOf("very_sesion") != -1 || settings.url.indexOf("renew_session") != -1 || settings.url.indexOf("checkSysExpDat") != -1) {
                //console.log('escapando a la validacion de session');
            } else {
                // checkSession();
                checkSysExpDat();
            }

        });

        //ESTO RENUEVA LA SESSION SI ESTAS USANDO EL SISTEMA
        $("body").mouseup(function () {
            // renewSession();
        });

        $("input").blur(function () {
//            renewSession();
        });

        $("input").focus(function () {
            //          renewSession();
        });


        $('#fecha').datepicker({todayHighlight: true});

        $('body').on('keydown', function (e) {


            //console.log(e.keyCode);

            if (e.keyCode == 116) {
                e.preventDefault();
                e.stopPropagation();
                // $(this).next().focus();  //Use whatever selector necessary to focus the 'next' input
                return false;
            }


            //F3
            if (e.keyCode == 114) {
                e.preventDefault();
                if ($(".modal").is(":visible")) {
                    return false;
                } else {

                    Utilities.showPreloader();

                    $.ajax({
                        url: base_url + 'venta',
                        success: function (data) {

                            if (data.error == undefined) {

                                $('#page-content').html(data);

                            } else {

                                Utilities.alertModal(data.error, 'warning');
                                $(this).prop('disabled', true);

                            }

                            Utilities.hiddePreloader();

                        },
                        error: function (response) {
                            Utilities.hiddePreloader();
                            Utilities.alertModal('Ha ocurrido un error al realizar la operacion', 'warning');

                            $(this).prop('disabled', true);

                        }
                    })

                }
            }


            //F2
            if (e.keyCode == 113) {

                e.preventDefault();

                if ($(".modal").is(":visible")) {
                    return false;
                }
                Utilities.showPreloader();

                $.ajax({
                    url: base_url + 'producto/stock',
                    success: function (data) {

                        if (data.error == undefined) {

                            $('#page-content').html(data);


                        } else {

                            Utilities.alertModal(data.error, 'warning');

                            $(this).prop('disabled', true);

                        }


                        Utilities.hiddePreloader();

                    },
                    error: function (response) {
                        Utilities.hiddePreloader();
                        Utilities.alertModal('Ha ocurrido un error al realizar la operacion', 'warning');


                        $(this).prop('disabled', true);

                    }
                })

            }
        });


        $('body').on('keypress', function (e) {


            // console.log(e.keyCode);
            if (e.which == 13) // Enter key = keycode 13
            {
                e.preventDefault();
                e.stopPropagation();
                // $(this).next().focus();  //Use whatever selector necessary to focus the 'next' input
                return false;
            }
        });


        StatusCaja.init(cajasabiertas, aperturocaja, caja_id);

    }


    var pantallaCompleta = function () {


        const target = $('document')[0]; // Get DOM element from jQuery collection
        if (screenfull.enabled) {
            screenfull.toggle(target);
            screenfull.onerror(event, function () {
                console.error('Failed to enable fullscreen', event);
            });
        }


    };

    var inizializePlugins = function () {

        /*$.fn.datepicker.dates['en'] = {
         days: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"],
         daysShort: ["Dom", "Lun", "Mie", "Mar", "Jue", "Vie", "Sab"],
         daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
         months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
         monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dec"],
         today: "Hoy",
         clear: "Clear",
         format: "dd-mm-yyyy",
         titleFormat: "MM yyyy",
         weekStart: 0,
         todayHighlight: true
         };
         */

        $(window).resize(function () {
            resizePageContent();
        });
        $(window).bind('orientationchange', resizePageContent);

        // Add the correct copyright year at the footer
        var yearCopy = $('#year-copy'), d = new Date();
        if (d.getFullYear() === 2014) {
            yearCopy.html('2014');
        } else {
            yearCopy.html('2014-' + d.getFullYear().toString().substr(2, 2));
        }

        // Initialize chat demo functionality (in sidebar)
        //chatUi();

        // Initialize tabs
        $('[data-toggle="tabs"] a, .enable-tabs a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        // Initialize Tooltips
        $('[data-toggle="tooltip"], .enable-tooltip').tooltip({animation: false});

        // Initialize Popovers
        $('[data-toggle="popover"], .enable-popover').popover({animation: true});


        // Initialize Chosen
        $('.select-chosen').chosen({width: "100%"});


        // Initialize Slider for Bootstrap
        $('.input-slider').slider();

        // Initialize Tags Input
        //
        //
        // $('.input-tags').tagsInput({width: 'auto', height: 'auto'});

        // Initialize Datepicker
        $('.input-datepicker, .input-daterange').datepicker({weekStart: 1, format: 'dd-mm-yyyy'});
        $('.input-datepicker-close').datepicker({weekStart: 1, format: 'dd-mm-yyyy'}).on('changeDate', function (e) {
            $(this).datepicker('hide');
        });

        // Initialize Timepicker
        // $('.input-timepicker').timepicker({minuteStep: 1, showSeconds: true, showMeridian: true});
        //$('.input-timepicker24').timepicker({minuteStep: 1, showSeconds: true, showMeridian: false});


        // Initialize Placeholder
        // $('input, textarea').placeholder();
    }

    /* Page Loading functionality */
    var pageLoading = function () {
        var pageWrapper = $('#page-wrapper');

        if (pageWrapper.hasClass('page-loading')) {
            pageWrapper.removeClass('page-loading');
        }

    };

    /* Gets window width cross browser */
    var getWindowWidth = function () {
        return window.innerWidth
            || document.documentElement.clientWidth
            || document.body.clientWidth;
    };

    /* Sidebar Navigation functionality */
    var handleNav = function () {

        // Animation Speed, change the values for different results
        var upSpeed = 250;
        var downSpeed = 250;

        // Get all vital links
        var menuLinks2 = $('.sidebar-nav-menu');
        var submenuLinks2 = $('.sidebar-nav-submenu');

        // Primary Accordion functionality
        menuLinks2.click(function () {
            var link = $(this);

            if (link.parent().hasClass('active') !== true) {
                if (link.hasClass('open')) {
                    link.removeClass('open').next().slideUp(upSpeed, function () {
                        handlePageScroll(link, 200, 300);
                    });

                    // Resize #page-content to fill empty space if exists
                    setTimeout(resizePageContent, upSpeed);
                } else {
                    $('.sidebar-nav-menu.open').removeClass('open').next().slideUp(upSpeed);
                    link.addClass('open').next().slideDown(downSpeed, function () {
                        handlePageScroll(link, 150, 600);
                    });

                    // Resize #page-content to fill empty space if exists
                    setTimeout(resizePageContent, ((upSpeed > downSpeed) ? upSpeed : downSpeed));
                }
            }

            return false;
        });

        // Submenu Accordion functionality
        submenuLinks2.click(function () {
            var link = $(this);

            if (link.parent().hasClass('active') !== true) {
                if (link.hasClass('open')) {
                    link.removeClass('open').next().slideUp(upSpeed, function () {
                        handlePageScroll(link, 200, 300);
                    });

                    // Resize #page-content to fill empty space if exists
                    setTimeout(resizePageContent, upSpeed);
                } else {
                    link.closest('ul').find('.sidebar-nav-submenu.open').removeClass('open').next().slideUp(upSpeed);
                    link.addClass('open').next().slideDown(downSpeed, function () {
                        handlePageScroll(link, 150, 600);
                    });

                    // Resize #page-content to fill empty space if exists
                    setTimeout(resizePageContent, ((upSpeed > downSpeed) ? upSpeed : downSpeed));
                }
            }

            return false;
        });
    };

    /* Scrolls the page (static layout) or the sidebar scroll element (fixed header/sidebars layout) to a specific position - Used when a submenu opens */
    var handlePageScroll = function (sElem, sHeightDiff, sSpeed) {
        if (!page.hasClass('disable-menu-autoscroll')) {
            var elemScrollToHeight;

            // If we have a static layout scroll the page
            if (!header.hasClass('navbar-fixed-top') && !header.hasClass('navbar-fixed-bottom')) {
                var elemOffsetTop = sElem.offset().top;

                elemScrollToHeight = (((elemOffsetTop - sHeightDiff) > 0) ? (elemOffsetTop - sHeightDiff) : 0);

                $('html, body').animate({scrollTop: elemScrollToHeight}, sSpeed);
            } else { // If we have a fixed header/sidebars layout scroll the sidebar scroll element
                var sContainer = sElem.parents('.sidebar-scroll');
                var elemOffsetCon = sElem.offset().top + Math.abs($('div:first', sContainer).offset().top);

                elemScrollToHeight = (((elemOffsetCon - sHeightDiff) > 0) ? (elemOffsetCon - sHeightDiff) : 0);
                sContainer.animate({scrollTop: elemScrollToHeight}, sSpeed);
            }
        }
    };


    var togleSidebar = function () {
        if ($("body").hasClass("content-wrapper")) {
            $("body").trigger("resize");
            $(".sidebar-nav, .slimScrollDiv").css("overflow", "hidden").parent().css("overflow", "visible");
            $("body").removeClass("content-wrapper");
            $(".open-close i").addClass("icon-arrow-left-circle");
            $(".logo span").show();
        } else {
            $("body").trigger("resize");
            $(".sidebar-nav, .slimScrollDiv").css("overflow-x", "visible").parent().css("overflow", "visible");
            $("body").addClass("content-wrapper");
            $(".open-close i").removeClass("icon-arrow-left-circle");
            $(".logo span").hide();
        }
    }
    /* Sidebar Functionality */
    var handleSidebar = function (mode, extra) {
        if (mode == 'close-sidebar') {

            $("body").trigger("resize");
            $(".sidebar-nav, .slimScrollDiv").css("overflow-x", "visible").parent().css("overflow", "visible");
            $("body").addClass("content-wrapper");
            $(".open-close i").removeClass("icon-arrow-left-circle");
            $(".logo span").hide();
        } else {

            if ($("body").hasClass("content-wrapper")) {
                $("body").trigger("resize");
                $(".sidebar-nav, .slimScrollDiv").css("overflow", "hidden").parent().css("overflow", "visible");
                $("body").removeClass("content-wrapper");
                $(".open-close i").addClass("icon-arrow-left-circle");
                $(".logo span").show();
            } else {
                $("body").trigger("resize");
                $(".sidebar-nav, .slimScrollDiv").css("overflow-x", "visible").parent().css("overflow", "visible");
                $("body").addClass("content-wrapper");
                $(".open-close i").removeClass("icon-arrow-left-circle");
                $(".logo span").hide();
            }
        }


    };


    /* Resize #page-content to fill empty space if exists */
    var resizePageContent = function () {
        var windowH = $(window).height();
        var sidebarH = sidebar.outerHeight();
        var sidebarAltH = sidebarAlt.outerHeight();
        var headerH = header.outerHeight();
        var footerH = footer.outerHeight();

        // If we have a fixed sidebar/header layout or each sidebars’ height < window height
        if (header.hasClass('navbar-fixed-top') || header.hasClass('navbar-fixed-bottom') || ((sidebarH < windowH) && (sidebarAltH < windowH))) {
            if (page.hasClass('footer-fixed')) { // if footer is fixed don't remove its height
                pageContent.css('min-height', windowH - headerH + 'px');
            } else { // else if footer is static, remove its height
                pageContent.css('min-height', windowH - (headerH + footerH) + 'px');
            }
        } else { // In any other case set #page-content height the same as biggest sidebar's height
            if (page.hasClass('footer-fixed')) { // if footer is fixed don't remove its height
                pageContent.css('min-height', ((sidebarH > sidebarAltH) ? sidebarH : sidebarAltH) - headerH + 'px');
            } else { // else if footer is static, remove its height
                pageContent.css('min-height', ((sidebarH > sidebarAltH) ? sidebarH : sidebarAltH) - (headerH + footerH) + 'px');
            }
        }
    };
    setupSessionTimeoutCheck = function () {
        clearTimeout(checkTimeout);
        checkTimeout = setTimeout(checkSession, 900000);
    };

    renewSession = function () {
        $.ajax({
            url: base_url + "inicio/renew_sesion",
            type: "POST",
            data: {}
        });
    };

// var checkTimeout;
    var checkSession = function () {
        $.ajax({
            url: base_url + 'inicio/very_sesion',
            type: "POST",
            success: function (result) {
                if (result === "false") {
                    //window.location = base_url + "logout?err=session";
                }

            },
            complete: function () {
                //setTimeout(setupSessionTimeoutCheck(), 60000);

            }
        });
    };


    var checkSysExpDat = function () {
        $.ajax({
            url: base_url + 'inicio/checkSysExpDat',
            type: "POST",
            dataType:'json',
			data:{tokenfirebase:tokenControlAmbiental},
            success: function (data) {
                var remaining = parseInt(data.remaining);

                //si trae datos del control ambiental, los recorro, y los mando a mostrar en pantalla si no ha sido notificado.
                if(Object.keys(data.notif_control_amb).length>0){
                    for(var i=0;i <Object.keys(data.notif_control_amb).length;i++){

                        if( control_items[data.notif_control_amb[i].alias]==undefined ||
                            control_items[data.notif_control_amb[i].alias]==''){

                            //cuando se cierre el toast alert, llamara a la funcion Utilities.closeNotifiControlAmb

                            control_items[data.notif_control_amb[i].alias]='';
                            control_items[data.notif_control_amb[i].alias]= Utilities.alertModal(data.notif_control_amb[i].mensaje,
                                'info',false,data.notif_control_amb[i].alias,ControlAmbiental.closeNotifiControlAmb);
                            control_items[data.notif_control_amb[i].alias].dia='';
                            control_items[data.notif_control_amb[i].alias].control_id='';
                            control_items[data.notif_control_amb[i].alias].dia=data.notif_control_amb[i].dia
                            control_items[data.notif_control_amb[i].alias].control_id=data.notif_control_amb[i].control_id;
                        }

                    }
                }

                var diasrenovar = 8 + remaining;
                if(remaining<=5 && remaining<0){

                    //Utilities.alertModal('Su licencia ha expirado hace '+remaining+' dias, recuerde renovar su licencia antes de '+diasrenovar+' dias o no podrá seguir usando el sistema', 'warning');
                }

                if(remaining<=5 && remaining>0){

                    //Utilities.alertModal('Su licencia está a '+remaining+' dias de expirar', 'warning');
                }

                if (data.result === "error") {
                 window.location.href = base_url + "logout?err=exp";
                }
            },error:function () {
                //window.location.href = base_url + "logout?err=licerr";


            },
            complete: function () {
                //setTimeout(setupSessionTimeoutCheck(), 60000);

            }
        });
    };

    /* Interactive blocks functionality */
    var interactiveBlocks = function () {



        // Toggle block's content
        $('[data-toggle="block-toggle-content"]').on('click', function () {
            var blockContent = $(this).closest('.block').find('.block-content');

            if ($(this).hasClass('active')) {
                blockContent.slideDown();
            } else {
                blockContent.slideUp();
            }

            $(this).toggleClass('active');
        });

        // Toggle block fullscreen
        $('[data-toggle="block-toggle-fullscreen"]').on('click', function () {
            var block = $(this).closest('.block');

            if ($(this).hasClass('active')) {
                block.removeClass('block-fullscreen');
            } else {
                block.addClass('block-fullscreen');
            }

            $(this).toggleClass('active');
        });

        // Hide block
        $('[data-toggle="block-hide"]').on('click', function () {
            $(this).closest('.block').fadeOut();
        });
    };

    var menulinks = function () {
        //checkURL();	//check if the URL has a reference to a page and load it
        $('.menulink').off();
        $('.menulink').on('click', function (e) {	//traverse through all our navigation links..


            e.preventDefault();
            Utilities.showPreloader();


            checkURL($(this).attr('href'));	//.. and assign them a new onclick event, using their own hash as a parameter (#page1 for example)


        });

    };

    var checkURL = function (hash) {

        if (!hash) hash = window.location.hash;	//if no parameter is provided, use the hash value from the current address

        /*cuando se ingresa a ventas, y se selecciona un cliente, se muestra una alerta con sus datos
        * entonces, si se sale de la venta, se elimina esa alerta toast*/
        if (Venta != undefined && Venta.toastCliSelected != undefined && Venta.toastCliSelected != "") {
            Venta.toastCliSelected.reset();
        }

        lasturl = hash;	//update the current hash

        loadPage(hash);	// and load the new page

    }
    var very_session = function () {


        return $.ajax({	//create an ajax request to load_page.php
            type: "POST",
            url: base_url + 'inicio/very_sesion',
            dataType: "json",
        });
    }
    var loadPage = function (url)	//the function that loads pages via AJAX
    {
        //strip the #page part of the hash and leave only the page number
// se cometna porque ya se esta llamando al chequeo de licencia y se unificó
        /* very_session().success(function (sesion) {

             if (sesion == false)	//if no errors
               {
                   Utilities.hiddePreloader();
                   Utilities.alertModal('<h4>El tiempo de su sessión ha expirado!</h4>',
                       'warning', true);
                   setTimeout(function () {
                       //location.href = base_url + 'inicio';
                   }, 500)

               } else {
                   if (url != false) {*/

                        $.ajax({	//create an ajax request to load_page.php
                            type: "POST",
                            //url: "<?= $ruta?>principal/getPage",
                            url: url,
                            data: 'page=' + url,	//with the page number as a parameter
                            dataType: "html",	//expect html to be returned
                            success: function (msg) {

                                if (parseInt(msg) != 0)	//if no errors
                                {
                                    $('#page-content').html(msg);	//load the returned html into pageContet
                                    inizializePlugins();
                                    menulinks();
                                    Utilities.hiddePreloader();
                                }
                            },
                            error: function () {
                                Utilities.alertModal('Ha ocurido un error al cargar la vista', 'danger');
                                Utilities.hiddePreloader();
                            }

                        });
                   /* }
                }
            }
        );*/


    }


    /* Scroll to top functionality */
    var scrollToTop = function () {
        // Get link
        var link = $('#to-top');
        $(window).scroll(function () {
            // If the user scrolled a bit (150 pixels) show the link in large resolutions
            if (($(this).scrollTop() > 150) && (getWindowWidth() > 991)) {
                link.fadeIn(100);
            } else {
                link.fadeOut(100);
            }
        });
        // On click get to top
        link.click(function () {
            $('html, body').animate({scrollTop: 0}, 400);
            return false;
        });
    };


    /* Template Options, change features functionality */
    var templateOptions = function () {
        /*
         * Color Themes
         */

        var colorList = $('.sidebar-themes');
        var themeLink = $('#theme-link');
        var theme;
        if (themeLink.length) {

            theme = themeLink.attr('href');

            $('li', colorList).removeClass('active');
            $('a[data-theme="' + theme + '"]', colorList).parent('li').addClass('active');

            if (theme != 'default') {
                if (theme.length > 7) {

                    themeLink.attr('href', theme);
                } else {
                    theme = base_url + "recursosback/css/themes/" + theme + ".css";

                    themeLink.attr('href', theme);
                }
            }

        }

        $('a', colorList).click(function (e) {
            theme = base_url + "recursosback/" + $(this).data('theme');

            $.ajax({
                url: base_url + 'inicio/validarTema',
                type: 'POST',
                headers: {
                    Accept: 'application/json'
                },
                data: {'ruta': theme},
                success: function (data) {


                    $('li', colorList).removeClass('active');
                    $(this).parent('li').addClass('active');

                    if (theme === 'default') {
                        if (themeLink.length) {
                            themeLink.remove();
                            themeLink = $('#theme-link');
                        }
                    } else {
                        if (themeLink.length) {

                            themeLink.attr('href', theme);

                        } else {

                            $('link[href="css/themes.css"]').before('<link id="theme-link" rel="stylesheet" href="' + theme + '">');
                            themeLink = $('#theme-link');
                        }
                    }
                }
            })

        });

        // Prevent template options dropdown from closing on clicking options
        $('.dropdown-options a').click(function (e) {
            e.stopPropagation();
        });

        /* Page Style */
        var optMainStyle = $('#options-main-style');
        var optMainStyleAlt = $('#options-main-style-alt');

        if (page.hasClass('style-alt')) {
            optMainStyleAlt.addClass('active');
        } else {
            optMainStyle.addClass('active');
        }

        optMainStyle.click(function () {
            page.removeClass('style-alt');
            $(this).addClass('active');
            optMainStyleAlt.removeClass('active');
        });

        optMainStyleAlt.click(function () {
            page.addClass('style-alt');
            $(this).addClass('active');
            optMainStyle.removeClass('active');
        });

        /* Header options */
        var optHeaderDefault = $('#options-header-default');
        var optHeaderInverse = $('#options-header-inverse');
        var optHeaderTop = $('#options-header-top');
        var optHeaderBottom = $('#options-header-bottom');

        if (header.hasClass('navbar-default')) {
            optHeaderDefault.addClass('active');
        } else {
            optHeaderInverse.addClass('active');
        }

        if (header.hasClass('navbar-fixed-top')) {
            optHeaderTop.addClass('active');
        } else if (header.hasClass('navbar-fixed-bottom')) {
            optHeaderBottom.addClass('active');
        }

        optHeaderDefault.click(function () {
            header.removeClass('navbar-inverse').addClass('navbar-default');
            $(this).addClass('active');
            optHeaderInverse.removeClass('active');
        });

        optHeaderInverse.click(function () {
            header.removeClass('navbar-default').addClass('navbar-inverse');
            $(this).addClass('active');
            optHeaderDefault.removeClass('active');
        });

        optHeaderTop.click(function () {
            page.removeClass('header-fixed-bottom').addClass('header-fixed-top');
            header.removeClass('navbar-fixed-bottom').addClass('navbar-fixed-top');
            $(this).addClass('active');
            optHeaderBottom.removeClass('active');
            handleSidebar('sidebar-scroll');

            // Resize #page-content
            resizePageContent();
        });

        optHeaderBottom.click(function () {
            page.removeClass('header-fixed-top').addClass('header-fixed-bottom');
            header.removeClass('navbar-fixed-top').addClass('navbar-fixed-bottom');
            $(this).addClass('active');
            optHeaderTop.removeClass('active');
            handleSidebar('sidebar-scroll');

            // Resize #page-content
            resizePageContent();
        });

        /* Footer */
        var optFooterStatic = $('#options-footer-static');
        var optFooterFixed = $('#options-footer-fixed');

        if (page.hasClass('footer-fixed')) {
            optFooterFixed.addClass('active');
        } else {
            optFooterStatic.addClass('active');
        }

        optFooterStatic.click(function () {
            page.removeClass('footer-fixed');
            $(this).addClass('active');
            optFooterFixed.removeClass('active');

            // Resize #page-content
            resizePageContent();
        });

        optFooterFixed.click(function () {
            page.addClass('footer-fixed');
            $(this).addClass('active');
            optFooterStatic.removeClass('active');

            // Resize #page-content
            resizePageContent();
        });
    };

    var saveRecibeNotControlAmb= function (){
    	if(tokenControlAmbiental!=''){
			Utilities.showPreloader();
			//aqui lo genero, pero no lo mando a decsargar, si es que no le digo que lo descargue
			var ajax = UtilitiesService.saveRecibeNotControlAmb(tokenControlAmbiental);
			ajax.success(function (data) {
				if (data.success) {
					Utilities.alertModal(data.success, 'success', true);
					$('#li_saveRecibeNotControlAmb').remove();
				}
				else {
					if(data.error=='DEBE REINICIAR'){
						$('#li_saveRecibeNotControlAmb').remove();
					}else{
						Utilities.alertModal(data.error, 'error', true);
					}
				}
				Utilities.hiddePreloader();
			});
			ajax.error(function () {
				Utilities.hiddePreloader();
				Utilities.alertModal('ha ocurrido un error al generar el respaldo de la base de datos', 'error', true);

			})
		}else{
			Utilities.alertModal('Ha ocurrido un error con el token de verificación', 'error', true);
		}


	};

    var generarBackup = function (descargar) {
        $("#confirmupdateModal").modal('hide');

        Utilities.showPreloader();
        //aqui lo genero, pero no lo mando a decsargar, si es que no le digo que lo descargue
        var ajax = UtilitiesService.generarBackup();
        ajax.success(function (data) {

            if (data.success) {
                Utilities.alertModal(data.success, 'success', true);
                if (descargar == 1) {
                    descargarBackup();
                }
            } else {
                Utilities.alertModal(data.error, 'error', true);
            }

            Utilities.hiddePreloader();
        });
        ajax.error(function () {
            Utilities.hiddePreloader();
            Utilities.alertModal('ha ocurrido un error al generar el respaldo de la base de datos', 'error', true);

        })

    };

    var descargarBackup = function () {

        Utilities.showPreloader();
        document.location = baseurl + 'opciones/downloadbackup'
        Utilities.hiddePreloader();
    };

    var modalConfirmUpdate = function () {

        $("#confirmupdateModal").modal('show');
    };

    var modalConfirmUpdateRepository = function () {

        $("#confirmupdateRepositoryModal").modal('show');
    };
    var actualizarBd = function (refrescar = false) {
        /*hace migrate*/
        $("#confirmupdateModal").modal('hide');

        Utilities.showPreloader();
        var ajax = UtilitiesService.updateBd();
        ajax.success(function (data) {

            if (data.result == 'success') {
                Utilities.alertModal('Se ha actualizado la base de datos', 'success', true);
                if (refrescar == true) {
                    Utilities.alertModal('La pantalla será refrescada', 'success', true);
                    setTimeout(function () {
                        location.reload();
                    }, 3500)

                }

            } else {
                Utilities.alertModal(data.message, 'error', true);


            }

            Utilities.hiddePreloader();
        });
        ajax.error(function () {
            Utilities.hiddePreloader();
            Utilities.alertModal('Ha ocurrido un error al actualizar la base de datos', 'error', true);

        })


    };
    var confirmUpateRepository = function () {
        /*hace pull*/
        $("#confirmupdateRepositoryModal").modal('hide');

        Utilities.showPreloader();
        var ajax = UtilitiesService.updateRepo();
        ajax.success(function (data) {

            if (data.result == 'success') {
                Utilities.alertModal('Se ha actualizado el sistema', 'success', true);
                /*hag migrate de una vez*/
                /*y le digo que refresque*/
                actualizarBd(true);

            } else {
                Utilities.alertModal(data.message, 'error', true);


            }

            Utilities.hiddePreloader();
        });
        ajax.error(function () {
            Utilities.hiddePreloader();
            Utilities.alertModal('ha ocurrido un error al actualizar el sistema', 'error', true);

        })


    }
    /* Datatables basic Bootstrap integration (pagination integration included under the Datatables plugin in plugins.js) */
    var dtIntegration = function () {
        $.extend(true, $.fn.dataTable.defaults, {
            "sDom": "<'row'<'col-sm-6 col-xs-5'l><'col-sm-6 col-xs-7'f>r>t<'row'<'col-sm-5 hidden-xs'i><'col-sm-7 col-xs-12 clearfix'p>>",
            //"sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_",
                "sSearch": "<div class=\"input-group\">_INPUT_<span class=\"input-group-addon\"><i class=\"fa fa-search\"></i></span></div>",
                "sInfo": "<strong>_START_</strong>-<strong>_END_</strong> of <strong>_TOTAL_</strong>",
                "oPaginate": {
                    "sPrevious": "",
                    "sNext": ""
                }
            }
        });
        $.extend($.fn.dataTableExt.oStdClasses, {
            "sWrapper": "dataTables_wrapper form-inline",
            "sFilterInput": "form-control",
            "sLengthSelect": "form-control"
        });
    };


    var formSubmitAjax = function (action, callback, modal, form, boton) {

        very_session().success(function (sesion) {


            if (sesion == "false")	//if no errors
            {
                Utilities.hiddePreloader();
                alert('El tiempo de su sessión ha expirado');
                //location.href = base_url + 'inicio';
            } else {

                Utilities.showPreloader();
                console.log(action);
                $.ajax({
                    url: action,
                    type: 'POST',
                    dataType: 'json',
                    data: $("#" + form).serialize(),
                    success: function (data) {
                        $("#" + boton).removeClass('disabled');
                        if (data.error == undefined) {

                            var resultcal = callback();

                            if (data.success) {

                                Utilities.alertModal(data.success, 'success');

                            }
                            if (resultcal != undefined) {
                                resultcal.success(function (data2) {


                                    if (modal != null) {


                                        $('#' + modal).on('hidden.bs.modal', function () {
                                            $('#page-content').html(data2);

                                            $("#successspan").html(data.success);

                                            $("#success").css('display', 'block');
                                        });

                                        $("#" + modal).modal('hide');
                                        Utilities.hiddePreloader();

                                    } else {

                                        $('#page-content').html(data2);

                                        $("#successspan").html(data.success);

                                        $("#success").css('display', 'block');
                                        Utilities.hiddePreloader();

                                    }

                                })
                            } else {


                                $("#successspan").html(data.success);
                                $("#success").css('display', 'block');
                                if (modal != null) {
                                    $("#" + modal).modal('hide');
                                }

                                Utilities.hiddePreloader();
                            }
                        } else {


                            Utilities.alertModal(data.error, 'error');


                            /*$("#errorspan").text(data.error);
                             $("#error").css('display','block');*/
                            Utilities.hiddePreloader();

                        }

                        setTimeout(function () {
                            //$(".alert-danger").css('display','none');
                            $(".alert-success").css('display', 'none');
                        }, 3000)
                    },
                    error: function (response) {
                        $("#" + boton).removeClass('disabled');
                        Utilities.hiddePreloader();


                        Utilities.alertModal('Ha ocurrido un error al realizar la operacion', 'error');

                    }
                })
            }
        })
    }
    var restablecerBD = function () {
        Utilities.showPreloader();
        $.ajax({
            url: base_url + "opciones/restablecerBD",
            type: 'POST',
            dataType: 'json',
            cache: false,
            processData: false,
            success: function (response) {

                Utilities.hiddePreloader();
                if (response) {
                    Utilities.alertModal("La Base de datos se ha restablecido", 'success', false);
                    $("#modalconfirmrestablecer").modal('hide');
                } else {
                    Utilities.alertModal("No se ha podido restablecer la Base de datos", 'error', false);
                }

            },
            erro: function () {
                Utilities.alertModal("Ha ocurrido un error al restablecer la base de datos", 'error', false);
                Utilities.hiddePreloader();
            }
        });
    }

    var formSubmitWithFile = function (action, callback, modal, form, boton) {
        very_session().success(function (sesion) {


            if (sesion == "false")	//if no errors
            {
                alert('El tiempo de su sessión ha expirado');
                //location.href = base_url + 'inicio';
            } else {

                Utilities.showPreloader();

                var formData = new FormData($("#" + form)[0]);
                var contentType = false;


                $.ajax({
                    url: action,
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    cache: false,
                    contentType: contentType,
                    processData: false,
                    success: function (data) {
                        $("#" + boton).removeClass('disabled');
                        if (data.error == undefined) {

                            var resultcal = callback();

                            if (data.success) {

                                Utilities.alertModal(data.success, 'success');

                            }


                            if (data.savingProductoCompra != undefined) {

                                Compra.agregarNuevoProducto(data.producto_id);
                            }

                            if (resultcal != undefined) {
                                resultcal.success(function (data2) {


                                    if (modal != null) {


                                        $('#' + modal).on('hidden.bs.modal', function () {
                                            $('#page-content').html(data2);

                                            $("#successspan").html(data.success);

                                            $("#success").css('display', 'block');
                                        });

                                        $("#" + modal).modal('hide');
                                        Utilities.hiddePreloader();

                                    } else {


                                        $('#page-content').html(data2);

                                        $("#successspan").html(data.success);

                                        $("#success").css('display', 'block');
                                        Utilities.hiddePreloader();

                                    }


                                })
                            } else {

                                $("#successspan").html(data.success);
                                $("#success").css('display', 'block');
                                if (modal != null) {
                                    $("#" + modal).modal('hide');
                                }

                                Utilities.hiddePreloader();
                            }


                        } else {


                            Utilities.alertModal(data.error, 'error');
                            Utilities.hiddePreloader();

                        }

                        setTimeout(function () {
                            //$(".alert-danger").css('display','none');
                            $(".alert-success").css('display', 'none');
                        }, 3000)
                    },
                    error: function (response) {
                        $("#" + boton).removeClass('disabled');
                        Utilities.hiddePreloader();
                        Utilities.alertModal('Ha ocurrido un error al realizar la operacion', 'error');


                    }
                })
            }
        })
    }

    return {
        init: function (cajasabiertas, aperturocaja, caja_id, fullscreen) {

            uiInit(); // Initialize UI Code
            pageLoading(); // Initialize Page Loading
            events(cajasabiertas, aperturocaja, caja_id);
            checkSysExpDat();
            // checkSession();

            if (fullscreen == 'SI') {
                //$("#fullscreen").click();
            }
        },

        generarBackup: function (descargar = 1) {
            generarBackup(descargar);
        },
		saveRecibeNotControlAmb: function (){
			saveRecibeNotControlAmb();
		},

        descargarBackup: function () {
            descargarBackup();
        },

        tooGlefullScreen: function () {
            pantallaCompleta();
        },

        buscarActualizaciones: function () {
            modalConfirmUpdate();
        },
        buscarActualizacionesRepository: function () {
            modalConfirmUpdateRepository();
        },
        confirmUpate: function () {
            actualizarBd(false);
        },

        confirmUpateRepository: function () {
            confirmUpateRepository();
        },
        checkSession: function () {
            checkSession();
        },
        sidebar: function (mode, extra) {
            handleSidebar(mode, extra); // Handle sidebars - access functionality from everywhere
        },
        datatables: function () {
            // dtIntegration(); // Datatables Bootstrap integration
        },

        formSubmitAjax: function (action, callback, modal, form, boton) {

            formSubmitAjax(action, callback, modal, form, boton);
        },

        formSubmitWithFile: function (action, callback, modal, form, boton) {
            formSubmitWithFile(action, callback, modal, form, boton);
        },
        restablecerBD: function () {
            restablecerBD();
        }
    };
}
();
