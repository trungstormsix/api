<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>oCoder Education</title>

        <!-- Bootstrap core CSS -->
        <link href="{!! asset('assets/css/bootstrap.min.css') !!}" media="all" rel="stylesheet" type="text/css" />

        <!-- Animation CSS -->
        <link href="{!! asset('assets/css/animate.css') !!}" media="all" rel="stylesheet" type="text/css" />
        <link href="{!! asset('assets/font-awesome/css/font-awesome.min.css') !!}" media="all" rel="stylesheet" type="text/css" />
        <!-- Custom styles for this template -->
        <link href="{!! asset('assets/css/style.css') !!}" media="all" rel="stylesheet" type="text/css" />
        <link href="{!! asset('assets/css/plugins/iCheck/custom.css') !!}" media="all" rel="stylesheet" type="text/css" />

    <style type="text/css">
        .landing-page .navbar-default .nav li a {
            color: #676a6c;
        }
        .landing-page .navy-line {
            margin: 150px auto 0;
            opacity: 0;
        }
        .landing-page .navbar-default {
            background: #fff;
        }
        h1 {
            margin-bottom: 50px;
        }
        .ibox-content {
            border-top: 0;
        }
        body.landing-page {
            background: #dadada;
        }
        .landing-page section p {
            color: #000;
        }
    </style>

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
                            <li><a class="page-scroll" href="{{url('/')}}">Home</a></li>
                            <li><a class="page-scroll" href="{{url('/listening')}}">Listening</a></li>
                            @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                        <li>
                            <a href="{{ url('admin/user/profile') }}"> {{ Auth::user()->username }} </a></li>
                        <li><a href="{{ url('/logout') }}">Logout</a></li>
                    @endif
                        </ul>
                    </div>
                </div>
            </nav>
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
        <script type="text/javascript" src="{!! asset('assets/js/plugins/iCheck/icheck.min.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('assets/js/front.js') !!}"></script>


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
        <!-- iCheck -->
        
            <script>
                $(document).ready(function () {
                    $('.i-checks').iCheck({
                        checkboxClass: 'icheckbox_square-green',
                        radioClass: 'iradio_square-green',
                    });
                });
            </script>

        @yield('content_script')
    </body>
</html>
