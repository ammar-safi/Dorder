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
                                {{-- <img src="{{ asset('app-assets/images/pages/auth-bg-da') }}" class="img-fluid my-3" alt="branding logo"> --}}
                                <h1 class="error-title mt-1">Forbidden</h1>
                                <p class="p-2">
                                    Sorry , You can't visit this site
                                </p>
                                @if(Auth::user()->type == "client")
                                <form action="{{Route("logout")}}" method="post">
                                    @csrf
                                    {{-- <a class="btn btn-primary round glow" ><input type="submit" value="Back to login" ></a> --}}
                                    <a class="btn btn-primary round glow" onclick="this.closest('form').submit()" style="color: black">Back to login</a>
                                </form>
                                @else
                                <button type="button" class="btn btn-primary rounded-button" style="color: black; padding: 7.0px 20px;" onclick="history.back()">go back</button>
                                @endif                                
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
