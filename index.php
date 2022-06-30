<?php
declare (strict_types=1);

use  App\EvolvClient\EvolvClient;

require_once  __DIR__ . '/App/EvolvClient.php';

require  'vendor/autoload.php';
?>
<html lang="en" class="no-js">
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title>Test</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>

    <!-- GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Hind:300,400,500,600,700" rel="stylesheet" type="text/css">
    <link href="src/vendor/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
    <link href="src/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

    <!-- PAGE LEVEL PLUGIN STYLES -->
    <link href="src/css/animate.css" rel="stylesheet">
    <link href="src/vendor/swiper/css/swiper.min.css" rel="stylesheet" type="text/css"/>

    <!-- THEME STYLES -->
    <link href="src/css/layout.css" rel="stylesheet" type="text/css"/>

    <!-- Favicon -->
    <link rel="shortcut icon" href="favicon.ico"/>
</head>
<!-- END HEAD -->
<!-- BODY -->
<body id="body" data-spy="scroll" data-target=".header">

<!--========== HEADER ==========-->
<header class="header navbar-fixed-top">
    <!-- Navbar -->
    <nav class="navbar" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="menu-container js_nav-item">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="toggle-icon"></span>
                </button>

                <!-- Logo -->
                <div class="logo">
                    <a class="logo-wrap" href="#body">
                        <img class="logo-img logo-img-main" src="src/img/logo.png" alt="Asentus Logo">
                        <img class="logo-img logo-img-active" src="src/img/logo-dark.png" alt="Asentus Logo">
                    </a>
                </div>
                <!-- End Logo -->
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse nav-collapse">
                <div class="menu-container">
                    <ul class="nav navbar-nav navbar-nav-right">
                        <li class="js_nav-item nav-item"><a class="nav-item-child nav-item-hover" href="#body">Home</a></li>
                        <li class="js_nav-item nav-item"><a class="nav-item-child nav-item-hover" href="#about">About</a></li>
                        <li class="js_nav-item nav-item"><a class="nav-item-child nav-item-hover" href="#products">Products</a></li>
                        <li class="js_nav-item nav-item"><a class="nav-item-child nav-item-hover" href="#pricing">Pricing</a></li>
                        <li class="js_nav-item nav-item"><a class="nav-item-child nav-item-hover" href="#work">Work</a></li>
                        <li class="js_nav-item nav-item"><a class="nav-item-child nav-item-hover" href="#service">Service</a></li>
                        <li class="js_nav-item nav-item"><a class="nav-item-child nav-item-hover" href="#contact">Contact</a></li>
                    </ul>
                </div>
            </div>
            <!-- End Navbar Collapse -->
        </div>
    </nav>
    <!-- Navbar -->
</header>
<!--========== END HEADER ==========-->

<!--========== SLIDER ==========-->
<div>
    <!-- Wrapper for slides -->
    <div class="carousel-innersdsd" role="listbox">
        <div class="item active">
            <img class="img-responsive" src="src/img/1920x1080/01.jpg" alt="Slider Image">
            <div class="container">
                <div class="carousel-centered">
                    <div class="margin-b-40">
                        <h1 class="carousel-title">Hello, world!</h1>
                        <p class="color-white">Lorem ipsum dolor amet consectetur adipiscing dolore magna aliqua <br/> enim minim estudiat veniam siad venumus dolore</p>
                    </div>
                    <a href="#" class="btn-theme btn-theme-sm btn-white-brd text-uppercase">Explore</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!--========== SLIDER ==========-->

<!--========== PAGE LAYOUT ==========-->
<!-- About -->
<div id="about">
    <div class="bg-color-sky-light">
        <div class="content-lg container">
            <div class="row">
                <div class="col-md-12 col-sm-12 md-margin-b-60">
                    <div class="margin-t-50 margin-b-30">
                        <?php

                        $environment = '758012fca1';
                        $uid = 'user_id';
                        $endpoint = 'https://participants.evolv.ai/v1';

                        $client = new EvolvClient($environment,$uid,$endpoint);

                        $client->initialize($environment, $uid, $endpoint, $remoteContext = [], $localContext = []);
                        $client->set("native.newUser",true,true);
                       // $client->set("native.pageCategory",'home',true);
                       // $client->set("native.pageCategory",'pdp',true);
                        $client->set("extra_key",'',true);
                        //all active

                        $key = $client->getActiveKeys();

                        $client->print_r($key);

                        $client->print_r($client->localContext());

                        //$client->print_r($client->remoteContext());

                        ?>
                    </div>
                </div>
                <div class="col-md-5 col-sm-5 md-margin-b-60">
                    <div class="margin-t-50 margin-b-30">
                        <h2>Why Choose Us?</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    </div>
                    <a href="#" class="btn-theme btn-theme-sm btn-white-bg text-uppercase">Explore</a>
                </div>
                <div class="col-md-5 col-sm-7 col-md-offset-2">
                    <!-- Accordion -->
                    <div class="accordion">
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a class="panel-title-child" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Exceptional Frontend Framework
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                    <div class="panel-body">
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch.
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingTwo">
                                    <h4 class="panel-title">
                                        <a class="collapsed panel-title-child" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Modern Design Trends
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                    <div class="panel-body">
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch.
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingThree">
                                    <h4 class="panel-title">
                                        <a class="collapsed panel-title-child" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            Beatifully Crafted Code
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                    <div class="panel-body">
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Accodrion -->
                </div>
            </div>
            <!--// end row -->
        </div>
    </div>
</div>
<!-- End About -->

<!-- Latest Products -->
<div id="products">
    <div class="content-lg container">
        <div class="row margin-b-40">
            <div class="col-sm-6">
                <h2>Latest Products</h2>
                <p>Lorem ipsum dolor sit amet consectetur adipiscing elit sed tempor incididunt ut laboret dolore magna aliqua enim minim veniam exercitation</p>
            </div>
        </div>
        <!--// end row -->

        <div class="row">
            <!-- Latest Products -->
            <div class="col-sm-4 sm-margin-b-50">
                <div class="margin-b-20">
                    <img class="img-responsive" src="src/img/970x647/01.jpg" alt="Latest Products Image">
                </div>
                <h4><a href="#">Workspace</a> <span class="text-uppercase margin-l-20">Management</span></h4>
                <p>Lorem ipsum dolor sit amet consectetur adipiscing elit sed tempor incdidunt ut laboret dolor magna ut consequat siad esqudiat dolor</p>
                <a class="link" href="#">Read More</a>
            </div>
            <!-- End Latest Products -->

            <!-- Latest Products -->
            <div class="col-sm-4 sm-margin-b-50">
                <div class="margin-b-20">
                    <img class="img-responsive" src="src/img/970x647/02.jpg" alt="Latest Products Image">
                </div>
                <h4><a href="#">Minimalism</a> <span class="text-uppercase margin-l-20">Developmeny</span></h4>
                <p>Lorem ipsum dolor sit amet consectetur adipiscing elit sed tempor incdidunt ut laboret dolor magna ut consequat siad esqudiat dolor</p>
                <a class="link" href="#">Read More</a>
            </div>
            <!-- End Latest Products -->

            <!-- Latest Products -->
            <div class="col-sm-4 sm-margin-b-50">
                <div class="margin-b-20">
                    <img class="img-responsive" src="src/img/970x647/03.jpg" alt="Latest Products Image">
                </div>
                <h4><a href="#">Cleant Style</a> <span class="text-uppercase margin-l-20">Design</span></h4>
                <p>Lorem ipsum dolor sit amet consectetur adipiscing elit sed tempor incdidunt ut laboret dolor magna ut consequat siad esqudiat dolor</p>
                <a class="link" href="#">Read More</a>
            </div>
            <!-- End Latest Products -->
        </div>
        <!--// end row -->
    </div>
</div>
<!-- End Latest Products -->

<!-- Pricing -->
<div id="pricing">
    <div class="bg-color-sky-light">
        <div class="content-lg container">
            <div class="row row-space-1">
                <div class="col-sm-4 sm-margin-b-2">
                    <!-- Pricing -->
                    <div class="pricing">
                        <div class="margin-b-30">
                            <i class="pricing-icon icon-chemistry"></i>
                            <h3>Lorem  <span> - $</span> 49</h3>
                            <p>Lorem ipsum dolor amet consectetur ut consequat siad esqudiat dolor</p>
                        </div>
                        <ul class="list-unstyled pricing-list margin-b-50">
                            <li class="pricing-list-item">Basic Features</li>
                            <li class="pricing-list-item">Up to 5 products</li>
                            <li class="pricing-list-item">50 Users Panels</li>
                        </ul>
                        <a href="pricing.html" class="btn-theme btn-theme-sm btn-default-bg text-uppercase">Choose</a>
                    </div>
                    <!-- End Pricing -->
                </div>
                <div class="col-sm-4 sm-margin-b-2">
                    <!-- Pricing -->
                    <div class="pricing pricing-active">
                        <div class="margin-b-30">
                            <i class="pricing-icon icon-badge"></i>
                            <h3>Professional <span> - $</span> 149</h3>
                            <p>Lorem ipsum dolor amet consectetur ut consequat siad esqudiat dolor</p>
                        </div>
                        <ul class="list-unstyled pricing-list margin-b-50">
                            <li class="pricing-list-item">Basic Features</li>
                            <li class="pricing-list-item">Up to 100 products</li>
                            <li class="pricing-list-item">100 Users Panels</li>
                        </ul>
                        <a href="pricing.html" class="btn-theme btn-theme-sm btn-default-bg text-uppercase">Choose</a>
                    </div>
                    <!-- End Pricing -->
                </div>
                <div class="col-sm-4">
                    <!-- Pricing -->
                    <div class="pricing">
                        <div class="margin-b-30">
                            <i class="pricing-icon icon-shield"></i>
                            <h3>Advanced <span> - $</span> 249</h3>
                            <p>Lorem ipsum dolor amet consectetur ut consequat siad esqudiat dolor</p>
                        </div>
                        <ul class="list-unstyled pricing-list margin-b-50">
                            <li class="pricing-list-item">Extended Features</li>
                            <li class="pricing-list-item">Unlimited products</li>
                            <li class="pricing-list-item">Unlimited Users Panels</li>
                        </ul>
                        <a href="pricing.html" class="btn-theme btn-theme-sm btn-default-bg text-uppercase">Choose</a>
                    </div>
                    <!-- End Pricing -->
                </div>
            </div>
            <!--// end row -->
        </div>
    </div>
</div>
<!-- End Pricing -->

<!-- Work -->
<div id="work">
    <div class="section-seperator">
        <div class="content-md container">
            <div class="row margin-b-40">
                <div class="col-sm-6">
                    <h2>Work</h2>
                    <p>Lorem ipsum dolor sit amet consectetur adipiscing elit sed tempor incididunt ut laboret dolore magna aliqua enim minim veniam exercitation</p>
                </div>
            </div>
            <!--// end row -->

            <!-- Masonry Grid -->
            <div class="masonry-grid row">
                <div class="masonry-grid-sizer col-xs-6 col-sm-6 col-md-1"></div>
                <div class="masonry-grid-item col-xs-12 col-sm-6 col-md-8 margin-b-30">
                    <!-- Work -->
                    <div class="work work-popup-trigger">
                        <div class="work-overlay">
                            <img class="full-width img-responsive" src="src/img/800x400/01.jpg" alt="Portfolio Image">
                        </div>
                        <div class="work-popup-overlay">
                            <div class="work-popup-content">
                                <a href="javascript:void(0);" class="work-popup-close">Hide</a>
                                <div class="margin-b-30">
                                    <h3 class="margin-b-5">Art Of Coding</h3>
                                    <span>Clean &amp; Minimalistic Design</span>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8 work-popup-content-divider sm-margin-b-20">
                                        <div class="margin-t-10 sm-margin-t-0">
                                            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.</p>
                                            <ul class="list-inline work-popup-tag">
                                                <li class="work-popup-tag-item"><a class="work-popup-tag-link" href="#">Design,</a></li>
                                                <li class="work-popup-tag-item"><a class="work-popup-tag-link" href="#">Coding,</a></li>
                                                <li class="work-popup-tag-item"><a class="work-popup-tag-link" href="#">Portfolio</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="margin-t-10 sm-margin-t-0">
                                            <p class="margin-b-5"><strong>Project Leader:</strong> John Doe</p>
                                            <p class="margin-b-5"><strong>Designer:</strong> Alisa Keys</p>
                                            <p class="margin-b-5"><strong>Developer:</strong> Mark Doe</p>
                                            <p class="margin-b-5"><strong>Customer:</strong> Keenthemes</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Work -->
                </div>
                <div class="masonry-grid-item col-xs-12 col-sm-6 col-md-4 margin-b-30">
                    <!-- Work -->
                    <div class="work work-popup-trigger">
                        <div class="work-overlay">
                            <img class="full-width img-responsive" src="src/img/397x415/01.jpg" alt="Portfolio Image">
                        </div>
                        <div class="work-popup-overlay">
                            <div class="work-popup-content">
                                <a href="javascript:void(0);" class="work-popup-close">Hide</a>
                                <div class="margin-b-30">
                                    <h3 class="margin-b-5">Art Of Coding</h3>
                                    <span>Clean &amp; Minimalistic Design</span>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8 work-popup-content-divider sm-margin-b-20">
                                        <div class="margin-t-10 sm-margin-t-0">
                                            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.</p>
                                            <ul class="list-inline work-popup-tag">
                                                <li class="work-popup-tag-item"><a class="work-popup-tag-link" href="#">Design,</a></li>
                                                <li class="work-popup-tag-item"><a class="work-popup-tag-link" href="#">Coding,</a></li>
                                                <li class="work-popup-tag-item"><a class="work-popup-tag-link" href="#">Portfolio</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="margin-t-10 sm-margin-t-0">
                                            <p class="margin-b-5"><strong>Project Leader:</strong> John Doe</p>
                                            <p class="margin-b-5"><strong>Designer:</strong> Alisa Keys</p>
                                            <p class="margin-b-5"><strong>Developer:</strong> Mark Doe</p>
                                            <p class="margin-b-5"><strong>Customer:</strong> Keenthemes</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Work -->
                </div>
                <div class="masonry-grid-item col-xs-12 col-sm-6 col-md-4 md-margin-b-30">
                    <!-- Work -->
                    <div class="work work-popup-trigger">
                        <div class="work-overlay">
                            <img class="full-width img-responsive" src="src/img/397x300/01.jpg" alt="Portfolio Image">
                        </div>
                        <div class="work-popup-overlay">
                            <div class="work-popup-content">
                                <a href="javascript:void(0);" class="work-popup-close">Hide</a>
                                <div class="margin-b-30">
                                    <h3 class="margin-b-5">Art Of Coding</h3>
                                    <span>Clean &amp; Minimalistic Design</span>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8 work-popup-content-divider sm-margin-b-20">
                                        <div class="margin-t-10 sm-margin-t-0">
                                            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.</p>
                                            <ul class="list-inline work-popup-tag">
                                                <li class="work-popup-tag-item"><a class="work-popup-tag-link" href="#">Design,</a></li>
                                                <li class="work-popup-tag-item"><a class="work-popup-tag-link" href="#">Coding,</a></li>
                                                <li class="work-popup-tag-item"><a class="work-popup-tag-link" href="#">Portfolio</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="margin-t-10 sm-margin-t-0">
                                            <p class="margin-b-5"><strong>Project Leader:</strong> John Doe</p>
                                            <p class="margin-b-5"><strong>Designer:</strong> Alisa Keys</p>
                                            <p class="margin-b-5"><strong>Developer:</strong> Mark Doe</p>
                                            <p class="margin-b-5"><strong>Customer:</strong> Keenthemes</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Work -->
                </div>
                <div class="masonry-grid-item col-xs-12 col-sm-6 col-md-4 md-margin-b-30">
                    <!-- Work -->
                    <div class="work work-popup-trigger">
                        <div class="work-overlay">
                            <img class="full-width img-responsive" src="src/img/397x300/02.jpg" alt="Portfolio Image">
                        </div>
                        <div class="work-popup-overlay">
                            <div class="work-popup-content">
                                <a href="javascript:void(0);" class="work-popup-close">Hide</a>
                                <div class="margin-b-30">
                                    <h3 class="margin-b-5">Art Of Coding</h3>
                                    <span>Clean &amp; Minimalistic Design</span>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8 work-popup-content-divider sm-margin-b-20">
                                        <div class="margin-t-10 sm-margin-t-0">
                                            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.</p>
                                            <ul class="list-inline work-popup-tag">
                                                <li class="work-popup-tag-item"><a class="work-popup-tag-link" href="#">Design,</a></li>
                                                <li class="work-popup-tag-item"><a class="work-popup-tag-link" href="#">Coding,</a></li>
                                                <li class="work-popup-tag-item"><a class="work-popup-tag-link" href="#">Portfolio</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="margin-t-10 sm-margin-t-0">
                                            <p class="margin-b-5"><strong>Project Leader:</strong> John Doe</p>
                                            <p class="margin-b-5"><strong>Designer:</strong> Alisa Keys</p>
                                            <p class="margin-b-5"><strong>Developer:</strong> Mark Doe</p>
                                            <p class="margin-b-5"><strong>Customer:</strong> Keenthemes</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Work -->
                </div>
                <div class="masonry-grid-item col-xs-12 col-sm-6 col-md-4">
                    <!-- Work -->
                    <div class="work work-popup-trigger">
                        <div class="work-overlay">
                            <img class="full-width img-responsive" src="src/img/397x300/03.jpg" alt="Portfolio Image">
                        </div>
                        <div class="work-popup-overlay">
                            <div class="work-popup-content">
                                <a href="javascript:void(0);" class="work-popup-close">Hide</a>
                                <div class="margin-b-30">
                                    <h3 class="margin-b-5">Art Of Coding</h3>
                                    <span>Clean &amp; Minimalistic Design</span>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8 work-popup-content-divider sm-margin-b-20">
                                        <div class="margin-t-10 sm-margin-t-0">
                                            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.</p>
                                            <ul class="list-inline work-popup-tag">
                                                <li class="work-popup-tag-item"><a class="work-popup-tag-link" href="#">Design,</a></li>
                                                <li class="work-popup-tag-item"><a class="work-popup-tag-link" href="#">Coding,</a></li>
                                                <li class="work-popup-tag-item"><a class="work-popup-tag-link" href="#">Portfolio</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="margin-t-10 sm-margin-t-0">
                                            <p class="margin-b-5"><strong>Project Leader:</strong> John Doe</p>
                                            <p class="margin-b-5"><strong>Designer:</strong> Alisa Keys</p>
                                            <p class="margin-b-5"><strong>Developer:</strong> Mark Doe</p>
                                            <p class="margin-b-5"><strong>Customer:</strong> Keenthemes</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Work -->
                </div>
            </div>
            <!-- End Masonry Grid -->
        </div>
    </div>

</div>
<!-- End Work -->

<!-- Service -->
<div id="service">
    <div class="bg-color-sky-light" data-auto-height="true">
        <div class="content-lg container">
            <div class="row margin-b-40">
                <div class="col-sm-6">
                    <h2>Services</h2>
                    <p>Lorem ipsum dolor sit amet consectetur adipiscing elit sed tempor incididunt ut laboret dolore magna aliqua enim minim veniam exercitation</p>
                </div>
            </div>
            <!--// end row -->

            <div class="row row-space-1 margin-b-2">
                <div class="col-sm-4 sm-margin-b-2">
                    <div class="service" data-height="height">
                        <div class="service-element">
                            <i class="service-icon icon-chemistry"></i>
                        </div>
                        <div class="service-info">
                            <h3>Art Of Coding</h3>
                            <p class="margin-b-5">Lorem ipsum dolor amet consectetur ut consequat siad esqudiat dolor</p>
                        </div>
                        <a href="#" class="content-wrapper-link"></a>
                    </div>
                </div>
                <div class="col-sm-4 sm-margin-b-2">
                    <div class="service bg-color-base" data-height="height">
                        <div class="service-element">
                            <i class="service-icon color-white icon-screen-tablet"></i>
                        </div>
                        <div class="service-info">
                            <h3 class="color-white">Responsive Design</h3>
                            <p class="color-white margin-b-5">Lorem ipsum dolor amet consectetur ut consequat siad esqudiat dolor</p>
                        </div>
                        <a href="#" class="content-wrapper-link"></a>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="service" data-height="height">
                        <div class="service-element">
                            <i class="service-icon icon-badge"></i>
                        </div>
                        <div class="service-info">
                            <h3>Feature Reach</h3>
                            <p class="margin-b-5">Lorem ipsum dolor amet consectetur ut consequat siad esqudiat dolor</p>
                        </div>
                        <a href="#" class="content-wrapper-link"></a>
                    </div>
                </div>
            </div>
            <!--// end row -->

            <div class="row row-space-1">
                <div class="col-sm-4 sm-margin-b-2">
                    <div class="service" data-height="height">
                        <div class="service-element">
                            <i class="service-icon icon-notebook"></i>
                        </div>
                        <div class="service-info">
                            <h3>Useful Documentation</h3>
                            <p class="margin-b-5">Lorem ipsum dolor amet consectetur ut consequat siad esqudiat dolor</p>
                        </div>
                        <a href="#" class="content-wrapper-link"></a>
                    </div>
                </div>
                <div class="col-sm-4 sm-margin-b-2">
                    <div class="service" data-height="height">
                        <div class="service-element">
                            <i class="service-icon icon-speedometer"></i>
                        </div>
                        <div class="service-info">
                            <h3>Fast Delivery</h3>
                            <p class="margin-b-5">Lorem ipsum dolor amet consectetur ut consequat siad esqudiat dolor</p>
                        </div>
                        <a href="#" class="content-wrapper-link"></a>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="service" data-height="height">
                        <div class="service-element">
                            <i class="service-icon icon-badge"></i>
                        </div>
                        <div class="service-info">
                            <h3>Free Plugins</h3>
                            <p class="margin-b-5">Lorem ipsum dolor amet consectetur ut consequat siad esqudiat dolor</p>
                        </div>
                        <a href="#" class="content-wrapper-link"></a>
                    </div>
                </div>
            </div>
            <!--// end row -->
        </div>
    </div>
</div>
<!-- End Service -->

<!-- Contact -->
<div id="contact">
    <!-- Contact List -->
    <div class="section-seperator">
        <div class="content-lg container">
            <div class="row">
                <!-- Contact List -->
                <div class="col-sm-4 sm-margin-b-50">
                    <h3><a href="#">New York</a> <span class="text-uppercase margin-l-20">Head Office</span></h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipiscing elit sed tempor incdidunt ut laboret dolor magna ut consequat siad esqudiat dolor</p>
                    <ul class="list-unstyled contact-list">
                        <li><i class="margin-r-10 color-base icon-call-out"></i> 1 012 3456 7890</li>
                        <li><i class="margin-r-10 color-base icon-envelope"></i> hq@aitOnepage.com</li>
                    </ul>
                </div>
                <!-- End Contact List -->

                <!-- Contact List -->
                <div class="col-sm-4 sm-margin-b-50">
                    <h3><a href="#">London</a> <span class="text-uppercase margin-l-20">Operation</span></h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipiscing elit sed tempor incdidunt ut laboret dolor magna ut consequat siad esqudiat dolor</p>
                    <ul class="list-unstyled contact-list">
                        <li><i class="margin-r-10 color-base icon-call-out"></i> 44 77 3456 7890</li>
                        <li><i class="margin-r-10 color-base icon-envelope"></i> operation@AitOnepage.com</li>
                    </ul>
                </div>
                <!-- End Contact List -->

                <!-- Contact List -->
                <div class="col-sm-4 sm-margin-b-50">
                    <h3><a href="#">Singapore</a> <span class="text-uppercase margin-l-20">Finance</span></h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipiscing elit sed tempor incdidunt ut laboret dolor magna ut consequat siad esqudiat dolor</p>
                    <ul class="list-unstyled contact-list">
                        <li><i class="margin-r-10 color-base icon-call-out"></i> 50 012 456 7890</li>
                        <li><i class="margin-r-10 color-base icon-envelope"></i> finance@AitOnepage.com</li>
                    </ul>
                </div>
                <!-- End Contact List -->
            </div>
            <!--// end row -->
        </div>
    </div>
    <!-- End Contact List -->

    <!-- Google Map -->
    <div id="map" class="map height-300"></div>
</div>
<!-- End Contact -->
<!--========== END PAGE LAYOUT ==========-->

<!--========== FOOTER ==========-->
<footer class="footer">
    <!-- Links -->
    <div class="section-seperator">
        <div class="content-md container">
            <div class="row">
                <div class="col-sm-2 sm-margin-b-30">
                    <!-- List -->
                    <ul class="list-unstyled footer-list">
                        <li class="footer-list-item"><a href="#">Home</a></li>
                        <li class="footer-list-item"><a href="#">About</a></li>
                        <li class="footer-list-item"><a href="#">Work</a></li>
                        <li class="footer-list-item"><a href="#">Contact</a></li>
                    </ul>
                    <!-- End List -->
                </div>
                <div class="col-sm-2 sm-margin-b-30">
                    <!-- List -->
                    <ul class="list-unstyled footer-list">
                        <li class="footer-list-item"><a href="#">Twitter</a></li>
                        <li class="footer-list-item"><a href="#">Facebook</a></li>
                        <li class="footer-list-item"><a href="#">Instagram</a></li>
                        <li class="footer-list-item"><a href="#">YouTube</a></li>
                    </ul>
                    <!-- End List -->
                </div>
                <div class="col-sm-3">
                    <!-- List -->
                    <ul class="list-unstyled footer-list">
                        <li class="footer-list-item"><a href="#">Subscribe to Our Newsletter</a></li>
                        <li class="footer-list-item"><a href="#">Privacy Policy</a></li>
                        <li class="footer-list-item"><a href="#">Terms &amp; Conditions</a></li>
                    </ul>
                    <!-- End List -->
                </div>
            </div>
            <!--// end row -->
        </div>
    </div>
    <!-- End Links -->

    <!-- Copyright -->
    <div class="content container">
        <div class="row">
            <div class="col-xs-6">
                <img class="footer-logo" src="src/img/logo-dark.png" alt="Aitonepage Logo">
            </div>
            <div class="col-xs-6 text-right">
                <p class="margin-b-0"><a class="fweight-700" href="#">Aitonepage</a> Theme Powered by: <a class="fweight-700" href="#">Lorem</a></p>
            </div>
        </div>
        <!--// end row -->
    </div>
    <!-- End Copyright -->
</footer>
<!--========== END FOOTER ==========-->

<!-- Back To Top -->
<a href="javascript:void(0);" class="js-back-to-top back-to-top">Top</a>

<!-- JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- CORE PLUGINS -->
<script src="src/vendor/jquery.min.js" type="text/javascript"></script>
<script src="src/vendor/jquery-migrate.min.js" type="text/javascript"></script>
<script src="src/vendor/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

<!-- PAGE LEVEL PLUGINS -->
<script src="src/vendor/jquery.easing.js" type="text/javascript"></script>
<script src="src/vendor/jquery.back-to-top.js" type="text/javascript"></script>
<script src="src/vendor/jquery.smooth-scroll.js" type="text/javascript"></script>
<script src="src/vendor/jquery.wow.min.js" type="text/javascript"></script>
<script src="src/vendor/swiper/js/swiper.jquery.min.js" type="text/javascript"></script>
<script src="src/vendor/masonry/jquery.masonry.pkgd.min.js" type="text/javascript"></script>
<script src="src/vendor/masonry/imagesloaded.pkgd.min.js" type="text/javascript"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBsXUGTFS09pLVdsYEE9YrO2y4IAncAO2U"></script>

<!-- PAGE LEVEL SCRIPTS -->
<script src="src/js/layout.min.js" type="text/javascript"></script>
<script src="src/js/components/wow.min.js" type="text/javascript"></script>
<script src="src/js/components/swiper.min.js" type="text/javascript"></script>
<script src="src/js/components/masonry.min.js" type="text/javascript"></script>
<script src="src/js/components/google-map.min.js" type="text/javascript"></script>

</body>
<!-- END BODY -->
</html>
