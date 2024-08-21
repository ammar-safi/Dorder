@include('panel.static.head')


<body class="vertical-layout vertical-menu-modern semi-dark-layout 1-column  navbar-sticky footer-static bg-full-screen-image  blank-page blank-page" data-open="click" data-menu="vertical-menu-modern" data-col="1-column" data-layout="semi-dark-layout">
<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- error 500 -->
            <section class="row flexbox-container">
                <div class="col-xl-6 col-md-7 col-9">
                    <!-- w-100 for IE specific -->
                    <div class="card bg-transparent shadow-none">
                        <div class="card-content">
                            <div class="card-body text-center bg-transparent miscellaneous">
                                <img src="{{ asset('app-assets/images/pages/500.png') }}" class="img-fluid my-3" alt="branding logo">
                                <h1 class="error-title mt-1">Internal Server Error!</h1>
                                <p class="p-2">
                                    Restart the browser after clearing the cache and deleting the cookies. <br> Issues triggered by wrong file
                                    and directory permissions.
                                </p>
                                <a href="{{Route('Admin-Panel')}}" class="btn btn-primary round glow">BACK
                                    TO
                                    HOME</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- error 500 end -->
        </div>
    </div>
</div>
<!-- END: Content-->


@include('panel.static.footer')
