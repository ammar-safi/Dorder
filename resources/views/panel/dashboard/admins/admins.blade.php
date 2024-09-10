@include('panel.static.header')
@include('panel.static.main')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
            <script>
                function edit() {
                    document.getElementById('edit').click();    
                }
                
                function deleteRow() {
                    document.getElementById('delete').click();    
                }

                function toggleOptions(button) {
                // إغلاق جميع القوائم المفتوحة
                    document.querySelectorAll('.options-menu').forEach(function(menu) {
                        menu.style.display = 'none';
                    });

                    // فتح القائمة الخاصة بالزر المضغوط
                    const optionsMenu = button.nextElementSibling;
                    optionsMenu.style.display = optionsMenu.style.display === 'block' ? 'none' : 'block';
                }

                // إغلاق القوائم عند الضغط خارجها
                document.addEventListener('click', function(event) {
                    const isClickInside = event.target.closest('.options-menu') || event.target.closest('button');
                    if (!isClickInside) {
                        document.querySelectorAll('.options-menu').forEach(function(menu) {
                            menu.style.display = 'none';
                        });
                    }
                });
            </script>

            <style>
                .table-container {
                    margin: 0px;
                    width: 100%; /* يضمن أن الجدول يأخذ عرض الشاشة بالكامل */
                    border: 1px solid #ddd;
                    border-radius: 12px;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                    overflow: hidden;
                }
                .data-table {
                    width: 100%; /* يجعل الجدول يأخذ عرض الشاشة بالكامل */
                    border-collapse: collapse; /* إزالة الفراغات بين الخلايا */
                }


                .table-title {
                    background-color: #ffffff; /* لون خلفية العنوان */
                    color: rgb(0, 0, 0); /* لون النص */
                    padding: 10px;
                    cursor: pointer;
                    text-align:right;
                    font-size: 1.5em;
                    display: flex; /* استخدام الفليكس لتوزيع العناصر */
                    justify-content: space-between; /* توزيع المساحة بين العناصر */
                    align-items: center; /* محاذاة العناصر في المنتصف */
                }

                .table-title:hover {
                    background-color: #bfd4e2; /* تأثير عند التحويم */
                }

                table {
                    width: 100%;
                    border-collapse: collapse; /* إزالة الفراغات بين الخلايا */
                }

                th, td {
                    padding: 12px;
                    text-align: center;
                    border-bottom: 1px solid #ddd; /* خط تحت الخلايا */
                }

                th {
                    background-color: #f2f2f2; /* لون خلفية رأس الجدول */
                }

                tr:hover {
                    background-color: #b5c6ca; /* تأثير عند التحويم على الصفوف */
                }

                .edit-button {
                    background-color: #008CBA; /* لون زر التعديل */
                    color: white; /* لون نص الزر */
                    border: none;
                    padding: 8px 12px;
                    text-align: center;
                    text-decoration: none;
                    display: inline-block;
                    border-radius: 5px;
                    cursor: pointer;
                }

                .edit-button:hover {
                    background-color: #007B9A; /* تأثير عند التحويم على الزر */
                }

                .table-title i {
                    transition: transform 0.3s; /* تأثير عند التحويم */
                    margin-left: 10px; /* مسافة بين العنوان والأيقونة */
                }

                .options-menu {
                    display: none;
                    position: absolute;
                    top: 100%; /* لجعل القائمة تظهر أسفل الزر */
                    right: 0; /* محاذاة القائمة إلى اليمين */
                    background-color: #fff;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                    z-index: 100;
                    padding: 10px;
                    width: 150px; /* عرض القائمة */
                    transition: all 0.3s ease;
                    /* ضب بعض الهوامش */
                    margin-top: 5px; 
                }

                .options-menu div {
                    margin-bottom: 5px; /* مساحة بين الأزرار */
                }

                .edit-button {
                    background-color: #008CBA; /* لون زر التعديل */
                    color: white; /* لون نص الزر */
                    border: none;
                    padding: 8px 12px;
                    text-align: center;
                    text-decoration: none;
                    display: inline-block;
                    border-radius: 5px;
                    cursor: pointer;
                }

                .edit-button:hover {
                    background-color: #007B9A; /* تأثير عند التحويم على الزر */
                }

                .table-title i {
                    transition: transform 0.3s; /* تأثير عند التحويم */
                    margin-left: 10px; /* مسافة بين العنوان والأيقونة */
                }

                .options-menu {
                    display: none;
                    position: absolute;
                    top: -60px;
                    right: -150px;
                    background-color: #fff;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                    z-index: 100;
                    padding: 10px;
                    width: 150px;
                    transition: all 0.3s ease;
                }

                .options-menu button {
                    display: flex;
                    align-items: center;
                    width: 100%;
                    background: none;
                    border: none;
                    color: #333;
                    padding: 8px;
                    cursor: pointer;
                    text-align: right; /* لجعل النص العربي يظهر بالكامل */
                    white-space: nowrap; /* منع النص من الانتقال لسطر آخر */
                    transition: background-color 0.2s ease;
                    font-size: 14px;
                }

                .options-menu button:hover {
                    background-color: #f0f0f0;
                    border-radius: 4px;
                }

                .options-menu i {
                    margin-left: 10px; /* جعل الأيقونة على يسار النص */
                    color: #555;
                }


            </style>
        </div>
        <div class="content-body">
            <br><br>
            @if (isset($validate))                        
            <br><br>
            <div style="background-color: #ffb3b3; border-right: 6px solid #c20c0c; padding: 20px; border-radius: 10px;">
                <p style="font-size: 20px; margin: 0;">
                    @error('id')
                        {{$message}}
                    @enderror
                </p>
            </div>
                               
            @elseif(session()->has("error"))
                <br><br>
                <div style="background-color: #ffb3b3; border-right: 6px solid #c20c0c; padding: 20px; border-radius: 10px;">
                    <p style="font-size: 20px; margin: 0;">
                            {{session("error")}}
                    </p>
                </div>                    
            @elseif(session()->has("success"))
            <br><br>
                <div style="background-color: #cbf1d3; border-right: 6px solid #0cc230; padding: 20px; border-radius: 10px;">
                    <p style="font-size: 20px; margin: 0;">
                        <strong>
                            {{session("success")}}
                        </strong> 
                    </p>
                </div>                    
                <br><br>
            @endif

            
            
                
            
            <div class="card-body">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                    <div>
                        <h1 style="margin: 0;">المدراء </h1>
                    </div>
                    @if (Auth::User()->type == "admin")
                    <a href="{{ route('admins.add') }}" id="add-admin" class="btn btn-primary rounded-button" style="background-color: rgb(23, 54, 139); color: white; padding: 8px 12px; text-decoration: none; border-radius: 5px;">إضافة مدير</a>
                    @endif
                </div>
                <br>
                <hr>
                <br>
                <table>
                    <thead>
                        <tr>
                            <th>المدير</th>
                            <th>البريد الاكتروني</th>
                            <th>رقم الهاتف</th>
                            <th>تاريخ الانضمام</th>
                            <th>تاريخ التعديل</th>
                            <th></th>
                            {{-- <th>الحالة</th> --}}
                            {{-- <th>العمليات</th> --}}
                        </tr>
                    </thead>
                    <tbody>

                        
                        @foreach ($admins as $admin)
                                                
                            <tr>
                                <td>{{$admin->name}}</td>
                                <td>{{$admin->email}}</td>
                                <td>{{$admin->mobile}}</td>
                                <td>{{$admin->created_at}}</td>
                                <td>{{$admin->updated_at}}</td>
                                {{-- <td style="text-align:right">{{  ['🔴 غير نشط','🟢 نشط']  [$admin->active]}} </td> --}}
                                @if ($admin->email != "ammar@gmail.com" )
                                <td>
                                    <div style="position: relative; display: flex; justify-content: center;">
                                        <button onclick="toggleOptions(this)" style="background: none; border: none; cursor: pointer;">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="options-menu">
                                           <form action="{{ route('admins.edit')}}" method="GET">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$admin->id}}">
                                                <button type="submit">
                                                    <i class="fas fa-edit"></i> تعديل
                                                </button>
                                            </form>
                                            
                                            <form id="delete-form-{{ $admin->id }}" action="{{Route('admins.soft.delete')}}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$admin->id}}">
                                                <button type="submit" onclick="confirmDelete({{ $admin->id }})">
                                                    <i class="fas fa-trash"></i> حذف
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                                
                                @endif
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                </div>
            </div>
        </div>
    </div>
</div>
    @include('panel.static.footer')