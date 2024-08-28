@include('panel.static.header')
@include('panel.static.main')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <style>
                .p-3  {
                    padding: 1rem !important;
                    margin-right: 10% !important ;
                    margin-top: 2.5% !important ;
                    forn
                }
            </style>
        </div>
        <div class="content-body">
            <br><br><br>
            <div class="container">
                <div class="col-12 text mb-4">
                    <h1 style="font-family: 'Cairo', sans-serif; color: #071f38;">الإعدادات</h1>
                    <hr>
                </div>

                <div class="list-group">
                    <a href="{{ route('work.times.show') }}" class="list-group-item list-group-item-action">
                        <h5 class="mb-1" style="font-family: 'Cairo', sans-serif; color: #2980b9;">اوقات الدوام</h5>
                        <p class="mb-1" style="font-family: 'Cairo', sans-serif; color: #34495e;">عرض و تعديل اوقات الداوم</p>
                    </a>
                    {{-- <div id="collapseOne" class="collapse">
                        <div class="p-3">
                            <a href="{{ route('work.times.show') }}" class="btn btn-primary" style="background-color: #2980b9; border: none; border-radius: 20px;">عرض</a>
                        </div>
                    </div> --}}

                    <a href="#collapseTwo" class="list-group-item list-group-item-action" data-toggle="collapse">
                        <h5 class="mb-1" style="font-family: 'Cairo', sans-serif; color: #2980b9;">المحذوفات</h5>
                        <p class="mb-1" style="font-family: 'Cairo', sans-serif; color: #34495e;">المدن والمناطق المحذوفة وغيرها</p>
                    </a>
                    <div id="collapseTwo" class="collapse">
                        <a href="#" >
                        <div class="p-3">
                            <h6 class="mb-1" style="font-family: 'Cairo', sans-serif;">المدن </h6>
                        </div></a>
                        <hr>
                        <a href="#" >
                        <div class="p-3">
                            <h6 class="mb-1" style="font-family: 'Cairo', sans-serif; color: ">المناطق </h6>
                        </div></a>
                        <hr>
                        <a href="#" >
                        <div class="p-3">
                           <h6 class="mb-1" style="font-family: 'Cairo', sans-serif; color: ">الحزم </h6>
                        </div></a>
                    </div>

                    <a href="#collapseThree" class="list-group-item list-group-item-action" data-toggle="collapse">
                        <h5 class="mb-1" style="font-family: 'Cairo', sans-serif; color: #2980b9;">قائمة الحظر</h5>
                        <p class="mb-1" style="font-family: 'Cairo', sans-serif; color: #34495e;">الموظفين والعملاء المحظورين</p>
                    </a>
                    <div id="collapseThree" class="collapse">
                        <a href="{{'#'}}"><div class="p-3">
                            <h6 class="mb-1" style="font-family: 'Cairo', sans-serif;">العملاء</h6>
                        </div></a>
                        <hr>
                        <a href="#" >
                        <div class="p-3">
                            <h6 class="mb-1" style="font-family: 'Cairo', sans-serif; color: ">المشرفين </h6>
                        </div></a>
                        <hr>
                        <a href="#" >
                        <div class="p-3">
                            <h6 class="mb-1" style="font-family: 'Cairo', sans-serif; color: ">المشرفين </h6>
                        </div></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Content-->

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

@include('panel.static.footer')
