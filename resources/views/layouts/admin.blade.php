<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Admin - {{empty($title) ?  'oCoder' : $title}}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

        <!-- Styles -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}


        <!-- CSS Files -->
        <link rel="shortcut icon" href="{{ asset('assets/favicon.ico') }}" />
        <link href="{!! asset('assets/css/bootstrap.min.css') !!}" media="all" rel="stylesheet" type="text/css" />
        <link href="{!! asset('assets/css/animate.css') !!}" media="all" rel="stylesheet" type="text/css" />
        <link href="{!! asset('assets/font-awesome/css/font-awesome.min.css') !!}" media="all" rel="stylesheet" type="text/css" />
        <link href="{!! asset('assets/css/style.css') !!}" media="all" rel="stylesheet" type="text/css" />

        <link href="{!! asset('assets/css/plugins/switchery/switchery.css')!!}" rel="stylesheet">
        <link href="{!! asset('assets/css/plugins/datapicker/datepicker3.css')!!}" rel="stylesheet">
        <link href="{!! asset('assets/css/plugins/daterangepicker/daterangepicker-bs3.css')!!}" rel="stylesheet">


    </head>
    <body id="app-layout">
        <div id="wrapper">

            <nav class="navbar-default navbar-static-side" role="navigation">
                <div class="sidebar-collapse">
                    <ul class="nav metismenu" id="side-menu">
                        <li class="nav-header">
                            <div class="dropdown profile-element">                            
                                <!-- Authentication Links -->
                                @if (Auth::guest())
                                <li><a href="{{ url('/login') }}">Login</a></li>
                                <li><a href="{{ url('/register') }}">Register</a></li>
                                @else
                                <img alt="image" class="img-circle" src="{!! asset('images/profile_small.jpg') !!}" />
                                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                    <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{Auth::user()->email}}</strong>
                                        </span></span>
                                   <!--<span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{ Auth::user()->username }}</strong>-->
                                    <span class="text-muted text-xs block">{{Auth::user()->username}} <b class="caret"></b></span> 
                                </a>
                                <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                    <li>
                                        <a href="{{ url('/admin/profile') }}"><i class="fa fa-envelope"></i> <span class="nav-label">Profile</span> </a>
                                    </li>
                                    <li><a href="{{ url('/logout') }}">Logout</a></li>
                                </ul>

                                @endif
                                <div class="logo-element">
                                    oCoder
                                </div>
                            </div>

                        </li>
                        @include('layouts.menu.custom-menu', array('MyNavBar' => Menu::get('MyNavBar')))
                        <!--@include(config('laravel-menu.views.bootstrap-items'), array('items' => $MyNavBar->roots()))-->

                    </ul>
                </div>
            </nav>

            <div id="page-wrapper" class="gray-bg">
                <div class="row border-bottom">
                    <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                        <div class="navbar-header">
                            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                            @yield('search_form')
                        </div>
                        <ul class="nav navbar-top-links navbar-right">
                            <li>
                                <span class="m-r-sm text-muted welcome-message">Welcome to INSPINIA+ Admin Theme.</span>
                            </li>
                            <li class="dropdown">
                                <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                                    <i class="fa fa-envelope"></i>  <span class="label label-warning">16</span>
                                </a>
                                <ul class="dropdown-menu dropdown-messages">
                                    <li>
                                        <div class="dropdown-messages-box">
                                            <a href="profile.html" class="pull-left">
                                                <img alt="image" class="img-circle" src="img/a7.jpg">
                                            </a>
                                            <div class="media-body">
                                                <small class="pull-right">46h ago</small>
                                                <strong>Mike Loreipsum</strong> started following <strong>Monica Smith</strong>. <br>
                                                <small class="text-muted">3 days ago at 7:58 pm - 10.06.2014</small>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <div class="dropdown-messages-box">
                                            <a href="profile.html" class="pull-left">
                                                <img alt="image" class="img-circle" src="img/a4.jpg">
                                            </a>
                                            <div class="media-body ">
                                                <small class="pull-right text-navy">5h ago</small>
                                                <strong>Chris Johnatan Overtunk</strong> started following <strong>Monica Smith</strong>. <br>
                                                <small class="text-muted">Yesterday 1:21 pm - 11.06.2014</small>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <div class="dropdown-messages-box">
                                            <a href="profile.html" class="pull-left">
                                                <img alt="image" class="img-circle" src="img/profile.jpg">
                                            </a>
                                            <div class="media-body ">
                                                <small class="pull-right">23h ago</small>
                                                <strong>Monica Smith</strong> love <strong>Kim Smith</strong>. <br>
                                                <small class="text-muted">2 days ago at 2:30 am - 11.06.2014</small>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <div class="text-center link-block">
                                            <a href="mailbox.html">
                                                <i class="fa fa-envelope"></i> <strong>Read All Messages</strong>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                                    <i class="fa fa-bell"></i>  <span class="label label-primary">8</span>
                                </a>
                                <ul class="dropdown-menu dropdown-alerts">
                                    <li>
                                        <a href="mailbox.html">
                                            <div>
                                                <i class="fa fa-envelope fa-fw"></i> You have 16 messages
                                                <span class="pull-right text-muted small">4 minutes ago</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="profile.html">
                                            <div>
                                                <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                                <span class="pull-right text-muted small">12 minutes ago</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="grid_options.html">
                                            <div>
                                                <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                                <span class="pull-right text-muted small">4 minutes ago</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <div class="text-center link-block">
                                            <a href="notifications.html">
                                                <strong>See All Alerts</strong>
                                                <i class="fa fa-angle-right"></i>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </li>


                            <li>
                                <a href="{{ url('/logout') }}"><i class="fa fa-sign-out"></i>  Logout</a>

                            </li>
                        </ul>

                    </nav>
                </div>

                @if (Session::has('success'))
                <br>
                <div class="alert alert-success alert-dismissable animated fadeInDown">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    {{ Session::get('success') }}
                </div>

                @elseif (Session::has('error'))
                <br>

                <div class="alert alert-danger  alert-dismissable animated fadeInDown">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    {{ Session::get('error') }}
                </div>
                @elseif (count($errors) > 0)
                <div class="alert alert-danger  alert-dismissable animated fadeInDown">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>

                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @yield('content')

                <div class="footer">
                    <div class="pull-right">
                        10GB of <strong>250GB</strong> Free.
                    </div>
                    <div>
                        <strong>Copyright</strong> oCoder &copy; 2014-2015
                    </div>
                </div>
            </div>
        </div>
        <!-- Mainly scripts -->
        <script type="text/javascript" src="{!! asset('assets/js/jquery-2.1.1.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('assets/js/bootstrap.min.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('assets/js/plugins/metisMenu/jquery.metisMenu.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('assets/js/plugins/slimscroll/jquery.slimscroll.min.js') !!}"></script>



        <!-- Custom and plugin javascript -->
        <script type="text/javascript" src="{!! asset('assets/js/inspinia.js') !!}"></script>
        <script type="text/javascript" src="{!! asset('assets/js/plugins/pace/pace.min.js') !!}"></script>
        <!-- Switchery -->
        <script src="{!! asset('assets/js/plugins/switchery/switchery.js') !!}"></script>
        <script src="{!! asset('assets/js/plugins/datapicker/bootstrap-datepicker.js') !!}"></script>
        <script src="{!! asset('assets/js/plugins/daterangepicker/daterangepicker.js') !!}"></script>
        <script src="{!! asset('assets/js/admin.js') !!}"></script>

        {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
    @yield('content_js')

</body>
</html>
