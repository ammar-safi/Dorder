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
                    background-color: #e2e9eb; /* تأثير عند التحويم على الصفوف */
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
                    @error('city_id')
                        {{$message}}
                    @enderror
                    @error('area_id')
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
            <div style="background-color: #c4f8d4; border-right: 6px solid #0d9135; padding: 20px; border-radius: 10px;">
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
                        <h1 style="margin: 0;">المشرفين </h1>
                    </div>
                    @if (Auth::User()->type == "admin")
                    <div>
                        <a href="{{ route('monitors.add') }}" id="add-monitor" class="btn btn-primary rounded-button" style="background-color: rgb(23, 54, 139); color: white; padding: 8px 12px; text-decoration: none; border-radius: 5px;">إضافة مشرف</a>
                        <a href="{{Route("employs.create" , ['route'=>'monitors.show'])}}" id="add-monitor" class="btn btn-primary rounded-button" style="background-color: rgb(23, 54, 139); color: white; padding: 8px 12px; text-decoration: none; border-radius: 5px;"> تعيين موظفين</a>
                    </div>
                    @endif
                </div>
                <br>
                <hr>
                <br>


                <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                    <form method="GET" action="{{ route('monitors.show') }}" style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
                        @if($selectedAreaId)
                            <input type="hidden" name="area_id" value="{{$selectedAreaId}}">
                        @endif
                        @if($selectedCityId)
                            <input type="hidden" name="city_id" value="{{$selectedCityId}}">
                        @endif
                        <select name="show" id="" onchange="this.form.submit()" class="custom-select" style="width:180px">
                            <option value='monitors'  {{ $show == 'monitors' ? 'selected' : '' }} >على رأس العمل</option>
                            <option value="deleted"  {{ $show == 'deleted' ? 'selected' : '' }} >المتاحين للعمل</option>
                            <option value="baned"  {{ $show == 'baned' ? 'selected' : '' }} >المشرفين المحظورين</option>
                        </select>
                        <div style="position: relative; display: flex; align-items: center; width: 200px;">
                            <input type="text" name="search_name" id="search_name" value="{{ $searchName }}" placeholder="اسم المشرف" class="custom-select" style="padding: 5px 40px 5px 10px; width: 100%; font-size: 0.875rem; border-radius: 5px; border: 1px solid #ccc;">
                            <button type="submit" class="btn btn-primary rounded-button" style="position: absolute; left: 0; top: 0; bottom: 0; padding: 5px 10px; font-size: 0.875rem; background-color: rgb(23, 54, 139); color: white; border-radius: 5px; border: none;">بحث</button>
                        </div>
                        @if ($searchName) 
                        <button type="button" class="btn btn-primary rounded-button" style="padding: 5px 10px; font-size: 0.875rem; background-color: rgb(23, 54, 139); color: white; border-radius: 5px; border: none;" onclick="document.getElementById('search_name').value=''; this.form.submit();">إلغاء</button>
                        @endif
                    </form>
                    @if($show == "monitors")
                        <form method="GET" action="{{ route('monitors.show') }}" style="margin-bottom: 10px; display: flex; align-items: center; gap: 5px;">
                            <input type="hidden" name="search_name" value="{{$searchName}}">
                            <input type="hidden" name="show" value="{{$show}}">
                            <select name="city_id" id="city_id" onchange="this.form.submit()" class="custom-select" style="width: 150px;">
                                <option value="">حدد مدينة</option>
                                <option onclick='window.location.href="{{ route("monitors.show" , ["search_name"=>$searchName]) }}"'>الغاء تحديد مدينة</option>
                                @foreach ($cities as $city)
                                <option value="{{ $city->id }}" {{ $selectedCityId == $city->id ? 'selected' : '' }}>
                                    {{ $city->title }}
                                </option>
                                @endforeach
                            </select>
                        </form>
                        
                        @if ($selectedCityId)
                            <form method="GET" action="{{ route('monitors.show') }}" style="margin-bottom: 10px; display: flex; align-items: center; gap: 5px;">
                                    <input type="hidden" name="search_name" value="{{$searchName}}">
                                    <input type="hidden" name="city_id" value="{{ $selectedCityId }}">
                                    <input type="hidden" name="show" value="{{ $show }}">
                                    {{-- <label for="area_id" style="margin: 0; font-size: 0.875rem;">حدد منطقة:</label> --}}
                                    <select name="area_id" id="area_id" onchange="this.form.submit()" class="custom-select" style="width: 150px;"   >
                                        <option value="">حدد منطقة</option>
                                        @foreach ($areas as $area)
                                            <option value="{{ $area->id }}" {{ $selectedAreaId == $area->id ? 'selected' : '' }}>
                                                {{ $area->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                        @endif
                    @endif
                </div>
                
            </div>
                



                
                @if ($selectedCityId || $selectedAreaId || $searchName || $show != "monitors")
                <table>
                    <thead>
                        <tr>
                            <th>المشرف</th>
                            @if($show == "monitors")
                            <th>المنطقة</th>
                            <th>المحافظة</th>
                            @endif
                            <th>البريد الاكتروني</th>
                            <th>رقم الهاتف</th>
                            @if (Auth::User()->type == "admin")
                            <th> </th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>

                        
                        @foreach ($monitors as $monitor)
                        {{-- @dd($Monitors) --}}
                            {{-- @dd($monitor->user->name) --}}
                            <tr>
                                <td>{{[ $monitor , $monitor->user][$show == "monitors"]->name}}</td>
                                
                                @if($show == "monitors")
                                    <td>{{$monitor->area->title}}</td>
                                    <td>{{$monitor->area->city->title /*()->withTrashed()->first('title')->title*/}}</td>
                                @endif
                                <td>{{[ $monitor , $monitor->user][$show == "monitors"]->email}}</td>
                                <td>{{[ $monitor , $monitor->user][$show == "monitors"]->mobile}}</td>
                                
                                
                                @if (Auth::user()->type == "admin")
                                <td>
                                    <div style="position: relative; display: flex; justify-content: center;">
                                        <button onclick="toggleOptions(this)" style="background: none; border: none; cursor: pointer;">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="options-menu">
                                        @if($show == "monitors" || $show == "deleted")    
                                            <form action="{{ route('monitors.edit')}}" method="GET" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="id" value="{{[$monitor , $monitor->user][$show == "monitors"] ->id}}">
                                                <button type="submit" id="edit">
                                                    <i class="fas fa-edit"></i>تعديل
                                                </button>
                                            </form>
                                        
                                            @if($show == "monitors")
                                                <form action="{{ route('monitors.soft.delete')}}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$monitor->id}}">
                                                    <button type="submit" id="delete">
                                                        <i class="fas fa-user-slash"></i>اقالة
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('monitors.employ')}}" method="get" style="display: inline;">
                                                <input type="hidden" name="id" value="{{$monitor->id}}">
                                                <button type="submit" id="delete">
                                                    <i class="fas fa-add"></i>تعيين منطقة
                                                </button>
                                            </form>

                                            <form action="{{ route('monitors.ban')}}" method="POST" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="id" value="{{[$monitor , $monitor->user][$show == "monitors"] ->id}}">
                                                <button type="submit" id="delete">
                                                    <i class="fas fa-ban"></i>حظر
                                                </button>
                                            </form>
                                             {{-- @elseif() --}}
                                        @endif

                                        @if($show == 'baned')
                                            <form action="{{ route('monitors.restore')}}" method="POST" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$monitor->id}}">
                                                <button type="submit" id="edit">
                                                    <i class="fas fa-undo-alt"></i>الغاء الحظر
                                                </button>
                                            </form>
                                        @endif
                                        </div>
                                    </div>                               
                                </td>
                                @endif
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif


                </div>
            </div>
        </div>
    </div>
</div>
    @include('panel.static.footer')