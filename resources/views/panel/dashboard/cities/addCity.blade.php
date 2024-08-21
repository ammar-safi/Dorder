@include('panel.static.header')
@include('panel.static.main')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            {{-- <style>
                .btn {
                    background-color: #007bff; /* لون الخلفية */
                    color: white; /* لون النص */
                    border: none; /* إزالة الحدود */
                    border-radius: 25px; /* حواف مدورة */
                    padding: 10px 20px; /* مساحة داخل الزر */
                    text-align: center; /* محاذاة النص في الوسط */
                    text-decoration: none; /* إزالة الخط السفلي */
                    display: inline-block; /* لجعل العنصر a مثل الزر */
                    cursor: pointer; /* تغيير شكل المؤشر عند المرور فوق الزر */
                }

                .btn:hover {
                    background-color: #0056b3; /* لون الخلفية عند التمرير فوق الزر */
                }


            </style> --}}
        </div>

        <div class="content-body">

            <br><br><br>
            @if ($is_exist)            
            <div style="background-color: #dfe7b1; border-right: 6px solid #cfee22; padding: 20px; border-radius: 10px;">
                <p style="font-size: 20px; margin: 0;">
                     <strong>تنبيه ⚠</strong> <br>   <br>
                        هل تقصد  مدينة {{$is_exist->title}} ؟  هذه المدينة موجودة بالفعل   
                لكن يمكنك اضافة المدينة اذا كنت تقصد مدينة اخرى
                </p>
            </div>
                <br><br>
            @endif
            <h5>بعد اضافة مدينة {{$request->title}} يجب اضافة ما يلي :</h5>
            <br>
            <h5> - مناطق جديدة</h5>
            <h5> - مشرفين جدد</h5>
            <h5> - مراسلين جدد</h5>
            <br><br>
            <h6> من اجل تخديم المدينة قم باضافة مناطق جديدة لمدينة {{$request->title}} من قائمة "المناطق المخدمة -> اضافة منطقة جديدة "</h4>
            <br>
            <h6>ثم قم بتعيين مشرفين وعمال توصيل في هذه المدينة من قائمة , "المناطق المخدمة -> تعديل منطقة "</h6>
            <h6> في حال اردت تعيين مشرفين وعمال توصيل جدد قم باضافتهم الى النظام اولا ثم قم بتعيينهم لهذه المدينة  , من قائمة "المشرفين -> اضافة مشرف جديد" </h6>
            <h6>ومن قائمة "عمال التوصيل -> اضافة عامل توصيل" </h6>
{{-- @dd($request->title); --}}
            <h6>
                <br><br>
                <p style="color: red">

                    هل انت متأكد من اضافة مدينة {{$request->title}} 
                </p>
                <div style="display: inline-block;">
                    <a class="btn btn-primary rounded-button" href="{{ route('cities.add') }}" style="color: black; padding: 10px 20px;">الغاء الاضافة</a> 
                </div>
                <div style="display: inline-block;">
                    <form action="{{route('cities.stor', $request)}}" method="post" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary rounded-button" style="color: black; padding: 10px 20px;">اضافة</button>        </form>
                        
                </div>
            </h6>
            </div>
        </div></div>
<br><br><br>
@include('panel.static.footer')
