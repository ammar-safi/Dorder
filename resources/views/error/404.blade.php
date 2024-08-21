@include('panel.static.head')
<body class="vertical-layout vertical-menu-modern semi-dark-layout 1-column  navbar-sticky footer-static bg-full-screen-image  blank-page blank-page" data-open="click" data-menu="vertical-menu-modern" data-col="1-column" data-layout="semi-dark-layout">
<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- error 404 -->
            <section class="row flexbox-container">
                <div class="col-xl-6 col-md-7 col-9">
                    <div class="card bg-transparent shadow-none">
                        <div class="card-content">
                            <div class="card-body text-center bg-transparent miscellaneous">
                                <h1 class="error-title">Page Not Found :(</h1>
                                <p class="pb-3">
                                    we couldn't find the page you are looking for</p>
                                <img class="img-fluid" src="{{ asset('app-assets/images/pages/404.png') }}" alt="404 error">
                                <a href="{{Route("Admin-Panel")}}" class="btn btn-primary round glow mt-3">BACK TO HOME</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- error 404 end -->
        </div>
    </div>
</div>
<!-- END: Content-->


@include('panel.static.footer')
