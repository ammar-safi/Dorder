@include('panel.static.Auth.AuthHeader')

<!-- BEGIN: Body-->


<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- login page start -->
            <section id="auth-login" class="row flexbox-container">
                <div class="col-xl-8 col-11">
                    <div class="card bg-authentication mb-0">
                        <div class="row m-0">
                            <!-- left section-login -->
                            <div class="col-md-6 col-12 px-0">
                                <div class="card disable-rounded-right mb-0 p-2 h-100 d-flex justify-content-center">
                                    <div class="card-header pb-1">
                                        <div class="card-title">
                                            <h4 class="text-center mb-2">مرحباً بك من جديد 😊</h4>
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            {{--  <div class="d-flex flex-md-row flex-column justify-content-around">
                                                        <a href="#" class="btn btn-social btn-google btn-block font-small-3 mr-md-1 mb-md-0 mb-1">
                                                                <i class="bx bxl-google font-medium-3"></i><span class="pl-50 d-block text-center">Google</span></a>
                                                        <a href="#" class="btn btn-social btn-block mt-0 btn-facebook font-small-3">
                                                                <i class="bx bxl-facebook-square font-medium-3"></i><span class="pl-50 d-block text-center">Facebook</span></a>
                                                </div>--}}
                                            <div class="divider">
                                                <div class="divider-text text-uppercase text-muted">
                                                    <small>دائماً نسعد بكونك معنا واستخدام خدماتنا</small>
                                                </div>
                                            </div>
                                            <form method="post" action="{{ route('login') }}">
                                                {{ csrf_field() }}
                                                <div class="form-group mb-50">
                                                    <label class="text-bold-600" for="exampleInputEmail1">بريدك الإلكتروني</label>
                                                    <input type="text" class="form-control" name="UserMail" id="exampleInputEmail1" value="{{ old('email') }}" placeholder="البريد الإلكتروني الخاص بك" required autocomplete="email" autofocus>
                                                </div>

                                                <div class="form-group">
                                                    <label class="text-bold-600" for="exampleInputPassword1">كلمة المرور:</label>
                                                    <input type="password" class="form-control" minlength="7" id="exampleInputPassword1" placeholder="كلمة المرور الخاصة بك" name="password" required autocomplete="current-password">
                                                </div>
                                                <div class="form-group d-flex flex-md-row flex-column justify-content-between align-items-center">
                                                    <div class="text-left">
                                                        <div class="checkbox checkbox-sm">
                                                            <input checked type="checkbox" name="remember" class="form-check-input" id="exampleCheck1">
                                                            <label class="checkboxsmall" for="exampleCheck1">
                                                                تذكرني ( إبقاء الجلسة نشطة لوقت أطول )
                                                            </label>
                                                        </div>
                                                    </div>
                                                    @if (Route::has('password.request'))
                                                        <div class="text-right">
                                                            <a href="{{ route('password.request') }}" class="card-link"><small> هل نسيت كلمة المرور </small></a>
                                                        </div>
                                                    @endif

                                                </div>
                                                <button type="submit" class="btn btn-primary glow w-100 position-relative"> تسجيل الدخول<i id="icon-arrow" class="bx bx-right-arrow-alt"></i></button>
                                            </form>
                                            <hr>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- right section image -->
                            <div class="col-md-6 d-md-block d-none text-center align-self-center p-3">
                                <div class="card-content">
                                    <img class="img-fluid" src="{{ asset('app-assets/images/pages/login.png') }}" alt="branding logo">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- login page ends -->

        </div>
    </div>
</div>
<!-- END: Content-->


@include('panel.static.Auth.AuthFooter')
