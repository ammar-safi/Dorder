@include('panel.static.header')
@include('panel.static.main')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        <style>
            .container {
                font-family: Arial, sans-serif;
                /* background-color: #f0f0f0; */
                display: flex;
                justify-content:left;
                align-items:;
                height: 60vh;
                margin: 0;
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 20px;
                padding: 20px;
            }

            .widget {
    background-size: cover; /* يجعل الصورة تغطي كامل العنصر */
    background-position: center; /* يحدد موضع الصورة في المنتصف */
    border-radius: 15px;
    padding: 50px;
    color: white;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    text-decoration: none; /* إزالة التزيين الافتراضي للرابط */
            }




            .widget h2 {
                margin: 0;
            }

            .widget:hover {
                opacity: 0.9; /* تأثير عند التمرير */
            }
        </style>
        </div>
        <div class="content-body">

            <h2> اختر مدينة معينة لتقم بتعديلها</h2>
            
            <div class="container">
                @foreach ($cities as $city)
                <a href="#" class="widget">
                    <h2>{{$city->title}}</h2>
                </a>
                @endforeach
            </div>
            
            
            
        </div></div></div>
            
    </div>
</div>

@include('panel.static.footer')
