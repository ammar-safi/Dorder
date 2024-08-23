<!-- BEGIN: Main Menu-->

@php
!isset($flag) ? $flag = NULL : NULL ;
// class={{$flag == "client-addresses"?'active':NULL
@endphp

<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto"><a class="navbar-brand" href="#">
                    <div class="brand-logo"><img class="logo" src="{{ asset('app-assets/images/logo/logo.png') }}" /></div>
                    <h2 class="brand-text mb-0">{{ config('app.name') }}</h2>
                </a></li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="bx bx-x d-block d-xl-none font-medium-4 primary"></i><i class="toggle-icon bx bx-disc font-medium-4 d-none d-xl-block primary" data-ticon="bx-disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        {{-- <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="lines">
            <li class=" navigation-header"><span>Dashboard</span></li>
            <li class=" nav-item"><a href="../../../html/rtl/vertical-menu-template-semi-dark/index.html"><i class="menu-livicon" data-icon="desktop"></i><span class="menu-title" data-i18n="Dashboard">Dashboard</span><span class="badge badge-light-danger badge-pill badge-round float-right mr-2">2</span></a>
                <ul class="menu-content">
                    <li class="active"><a href="dashboard-ecommerce.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="eCommerce">eCommerce</span></a>
                    </li>
                    <li><a href="dashboard-analytics.html"><i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Analytics">Analytics</span></a>
                    </li>
                </ul>
            </li>
        </ul>
        --}}
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="lines">
            <li class=" navigation-header"><span></span></li>
            <li class=" navigation-header"><span>الرئيسية</span></li>
            <li><a href="{{Route('Admin-Panel')}}"><i class="menu-livicon" data-icon="home"></i></i><span class="menu-item" data-i18n="eCommerce">الصفحة الرئيسية</span></a></li>
            <li><a href="#"><i class="menu-livicon" data-icon="dashboard"></i></i><span class="menu-item" data-i18n="eCommerce">الاحصائيات</span></a></li>
                        
                
        </ul>
            
        
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="lines">
            <li class=" navigation-header"><span>الخدمات</span></li>
            
            <li class=" nav-item"><a href="../../../html/rtl/vertical-menu-template-semi-dark/index.html"><i class="menu-livicon" data-icon="location"></i><span class="menu-title" data-i18n="Dashboard">المدن المخدمة</span><span class="badge badge-light-danger badge-pill badge-round float-right mr-2"></span></a>
                <ul class="menu-content">
                    {{-- <li class={{$flag? NULL : "active"}}></li> --}}
                    <li class={{$flag == "show-cities"?'active':NULL}}><a href='{{Route("cities.show")}}'><i class="bx bx-right-arrow-alt"></i></i><span class="menu-item" data-i18n="eCommerce">ادارة المدن </span></a>    
                    {{-- <li class={{$flag == "edit-city"?'active':NULL}}><a href="{{Route("cities.edit.city")}}"><i class="bx bx-right-arrow-alt"></i></i><span class="menu-item" data-i18n="eCommerce">تعديل مدينة</span></a>     --}}
                    <li class={{$flag == "add-city"?'active':NULL}}><a href='{{route("cities.add")}}'><i class="bx bx-right-arrow-alt"></i></i><span class="menu-item" data-i18n="eCommerce">اضافة مدينة  </span></a>    
                    </ul>
            </li>
            <br>
                
            <li class=" nav-item"><a href="../../../html/rtl/vertical-menu-template-semi-dark/index.html"><i class="menu-livicon" data-icon="map"></i><span class="menu-title" data-i18n="Dashboard">المناطق المخدمة</span><span class="badge badge-light-danger badge-pill badge-round float-right mr-2"></span></a>
                <ul class="menu-content">
                    {{-- <li class={{$flag? NULL : "active"}}></li> --}}
                    <li class={{$flag == "show-areas"?'active':NULL}}><a href="{{Route("areas.show")}}"><i class="bx bx-right-arrow-alt"></i></i><span class="menu-item" data-i18n="eCommerce">ادارة المناطق المخدمة</span></a>    
                    {{-- <li class={{$flag == "a"?'active':NULL}}><a href="#"><i class="bx bx-right-arrow-alt"></i></i><span class="menu-item" data-i18n="eCommerce">تعديل منطقة</span></a>     --}}
                    <li class={{$flag == "add-area"?'active':NULL}}><a href="{{Route('areas.add')}}"><i class="bx bx-right-arrow-alt"></i></i><span class="menu-item" data-i18n="eCommerce">اضافة منطقة </span></a>    
                </ul>
            </li>
            <br>
            
            <li class=" nav-item"><a href="../../../html/rtl/vertical-menu-template-semi-dark/index.html"><i class="menu-livicon" data-icon="user"></i><span class="menu-title" data-i18n="Dashboard">المدراء</span><span class="badge badge-light-danger badge-pill badge-round float-right mr-2"></span></a>
                <ul class="menu-content">
                    {{-- <li class={{$flag? NULL : "active"}}></li> --}}
                    <li class={{$flag == "show-admins"?'active':NULL}}><a href="{{Route('admins.show')}}"><i class="bx bx-right-arrow-alt"></i></i><span class="menu-item" data-i18n="eCommerce">ادارة المدراء</span></a>    
                    {{-- <li class={{$flag == "a"?'active':NULL}}><a href="#"><i class="bx bx-right-arrow-alt"></i></i><span class="menu-item" data-i18n="eCommerce">تعديل مدير</span></a>     --}}
                    <li class={{$flag == "add-admin"?'active':NULL}}><a href="{{Route('admins.add')}}"><i class="bx bx-right-arrow-alt"></i></i><span class="menu-item" data-i18n="eCommerce">اضافة مدير</span></a>    
                </ul>
            </li>
            <br>
            
            <li class=" nav-item"><a href="../../../html/rtl/vertical-menu-template-semi-dark/index.html"><i class="menu-livicon" data-icon="briefcase"></i><span class="menu-title" data-i18n="Dashboard">المشرفين</span><span class="badge badge-light-danger badge-pill badge-round float-right mr-2"></span></a>
                <ul class="menu-content">
                    {{-- <li class={{$flag? NULL : "active"}}></li> --}}
                    <li class={{$flag == "show-monitors"?'active':NULL}}><a href="{{Route('monitors.show')}}"><i class="bx bx-right-arrow-alt"></i></i><span class="menu-item" data-i18n="eCommerce">ادارة المشرفين</span></a>    
                    {{-- <li class={{$flag == "a"?'active':NULL}}><a href="{{Route('monitors.')}}"><i class="bx bx-right-arrow-alt"></i></i><span class="menu-item" data-i18n="eCommerce">تعديل مشرف</span></a>     --}}
                    <li class={{$flag == "add-monitor"?'active':NULL}}><a href="{{Route('monitors.add')}}"><i class="bx bx-right-arrow-alt"></i></i><span class="menu-item" data-i18n="eCommerce">اضافة مشرف</span></a>    
                            
                </ul>
            </li>
            <br>
            
            <li class=" nav-item"><a href="../../../html/rtl/vertical-menu-template-semi-dark/index.html"><i class="menu-livicon" data-icon="truck"></i><span class="menu-title" data-i18n="Dashboard">عمال التوصيل</span><span class="badge badge-light-danger badge-pill badge-round float-right mr-2"></span></a>
                <ul class="menu-content">
                    {{-- <li class={{$flag? NULL : "active"}}></li> --}}
                    <li class={{$flag == "deliver-show"?'active':NULL}}><a href="{{Route("delivers.show")}}"><i class="bx bx-right-arrow-alt"></i></i><span class="menu-item" data-i18n="eCommerce">ادارة عمال التوصيل</span></a>    
                    <li class={{$flag == "deliver-add"?'active':NULL}}><a href="{{Route("delivers.add")}}"><i class="bx bx-right-arrow-alt"></i></i><span class="menu-item" data-i18n="eCommerce">اضافة عامل توصيل</span></a>    
    
                </ul>
            </li>
            <br>
            
            <li class=" nav-item"><a href="../../../html/rtl/vertical-menu-template-semi-dark/index.html"><i class="menu-livicon" data-icon="piggybank"></i><span class="menu-title" data-i18n="Dashboard">حزم الاشتراك</span><span class="badge badge-light-danger badge-pill badge-round float-right mr-2"></span></a>
                <ul class="menu-content">
                    {{-- <li class={{$flag? NULL : "active"}}></li> --}}
                    <li class={{$flag == "a"?'active':NULL}}><a href="#"><i class="bx bx-right-arrow-alt"></i></i><span class="menu-item" data-i18n="eCommerce">ادارة الحزم</span></a>    
                    <li class={{$flag == "a"?'active':NULL}}><a href="#"><i class="bx bx-right-arrow-alt"></i></i><span class="menu-item" data-i18n="eCommerce">تعديل حزمة</span></a>    
                    <li class={{$flag == "a"?'active':NULL}}><a href="#"><i class="bx bx-right-arrow-alt"></i></i><span class="menu-item" data-i18n="eCommerce">اضافة حزمة جديد</span></a>    
                </ul>
            </li>
            <br>
            
            <li class=" nav-item"><a href="../../../html/rtl/vertical-menu-template-semi-dark/index.html"><i class="menu-livicon" data-icon="users"></i><span class="menu-title" data-i18n="Dashboard">العملاء</span><span class="badge badge-light-danger badge-pill badge-round float-right mr-2"></span></a>
                <ul class="menu-content">
                    {{-- <li class={{$flag? NULL : "active"}}></li> --}}
                    <li class={{$flag == "a"?'active':NULL}}><a href="#"><i class="bx bx-right-arrow-alt"></i></i><span class="menu-item" data-i18n="eCommerce">ادارة العملاء</span></a>    
                    <li class={{$flag == "a"?'active':NULL}}><a href="#"><i class="bx bx-right-arrow-alt"></i></i><span class="menu-item" data-i18n="eCommerce">ادارة عناويين العملاء</span></a>    

                </ul>
            </li>
            <br>
            
            <li class=" nav-item"><a href="../../../html/rtl/vertical-menu-template-semi-dark/index.html"><i class="menu-livicon" data-icon="box"></i><span class="menu-title" data-i18n="Dashboard">الطلبات</span><span class="badge badge-light-danger badge-pill badge-round float-right mr-2"></span></a>
                <ul class="menu-content">


                </ul>
            </li>
            <br>
             
            <li class=" nav-item"><a href="../../../html/rtl/vertical-menu-template-semi-dark/index.html"><i class="menu-livicon" data-icon="star"></i><span class="menu-title" data-i18n="Dashboard">المراجعات</span><span class="badge badge-light-danger badge-pill badge-round float-right mr-2"></span></a>
                <ul class="menu-content">
                    <li class={{$flag == "a"?'active':NULL}}><a href="#"><i class="bx bx-right-arrow-alt"></i></i><span class="menu-item" data-i18n="eCommerce">المراجعات</span></a>    
                    <li class={{$flag == "a"?'active':NULL}}><a href="#"><i class="bx bx-right-arrow-alt"></i></i><span class="menu-item" data-i18n="eCommerce">التقييمات</span></a>    
                </ul>
            </li>
             
            <br>
            <li class=" nav-item"><a href="../../../html/rtl/vertical-menu-template-semi-dark/index.html"><i class="menu-livicon" data-icon="help"></i><span class="menu-title" data-i18n="Dashboard">الدعم</span><span class="badge badge-light-danger badge-pill badge-round float-right mr-2"></span></a>
                <ul class="menu-content">
                    {{-- <li class={{$flag? NULL : "active"}}></li> --}}
                    <li class={{$flag == "a"?'active':NULL}}><a href="#"><i class="bx bx-right-arrow-alt"></i></i><span class="menu-item" data-i18n="eCommerce">ارقام الدعم</span></a>    
                    <li class={{$flag == "a"?'active':NULL}}><a href="#"><i class="bx bx-right-arrow-alt"></i></i><span class="menu-item" data-i18n="eCommerce">اضافة رقم</span></a>  
                </ul>
            </li>
            <br>
            
            </li>
            
            
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="lines">
                <li class=" navigation-header"><span></span></li>
                <li><a href="#"><i class="menu-livicon" data-icon="gears"></i></i><span class="menu" data-i18n="eCommerce">الاعدادات</span></a>    
            </ul>
        </ul>
        
    </div>
</div>
<!-- END: Main Menu-->
