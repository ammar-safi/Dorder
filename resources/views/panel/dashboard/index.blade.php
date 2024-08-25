
@include('panel.static.header')
@include('panel.static.main')



<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">

            

            <br><br><br>
            <div class="container">
                <div class="row">
                    <!-- Title -->
                    <div class="col-12 text mb-4">
                        <h1 style="font-family: 'Cairo', sans-serif; color: #2c3e50;">لوحة التحكم الرئيسية</h1>
                        <p style="font-family: 'Cairo', sans-serif; color: #34495e;">مرحباً بك في لوحة التحكم</p>
                    </div>
                    <!-- Cities Card -->
                    <div class="col-md-4">
                        <div class="card shadow-lg rounded-lg" style="border: none;">
                            <div class="card-body">
                                <h5 class="card-title" style="font-family: 'Cairo', sans-serif; color: #2980b9;">المدن </h5>
                                <p class="card-text">إدارة {{in_array($citiesCount , range(2,9))? $citiesCount . " مدن" : $citiesCount . " مدينة"}}  متاحة في النظام.</p>
                                <a href="{{ route('cities.show') }}" class="btn btn-primary" style="background-color: #2980b9; border: none; border-radius: 20px;">إدارة المدن</a>
                            </div>
                        </div>
                    </div>

                    <!-- Areas Card -->
                    <div class="col-md-4">
                        <div class="card shadow-lg rounded-lg" style="border: none;">
                            <div class="card-body">
                                <h5 class="card-title" style="font-family: 'Cairo', sans-serif; color: #27ae60;">المناطق  </h5>
                                <p class="card-text">إدارة {{in_array($areasCount , range(2,9))? $areasCount . " مناطق" : $areasCount  . " منطقة"}} ضمن المدن.</p>
                                <a href="{{ route('areas.show') }}" class="btn btn-primary" style="background-color: #27ae60; border: none; border-radius: 20px;">إدارة المناطق</a>
                            </div>
                        </div>
                    </div>

                    <!-- Users Card -->
                    <div class="col-md-4">
                        <div class="card shadow-lg rounded-lg" style="border: none;">
                            <div class="card-body">
                                <h5 class="card-title" style="font-family: 'Cairo', sans-serif; color: #e74c3c;">العملاء</h5>
                                <p class="card-text">إدارة {{in_array($clientsCount , range(2,9))? $clientsCount . " عملاء" : $clientsCount  . " عميل"}} .</p>
                                <a href="{{ route('clients.show') }}" class="btn btn-primary" style="background-color: #e74c3c; border: none; border-radius: 20px;">إدارة العملاء</a>
                            </div>
                        </div>
                    </div>
                    <!-- monitrs card -->
                    <div class="col-md-4" ">
                        <div class="card shadow-lg rounded-lg" style="border: none;background-color: #ffffff">
                            <div class="card-body">
                                <h5 class="card-title" style="font-family: 'Cairo', sans-serif; color: #007c15;">المشرفين </h5>
                                <p class="card-text">إدارة {{in_array($monitorsCount , range(2,9))? $monitorsCount . " مشرفين" : $monitorsCount  . " مشرف"}} .</p>
                                <a href="{{ route('monitors.show') }}" class="btn btn-primary" style="background-color: #e74c3c; border: none; border-radius: 20px;">إدارة المشرفين</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-lg rounded-lg" style="border: none;">
                            <div class="card-body">
                                <h5 class="card-title" style="font-family: 'Cairo', sans-serif; color: #000000;">عمال التوصيل </h5>
                                <p class="card-text">إدارة {{in_array($deliversCount , range(2,9))? $deliversCount . " عمال توصيل" : $deliversCount  . " عامل توصيل"}} التوصيل .</p>
                                <a href="{{ route('monitors.show') }}" class="btn btn-primary" style="background-color: #e74c3c; border: none; border-radius: 20px;">إدارة عمال التوصيل</a>
                            </div>
                        </div>
                    </div>
                
                </div>
        </div>

        </div>
    </div>
</div>
<!-- END: Content-->

<!-- demo chat-->
{{-- <div class="widget-chat-demo"> 
    <!-- widget chat demo footer button start -->
    <button class="btn btn-primary chat-demo-button glow px-1"><i class="livicon-evo" data-options="name: comments.svg; style: lines; size: 24px; strokeColor: #fff; autoPlay: true; repeat: loop;"></i></button>
    <!-- widget chat demo footer button ends -->
    <!-- widget chat demo start -->
    <div class="widget-chat widget-chat-demo d-none">
        <div class="card mb-0">
            <div class="card-header border-bottom p-0">
                <div class="media m-75">
                    <a href="JavaScript:void(0);">
                        <div class="avatar mr-75">
                            <img src="../../../app-assets/images/portrait/small/avatar-s-2.jpg" alt="avtar images" width="32" height="32">
                            <span class="avatar-status-online"></span>
                        </div>
                    </a>
                    <div class="media-body">
                        <h6 class="media-heading mb-0 pt-25"><a href="javaScript:void(0);">Kiara Cruiser</a></h6>
                        <span class="text-muted font-small-3">Active</span>
                    </div>
                    <i class="bx bx-x widget-chat-close float-right my-auto cursor-pointer"></i>
                </div>
            </div>
            <div class="card-body widget-chat-container widget-chat-demo-scroll">
                <div class="chat-content">
                    <div class="badge badge-pill badge-light-secondary my-1">today</div>
                    <div class="chat">
                        <div class="chat-body">
                            <div class="chat-message">
                                <p>How can we help? 😄</p>
                                <span class="chat-time">7:45 AM</span>
                            </div>
                        </div>
                    </div>
                    <div class="chat chat-left">
                        <div class="chat-body">
                            <div class="chat-message">
                                <p>Hey John, I am looking for the best admin template.</p>
                                <p>Could you please help me to find it out? 🤔</p>
                                <span class="chat-time">7:50 AM</span>
                            </div>
                        </div>
                    </div>
                    <div class="chat">
                        <div class="chat-body">
                            <div class="chat-message">
                                <p>Stack admin is the responsive bootstrap 4 admin template.</p>
                                <span class="chat-time">8:01 AM</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer border-top p-1">
                <form class="d-flex" onsubmit="widgetChatMessageDemo();" action="javascript:void(0);">
                    <input type="text" class="form-control chat-message-demo mr-75" placeholder="Type here...">
                    <button type="submit" class="btn btn-primary glow px-1"><i class="bx bx-paper-plane"></i></button>
                </form>
            </div>
        </div>
    </div></div>
    <!-- widget chat demo ends -->
--}}
<div class="sidenav-overlay"></div>
<div class="drag-target"></div>


@include('panel.static.footer')
