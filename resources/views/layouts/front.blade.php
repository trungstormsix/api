<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        @if(@$refresh)
        <meta http-equiv="refresh" content="{{@$refresh}}"/>
        @endif
        <title>{{@$coin ? $coin."-BTC" : "oCoder" }}</title>

        <!-- Bootstrap core CSS -->
        <link href="{!! asset('assets/css/bootstrap.min.css') !!}" media="all" rel="stylesheet" type="text/css" />

        <!-- Animation CSS -->
        <link href="{!! asset('assets/css/animate.css') !!}" media="all" rel="stylesheet" type="text/css" />
        <link href="{!! asset('assets/font-awesome/css/font-awesome.min.css') !!}" media="all" rel="stylesheet" type="text/css" />
        <!-- Custom styles for this template -->
        <link href="{!! asset('assets/css/style.css') !!}" media="all" rel="stylesheet" type="text/css" />
        <link href="{!! asset('assets/css/plugins/iCheck/custom.css') !!}" media="all" rel="stylesheet" type="text/css" />


    </head>
    <body id="page-top" class="landing-page">
        <div class="navbar-wrapper">
            <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
                <div class="container">
                    <div class="navbar-header page-scroll">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="{{url('/')}}">OCoder Education</a>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a class="page-scroll" href="{!! URL::route('trade.total') !!}">Home</a></li>
                            <li><a class="page-scroll" href="{!! URL::route('trade.coins') !!}">Kèo Hồi</a></li>
<!--                            <li><a class="page-scroll" href="#team">Team</a></li>
                            <li><a class="page-scroll" href="#testimonials">Testimonials</a></li>
                            <li><a class="page-scroll" href="#pricing">Pricing</a></li>-->
                            <li><a class="page-scroll" href="#contact">Contact</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <div id="inSlider" class="carousel carousel-fade" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#inSlider" data-slide-to="0" class="active"></li>
                <li data-target="#inSlider" data-slide-to="1"></li>
            </ol>
            <div class="carousel-inner" role="listbox">
                <div class="item active">
                    <div class="container">
                        <div class="carousel-caption">
                            <h1>We craft<br/>
                                brands, web apps,<br/>
                                and user interfaces<br/>
                                we are IN+ studio</h1>
                            <p>Lorem Ipsum is simply dummy text of the printing.</p>
                            <p>
                                <a class="btn btn-lg btn-primary" href="#" role="button">READ MORE</a>
                                <a class="caption-link" href="#" role="button">Inspinia Theme</a>
                            </p>
                        </div>
                        <div class="carousel-image wow zoomIn">
                            <img src="img/landing/laptop.png" alt="laptop"/>
                        </div>
                    </div>
                    <!-- Set background for slide in css -->
                    <div class="header-back one"></div>

                </div>
                <div class="item">
                    <div class="container">
                        <div class="carousel-caption blank">
                            <h1>We create meaningful <br/> interfaces that inspire.</h1>
                            <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam.</p>
                            <p><a class="btn btn-lg btn-primary" href="#" role="button">Learn more</a></p>
                        </div>
                    </div>
                    <!-- Set background for slide in css -->
                    <div class="header-back two"></div>
                </div>
            </div>
            <a class="left carousel-control" href="#inSlider" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#inSlider" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>



        <section class="timeline container">
            <div class="navy-line"></div>
            @yield('content')

        </section>

        <section id="contact" class="gray-section contact">
            <div class="container">
                <div class="row m-b-lg">
                    <div class="col-lg-12 text-center">
                        <div class="navy-line"></div>
                        <h1>Contact Us</h1>
                        <p>Donec sed odio dui. Etiam porta sem malesuada magna mollis euismod.</p>
                    </div>
                </div>
                <div class="row m-b-lg">
                    <div class="col-lg-3 col-lg-offset-3">
                        <address>
                            <strong><span class="navy">Company name, Inc.</span></strong><br/>
                            795 Folsom Ave, Suite 600<br/>
                            San Francisco, CA 94107<br/>
                            <abbr title="Phone">P:</abbr> (123) 456-7890
                        </address>
                    </div>
                    <div class="col-lg-4">
                        <p class="text-color">
                            Consectetur adipisicing elit. Aut eaque, totam corporis laboriosam veritatis quis ad perspiciatis, totam corporis laboriosam veritatis, consectetur adipisicing elit quos non quis ad perspiciatis, totam corporis ea,
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <a href="mailto:test@email.com" class="btn btn-primary">Send us mail</a>
                        <p class="m-t-sm">
                            Or follow us on social platform
                        </p>
                        <ul class="list-inline social-icon">
                            <li><a href="#"><i class="fa fa-twitter"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-facebook"></i></a>
                            </li>
                            <li><a href="#"><i class="fa fa-linkedin"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 text-center m-t-lg m-b-lg">
                        <p><strong>&copy; 2015 Company Name</strong><br/> consectetur adipisicing elit. Aut eaque, laboriosam veritatis, quos non quis ad perspiciatis, totam corporis ea, alias ut unde.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Mainly scripts -->
        <script type="text/javascript" src="{!! asset('assets/js/jquery-2.1.1.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('assets/js/bootstrap.min.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('assets/js/plugins/metisMenu/jquery.metisMenu.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('assets/js/plugins/slimscroll/jquery.slimscroll.min.js') !!}"></script>

        <!-- Custom and plugin javascript -->
        <!-- Custom and plugin javascript -->
        <script type="text/javascript" src="{!! asset('assets/js/inspinia.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('assets/js/plugins/pace/pace.min.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('assets/js/plugins/wow/wow.min.js') !!}"></script>


        <script>
            $(document).ready(function() {

                $('body').scrollspy({
                    target: '.navbar-fixed-top',
                    offset: 80
                });
                // Page scrolling feature
                $('a.page-scroll').bind('click', function(event) {
                    var link = $(this);
                    $('html, body').stop().animate({
                        scrollTop: $(link.attr('href')).offset().top - 50
                    }, 500);
                    event.preventDefault();
                    $("#navbar").collapse('hide');
                });
            });
            var cbpAnimatedHeader = (function() {
                var docElem = document.documentElement,
                    header = document.querySelector('.navbar-default'),
                    didScroll = false,
                    changeHeaderOn = 200;

                function init() {
                    window.addEventListener('scroll', function(event) {
                        if (!didScroll) {
                            didScroll = true;
                            setTimeout(scrollPage, 250);
                        }
                    }, false);
                }

                function scrollPage() {
                    var sy = scrollY();
                    if (sy >= changeHeaderOn) {
                        $(header).addClass('navbar-scroll')
                    } else {
                        $(header).removeClass('navbar-scroll')
                    }
                    didScroll = false;
                }

                function scrollY() {
                    return window.pageYOffset || docElem.scrollTop;
                }
                init();
            })();
            // Activate WOW.js plugin for animation on scrol
            new WOW().init();
        </script>
        @yield('content_script')
    </body>
</html>
