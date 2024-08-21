@include('panel.static.Auth.AuthHeader')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- forgot password start -->
            <section class="row flexbox-container">
                <div class="col-xl-7 col-md-9 col-10  px-0">
                    <div class="card bg-authentication mb-0">
                        <div class="row m-0">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <!-- left section-forgot password -->
                            <div class="col-md-6 col-12 px-0">
                                <div class="card disable-rounded-right mb-0 p-2">
                                    <div class="card-header pb-1">
                                        <div class="card-title">
                                            <h4 class="text-center mb-2">هل نسيت كلمة المرور 😮؟</h4>
                                        </div>
                                    </div>
                                    <div class="form-group d-flex justify-content-between align-items-center mb-2">
                                        <div class="text-left">
                                            <div class="ml-3 ml-md-2 mr-1">
                                                <a href="{{ route('login') }}" class="card-link btn btn-outline-primary text-nowrap">تسجيل الدخول</a>
                                            </div>
                                        </div>
                                        <div class="mr-3">
                                            <a href="./" class="card-link btn btn-outline-primary text-nowrap">العودة للرئيسية</a>
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="text-muted text-center mb-2"><small>حسناً لا تقلق فنحن سوف نساعدك بالحصول على واحدة جديدة فوراً
                                                    <br />فقط أدخل بريدك الإلكتروني
                                                😎</small></div>
                                            <form class="mb-2" method="POST" action="{{ route('password.email') }}">
                                                <div class="form-group mb-2">
                                                    <label class="text-bold-600" for="exampleInputEmailPhone1">بريدك الإلكتروني</label>
                                                    <input type="text" class="form-control" id="exampleInputEmailPhone1" placeholder="بريدك الإلكتروني أكتبه هنا" required autocomplete="email" autofocus></div>
                                                <button type="submit" class="btn btn-primary glow position-relative w-100">إعادة تعيين كلمة المرور<i id="icon-arrow" class="bx bx-right-arrow-alt"></i></button>
                                            </form>
                                            <div class="text-center mb-2"><a href="{{ route('login') }}"><small class="text-muted">I
                                                        أوه لقد تذكرت كلمة المرور 🤩</small></a></div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- right section image -->
                            <div class="col-md-6 d-md-block d-none text-center align-self-center">
                                <img class="img-fluid" src="{{ asset('app-assets/images/pages/lock-screen.png') }}" alt="branding logo" width="300">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- forgot password ends -->
        </div>
    </div>
</div>
<!-- END: Content-->

@include('panel.static.Auth.AuthFooter')
