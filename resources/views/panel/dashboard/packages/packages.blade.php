
@include('panel.static.header')
@include('panel.static.main')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
                    background-color: #e3eff1; /* تأثير عند التحويم على الصفوف */
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
            <script>
              
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
        </div>
        <div class="content-body">
            <div class="card-body">

            <br><br> 
            {{-- @dd(session("update_success")); --}}

            @if(session()->has("error"))
            <br>
            <div style="background-color: #dfe7b1; border-right: 6px solid #cfee22; padding: 10px; border-radius: 10px;">
                <p style="font-size: 20px; margin: 0;">
                    <strong>تنبيه ⚠</strong> <br><br>
                        {{session("error")}}
                </p>
            </div> 
            <br><br>
            @elseif(session()->has("update_success")) 
                <div style="background-color: #c6f8cf; border-right: 6px solid #1be23d; padding: 10px; border-radius: 10px;">
                    <p style="font-size: 20px; margin: 0;">
                            {{session("update_success")}}
                    </p>
                </div>
                <br><br>
            
            @elseif(session()->has("success")) 
                <div style="background-color: #c6f8cf; border-right: 6px solid #1be23d; padding: 10px; border-radius: 10px;">
                    <p style="font-size: 20px; margin: 0;">
                            {{session("success")}}
                    </p>
                </div>
                <br><br>
            
            @endif
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <div>
                    <h1 style="margin: 0;">حزم الاشتراك</h1>
                </div>
                @if (Auth::User()->type == "admin")
                <a href="{{ route('packages.add') }}" id="add-city" class="btn btn-primary rounded-button" style="background-color: rgb(23, 54, 139); color: white; padding: 8px 12px; text-decoration: none; border-radius: 5px;">إضافة حزمة</a>
                @endif
            </div>
            <br>
            <hr>
            <br>
            <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                <div style="width: 100%;" >
                    <!-- نموذج البحث عن المدينة بالاسم -->
                    <form method="GET" action="{{ route('packages.show') }}" style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
                        <select name="show" id="" onchange="this.form.submit()" class="custom-select" style="width:180px">
                            <option value='show'  {{ $show == 'show' ? 'selected' : '' }} > الحزم الفعالة</option>
                            <option value="deleted"  {{ $show == 'deleted' ? 'selected' : '' }} > الحزم المحذوفة</option>
                        </select>
                        <div style="position: relative; display: flex; align-items: center; width: 200px;">
                            <input type="text" name="search_name" id="search_name" value="{{ $searchName }}" placeholder="اسم الحزمة"  class="custom-select" style="padding: 5px 40px 5px 10px; width: 100%; font-size: 0.875rem; border-radius: 5px; border: 1px solid #ccc;">
                            <button type="submit" class="btn btn-primary rounded-button" style="position: absolute; left: 0; top: 0; bottom: 0; padding: 5px 10px; font-size: 0.875rem; background-color: rgb(23, 54, 139); color: white; border-radius: 5px; border: none;">بحث</button>
                        </div>
                        @if ($searchName) 
                        <button type="button" class="btn btn-primary rounded-button" style="padding: 5px 10px; font-size: 0.875rem; background-color: rgb(23, 54, 139); color: white; border-radius: 5px; border: none;" onclick="document.getElementById('search_name').value=''; this.form.submit();">إلغاء</button>
                        @endif
                    </form>
                </div>
                <div class="card-body">
                    <br>
                    <table>
                        <thead>
                            <tr>
                                <th>اسم الحزمة</th>
                                <th>سعر الحزمة </th>
                                <th>عدد الطلبات </th>
                                <th>سعر الطلب </th>
                                <th>عدد المشتركين </th>
                                @if (Auth::user()->type == "admin")
                                <th> </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody> 
                            @foreach ($packages as $package)                     
                                <tr>
                                    <td>{{$package->title}}</td>
                                    <td>{{$package->package_price}}</td>
                                    <td>{{$package->count_of_orders}}</td>
                                    <td>{{$package->order_price}}</td>
                                    <td>{{$package->count_of_clients}}</td>
                                    @if (Auth::user()->type == "admin")
                                    <td>
                                        <div style="position: relative; display: flex; justify-content: center;">
                                            <button onclick="toggleOptions(this)" style="background: none; border: none; cursor: pointer;">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            @if($show == "deleted")
                                                <div class="options-menu">
                                                    <form action="{{ Route("packages.restore") }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{$package->id}}">
                                                        <button type="submit" id="edit"><i class="fas fa-undo-alt"></i> استعادة </button>
                                                    </form>
                                                </div>
                                            @else
                                                <div class="options-menu">
                                                    <form action="{{ Route("packages.edit") }}" method="GET" style="display: inline;">
                                                        <input type="hidden" name="id" value="{{$package->id}}">
                                                        <button type="submit" id="edit"><i class="fas fa-edit"></i> تعديل </button>
                                                    </form>
                                                    <form action="{{ Route("packages.soft.delete") }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{$package->id}}">
                                                        <button type="submit" id="delete"><i class="fas fa-trash"></i>حذف</button>
                                                    </form>
                                                </div>
                                            @endif
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
</div>

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

@include('panel.static.footer')