
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
                        <h1 style="font-family: 'Cairo', sans-serif; color: #2c3e50;">الإعدادات</h1>
                        <p style="font-family: 'Cairo', sans-serif; color: #34495e;"></p>
                        <hr>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-lg rounded-lg" style="border: none;">
                            <div class="card-body">
                                <h5 class="card-title" style="font-family: 'Cairo', sans-serif; color: #2980b9;">اوقات الدوام </h5>
                                <p class="card-text" >عرض و تعديل اوقات الداوم </p>
                                <a href="{{ route('work.times.show') }}" class="btn btn-primary" style="background-color: #2980b9; border: none; border-radius: 20px;">عرض</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-lg rounded-lg" style="border: none;">
                            <div class="card-body">
                                <h5 class="card-title" style="font-family: 'Cairo', sans-serif; color: #2980b9;">المحذوفات </h5>
                                <p class="card-text"> المدن والمناطق المحذوفة وغيرها </p>
                                <a href="#" class="btn btn-primary" style="background-color: #2980b9; border: none; border-radius: 20px;">عرض</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-lg rounded-lg" style="border: none;">
                            <div class="card-body">
                                <h5 class="card-title" style="font-family: 'Cairo', sans-serif; color: #2980b9;">قائمة الحظر </h5>
                                <p class="card-text"> الموظفين  والعملاء المحظورين</p>
                                <a href="#" class="btn btn-primary" style="background-color: #2980b9; border: none; border-radius: 20px;">عرض</a>
                            </div>
                        </div>
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
