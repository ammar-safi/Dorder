
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
            </style>
            <script>
                function toggleTable(tableId, iconId) {
                    const table = document.getElementById(tableId);
                    const icon = document.getElementById(iconId);

                    if (table.classList.contains('hidden')) {
                        table.classList.remove('hidden');
                        icon.style.transform = 'rotate(180deg)'; // تدوير الأيقونة لأسفل
                    } else {
                        table.classList.add('hidden');
                        icon.style.transform = 'rotate(0deg)'; // إعادة الأيقونة لوضعها الأصلي
                    }
                }

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
                    <h1 style="margin: 0;">المناطق المخدمة</h1>
                </div>
                <a href="{{ route('areas.add') }}" id="add-city" class="btn btn-primary rounded-button" style="background-color: rgb(23, 54, 139); color: white; padding: 8px 12px; text-decoration: none; border-radius: 5px;">إضافة منطقة</a>
            </div>
            <br>
            <hr>
            <br>
            <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                <!-- نموذج البحث عن المدينة بالاسم -->
                <form method="GET" action="{{ route('areas.show') }}" style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
                    <input type="hidden" name="city_id" value="{{$selectedCityId}}">
                    <div style="position: relative; display: flex; align-items: center; width: 200px;">
                        <input type="text" name="search_name" id="search_name" value="{{ $searchName }}" placeholder="اسم المنطقة" style="padding: 5px 40px 5px 10px; width: 100%; font-size: 0.875rem; border-radius: 5px; border: 1px solid #ccc;">
                        <button type="submit" class="btn btn-primary rounded-button" style="position: absolute; left: 0; top: 0; bottom: 0; padding: 5px 10px; font-size: 0.875rem; background-color: rgb(23, 54, 139); color: white; border-radius: 5px; border: none;">بحث</button>
                    </div>
                    @if ($searchName) 
                    <button type="button" class="btn btn-primary rounded-button" style="padding: 5px 10px; font-size: 0.875rem; background-color: rgb(23, 54, 139); color: white; border-radius: 5px; border: none;" onclick="document.getElementById('search_name').value=''; this.form.submit();">إلغاء</button>
                    @endif
                </form>
                
                <!-- نموذج اختيار المدينة -->
                <form method="GET" action="{{ route('areas.show') }}" style="margin-bottom: 10px; display: flex; align-items: center; gap: 5px;">
                    <input type="hidden" name="search_name" value="{{$searchName}}">
                    <label for="city_id" style="margin: 0; font-size: 0.875rem;">حدد مدينة:</label>
                    <select name="city_id" id="city_id" onchange="this.form.submit()" style="padding: 5px; width: 150px; font-size: 0.875rem; border-radius: 5px;">
                        <option value="">حدد مدينة</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}" {{ $selectedCityId == $city->id ? 'selected' : '' }}>
                                {{ $city->title }}
                            </option>
                        @endforeach
                    </select>
                </form>
                @foreach ($collection as $cities => $areas)
                <div class="table-container">
                    <h2 class="table-title">{{$cities}}</h2>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>المناطق</th>
                                <th>عدد المشرفين</th>
                                <th>عدد عمال التوصيل</th>
                                <th>عدد المشتركين</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($areas as $index)
                                <tr>
                                    <td>{{$index->title}}</td> 
                                    <td>{{$index->count_of_monitors}}</td> 
                                    <td>{{$index->count_of_delivers}}</td> 
                                    <td>{{$index->count_of_clients}}</td> 
                                    <td>
                                        <div style="display: inline-block; margin-right: 10px;">
                                            <form action="{{Route('areas.add.employs')}}" method="GET" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$index->id}}">
                                                <button type="submit" id="add-employs" style="background: none; border: none; color: rgb(0, 165, 0); cursor: pointer;">اضافة موظفين</button>
                                            </form>
                                        <span class="icon" onclick="edit()"><i class="fas fa-users"></i></span>

                                        </div>
            
                                        <div style="display: inline-block; margin-right: 10px;">
                                            <form action="{{ route('areas.edit.area')}}" method="GET" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$index->id}}">
                                                <input type="hidden" name="route" value="edit.area">
                                                <button type="submit" id="edit" style="background: none; border: none; color: rgb(35, 20, 172); cursor: pointer;">تعديل</button>
                                            </form>
                                        <span class="icon" onclick="edit()"><i class="fas fa-edit"></i></span>

                                        </div>
                                        
                                        <div style="display: inline-block;">
                                            <form id="delete-form-{{ $index->id }}" action="{{Route('areas.soft.delete')}}" method="POST" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$index->id}}">
                                                <button type="submit" id="delete" onclick="confirmDelete({{ $index->id }})" style="background: none; border: none; color: rgb(172, 21, 21); cursor: pointer;" >حذف</button>
                                            </form>
                                            <span class="icon" onclick="edit()"><i class="fas fa-trash"></i></span>
                                        </div>
                                        

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br><br>
                </div>
            @endforeach
            
        
        
        </div>
            </div>
        </div>
    </div>
</div>

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

@include('panel.static.footer')