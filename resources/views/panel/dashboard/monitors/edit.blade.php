@include('panel.static.header')
@include('panel.static.main')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
            <div class="col-12">
                <br><br>
                <h1>تعديل المشرف {{$monitor->monitor->name}}</h1>
            </div>
        </div>
        <div class="content-body">
            <br><br>
            @if(session()->has("error"))
                <br><br>
                <div style="background-color: #ffb3b3; border-right: 6px solid #c20c0c; padding: 20px; border-radius: 10px;">
                    <strong>
                         يبدو ان هناك مشكلة , حاول مرة اخرى  ⚠
                    </strong>
                    <p style="font-size: 20px; margin: 0;">
                            {{session("error")}}
                    </p>
                </div> 
            @endif
           
        </div>
    </div>
</div>
<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

@include('panel.static.footer')
