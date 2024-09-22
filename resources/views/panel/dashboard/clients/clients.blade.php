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
                    }
                );

                

                /*
                    هي منشان لما بدي اكتب اسم بالبحث ينضاف عال select 
                */
                // function calculateUnitPrice() {
                //     const orderCount = document.getElementById('orderCount').value;
                //     const totalPrice = document.getElementById('totalPrice').value;
                //     let unitPrice = 0;
        
                //     if (orderCount && totalPrice) {
                //         unitPrice = totalPrice / orderCount;
                //     }
        
                //     document.getElementById('unitPrice').value = unitPrice.toFixed(2); // عرض النتيجة مع دقتين عشريتين
                // }
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
                    /* background-color: #bfd4e2; تأثير عند التحويم */
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
                    transition: transform 0.3s; تأثير عند التحويم
                    margin-left: 10px; /* مسافة بين العنوان والأيقونة */
                }

                .options-menu {
                    display: none;
                    position: absolute;
                    top: -60px;
                    /* right: -150px; */
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
                {{-- <p style="font-size: 20px; margin: 0;"> --}}
                    <strong>
                        @error('id')
                            {{$message}}
                        @enderror
                    </strong>
                {{-- </p> --}}
            </div>
                               
            @elseif(session()->has("error"))
                <br><br>
                <div style="background-color: #ffb3b3; border-right: 6px solid #c20c0c; padding: 20px; border-radius: 10px;">
                    {{-- <p style="font-size: 20px; margin: 0;"> --}}
                            <strong>
                                {{session("error")}}
                            </strong>
                            
                    {{-- </p> --}}
                </div>                    
            @elseif(session()->has("success"))
            <br><br>
                <div style="background-color: #c4f8d4; border-right: 6px solid #0d9135; padding: 20px; border-radius: 10px;">
                    {{-- <p style="font-size: 20px; margin: 0;"> --}}
                        <strong>
                            {{session("success")}}
                        </strong> 
                    {{-- </p> --}}
                </div>                    
                <br><br>
            @endif

            
            
                
            
            <div class="card-body">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                    <div>
                        <h1 style="margin: 0;">العملاء </h1>
                    </div>

                    <div>
                        <a href="{{ Route("clients.add") }}" id="add-deliver" class="btn btn-primary rounded-button" style="background-color: rgb(23, 54, 139); color: white; padding: 8px 12px; text-decoration: none; border-radius: 5px;">إضافة عميل </a>
                    </div>
                   
                </div>
                <br>
                <hr>
                <br>

                {{-- فلترة البحث --}}
               
                <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                    <form method="GET" action="{{ route('clients.show') }}" style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
                        @if($selectedAreaId)
                            <input type="hidden" name="area_id" value="{{$selectedAreaId}}">
                        @endif
                        @if($selectedCityId)
                            <input type="hidden" name="city_id" value="{{$selectedCityId}}">
                        @endif

                        <select name="show" id="" onchange="this.form.submit()" class="custom-select" style="width:180px">
                            <option value='show'  {{ $show == 'show' ? 'selected' : '' }} >العملاء</option>
                            <option value="active"  {{ $show == 'active' ? 'selected' : '' }} >المشتركين</option>
                            <option value="not_active"  {{ $show == 'not_active' ? 'selected' : '' }} >الغير مشتركين</option>
                            <option value="deleted"  {{ $show == 'deleted' ? 'selected' : '' }} >المحظورين</option>
                        </select>

                        <div style="position: relative; display: flex; align-items: center; width: 200px;">
                            <input  class="custom-select" type="text" name="search_name" id="search_name" value="{{ $searchName }}" placeholder="اسم العميل" style="padding: 5px 40px 5px 10px; width: 100%; font-size: 0.875rem; border-radius: 5px; border: 1px solid #ccc;">
                            <button type="submit" class="btn btn-primary rounded-button" style="position: absolute; left: 0; top: 0; bottom: 0; padding: 5px 10px; font-size: 0.875rem; background-color: rgb(23, 54, 139); color: white; border-radius: 5px; border: none;">بحث</button>
                        </div>
                        @if ($searchName) 
                        <button type="button" class="btn btn-primary rounded-button" style="padding: 5px 10px; font-size: 0.875rem; background-color: rgb(23, 54, 139); color: white; border-radius: 5px; border: none;" onclick="document.getElementById('search_name').value=''; this.form.submit();">إلغاء</button>
                        @endif
                    </form>
                    
                    <form method="GET" action="{{ route('clients.show') }}" style="margin-bottom: 10px;width:200px; display: flex; align-items: center; gap: 5px;">
                        <input type="hidden" name="search_name" value="{{$searchName}}">
                        <input type="hidden" name="show" value="{{$show}}">
                        <select name="city_id" id="city_id" onchange="this.form.submit()" class="custom-select">
                            <option value="">حدد مدينة</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}" {{ $selectedCityId == $city->id ? 'selected' : '' }}>
                                    {{ $city->title }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                    
                    @if ($selectedCityId)
                        <form method="GET" action="{{ route('clients.show') }}" style="margin-bottom: 10px;width:200px; display: flex; align-items: center; gap: 5px;">
                            <input type="hidden" name="show" value="{{$show}}">
                            <input type="hidden" name="search_name" value="{{$searchName}}">
                            <input type="hidden" name="city_id" value="{{ $selectedCityId }}">
                            <select name="area_id" id="area_id" onchange="this.form.submit()" class="custom-select">
                                <option value="">حدد منطقة</option>
                                @foreach ($areas as $area)
                                    <option value="{{ $area->id }}" {{ $selectedAreaId == $area->id ? 'selected' : '' }}>
                                        {{ $area->title }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    @endif
                </div>
                <br>
                <div>
                    <form action="{{route('clients.edit')}}" method="get">
                        @if($searchName) 
                            <input type="hidden" name="search_name" value="{{$searchName}}">
                        @else 
                            <input id="hiddenSearchName" type="hidden" name="search_name">
                        @endif

                        <input type="hidden" name="city_id" value="{{ $selectedCityId }}">
                        <input type="hidden" name="area_id" value="{{$selectedAreaId}}">

                    </form>
                </div>
            </div>
            {{-- الصفحة الرئيسية --}}
            @if (($selectedCityId || $selectedAreaId || $searchName || $clients ) && !$id)
                <div class="container"><div class="container">
                    <div class="row">
                        @foreach ($clients as $client)
                            <!-- Cities Card -->
                            <div class="col-md-6">
                                <a href="{{ route('clients.show' , ['id'=>$client->id]) }}" style="text-decoration: none;"> <!-- تغليف البطاقة بـ <a> -->
                                    <div class="card shadow-lg rounded-lg" style="border: none; display: flex; flex-direction: row; align-items: center;">
                                        <div class="card-body" style="display: flex; gap: 2rem; flex-grow: 1;">
                                            
                                            <!-- صورة العميل -->
                                            <div style="display: flex;">
                                                @php
                                                    $image = $client->image ? Storage::url($client->image->url) : '../../../../app-assets/images/portrait/small/images.png';
                                                @endphp
                                                <img src="{{ $image }}" alt="صورة العميل" style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover; margin-right: 10px;">
                                            </div>
                                            
                                            <!-- تفاصيل العميل -->
                                            <div>
                                                <h5 class="card-title" style="font-family: 'Cairo', sans-serif; color: #000000;">{{ $client->name }}</h5>
                                                <p class="card-text" style="color: #686868;">{{$client->area ? (' من ' . $client->area->city->title . ' يقيم في ' . $client->area->title):'لم يتم تحديد مدينة' }}</p>
                                            </div>  
                                        </div>
                                        
                                        <div style="display: flex; justify-content: center; align-items: center;">
                                            <div style="display: flex; justify-content: center; align-items: center; border-radius: 50%; width: 40px; height: 40px; color: #070707;">
                                                <i class="fas fa-arrow-left"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>                  

            {{--
                 الصفحة الخاصة بعميل محدد
                 اي عن الضغط على كارد محدد
            --}}
            @elseif($id) 
                @foreach ($clients as $client)
                    {{---
                        ----------------------------------------------------------------
                        -------------------  جدول العميل  ----------------------------- 
                        ----------------------------------------------------------------
                    --}}

                    <div class="table-container">
                        <h2 class="table-title">
                            <div style="display: flex;gap: 10px; align-items: center;">
                                @php
                                    $image = $client->image?Storage::url($client->image->url):'../../../../app-assets/images/portrait/small/images.png'  ;
                                @endphp
                                <a href="{{$image}}">
                                    <img src="{{$image}}" alt="صورة العميل" style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover; margin-right: 10px;">
                                </a>
                                <div>
                                    {{$client->name}}
                                </div>
                            </div>
                        
                            <div>
                                @if(!$client->deleted_at)
                                    @if (Auth::user()->type=="admin")
                                    <form action="{{ route('clients.edit')}}" method="GET" style="display: inline;">
                                        <input type="hidden" name="id" value="{{$client->id}}">
                                        <button type="submit" class="btn" style="background-color: rgb(255, 255, 255); color: rgb(94, 94, 94); padding: 8px 12px; text-decoration: none; border-radius: 5px;"><i class="fas fa-edit"></i></button>
                                    </form>
                                    @endif
                                    <form action="{{ route('clients.soft.delete')}}" method="POST" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$client->id}}">
                                        <button type="submit" class="btn" style="background-color: rgb(255, 255, 255); color: rgb(94, 94, 94); padding: 8px 12px; text-decoration: none; border-radius: 5px;"><i class="fas fa-user-minus"></i></button>
                                    </form>
                                @else 
                                    <form action="{{ route('clients.restore')}}" method="POST" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$client->id}}">
                                        <button type="submit" class="btn" style="background-color: rgb(255, 255, 255); color: rgb(94, 94, 94); padding: 8px 12px; text-decoration: none; border-radius: 5px;"><i class="fas fa-undo-alt"></i></button>
                                    </form>
                                @endif    
                            </div>
                        </h2>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>البريد الاكتروني</th>
                                    <th>رقم الهاتف</th>
                                    @if($client->area)
                                        <th>المحافظة</th>
                                        <th>المنطقة</th>
                                        <th>الاشتراك</th>
                                    @endif
                                    <th>صلاحية الحساب</th>
                                    <th>الطلبات المتاحة</th>
                                </tr>
                            </thead>
                            <tbody>

                            
                            {{-- @dd($Monitors) --}}
                                {{-- @dd($monitor->user->name) --}}
                                <tr>
                                    <td>{{$client->email}}</td>
                                    <td>{{$client->mobile}}</td>
                                    @if($client->area)
                                        <td>{{$client->area->city->title}}</td>
                                        <td>{{$client->area->title}}</td>
                                        <td>{{$client->package?->title}}</td>
                                    @endif
                                    <td>{{$client->expire}} <br> ({{$client->active?"نشط":"غير نشط"}})</td>
                                    <td>{{$client->subscription_fees}}  </td>
                                    <td>
                                            
                                    </td>
                                    
                                    
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <br><br><br>
                    <div style="display: flex;flex-direction: row; gap:10px">
                        {{-- 
                            ----------------------------------------------------------------                        
                            -------------------  جدول العناوين  ---------------------------
                            ----------------------------------------------------------------
                        --}}
                        <div class="table-container" style="width: 50%">
                            <h2 class="table-title">
                                <div style="display: flex;gap: 10px; align-items: center;">
                                    عناوين العميل 
                                </div>
                                <div>
                                    {{-- Pan All --}}
                                    @if($client->addresses()->exists())
                                        <form action="{{Route("addresses.delete.all")}}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$client->id}}">
                                            <button type="submit" class="btn" style="background-color: rgb(255, 255, 255); color: rgb(94, 94, 94); padding: 8px 12px; text-decoration: none; border-radius: 5px;"><i class="fas fa-trash"></i></button>
                                        </form>
                                    @endif
                                    {{-- Add --}}
                                    <form action="{{ route('addresses.add')}}" method="GET" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$client->id}}">
                                        <button type="submit" class="btn" style="background-color: rgb(255, 255, 255); color: rgb(94, 94, 94); padding: 8px 12px; text-decoration: none; border-radius: 5px;"><i class="fas fa-add"></i></button>
                                    </form>
                                </div>
                            </h2>
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th> </th>
                                            <th> </th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        {{-- @dd($client->Alladdresses()) --}}
                                        @foreach ($client->Alladdresses() as $addresses)
                                        <tr>
                                            <td style="text-align: right;">
                                                @if($addresses->deleted_at)
                                                    <del>
                                                @endif
                                                    {{$addresses->title}}
                                                @if($addresses->deleted_at)
                                                    </del>
                                                @endif

                                            </td> 
                                            <td style="justify-content:left">
                                                <div style="position: relative; display: flex; justify-content:left">
                                                    <button onclick="toggleOptions(this)" style="background: none; border: none; cursor: pointer;">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <div class="options-menu">
                                                    @if(!$addresses->deleted_at)

                                                        {{-- Edit --}}
                                                        <form action="{{ route('addresses.edit')}}" method="GET" style="display: inline;">
                                                            @csrf
                                                            <input type="hidden" name="address_id" value="{{$addresses->id}}">
                                                            <input type="hidden" name="client_id" value="{{$client->id}}">
                                                            <button type="submit" id="edit"><i class="fas fa-edit"></i> تعديل</button>
                                                        </form>       
                                                        
                                                        {{-- Delete --}}
                                                        
                                                        <form action="{{ route('addresses.delete')}}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <input type="hidden" name="address_id" value="{{$addresses->id}}">
                                                            <input type="hidden" name="client_id" value="{{$client->id}}">
                                                            <button type="submit" id="edit"><i class="fas fa-trash"></i> حذف</button>
                                                        </form>                                                
                                                    @else
                                                        {{-- Restore --}}
                                                        <form action="{{ route('addresses.restore')}}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <input type="hidden" name="address_id" value="{{$addresses->id}}">
                                                            <input type="hidden" name="client_id" value="{{$client->id}}">
                                                            <button type="submit" id="edit"><i class="fas fa-undo-alt"></i> استعادة</button>
                                                        </form>                                                
                                                    @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        
                                        @endforeach
                                    </tbody>
                                </table>
                                <br><br><br>
                        </div>
                        {{-- 
                            ----------------------------------------------------------------                        
                            -------------------  جدول الطلبات  ---------------------------
                            ----------------------------------------------------------------
                        --}}                        
                        
                        <div class="table-container" style="width: 50%">
                            <h2 class="table-title">
                                <div style="display: flex;gap: 10px; align-items: center;">
                                    طلبات العميل 
                                </div>
                                <div>
                                    {{-- Edit --}}
                                    <form action="{{ route('addresses.edit')}}" method="GET" style="display: inline;">
                                        <input type="hidden" name="id" value="{{$client->id}}">
                                        <button type="submit" class="btn" style="background-color: rgb(255, 255, 255); color: rgb(94, 94, 94); padding: 8px 12px; text-decoration: none; border-radius: 5px;"><i class="fas fa-edit"></i></button>
                                    </form>
                                    {{-- Pan --}}
                                    @if($client->addresses()->exists())
                                        <form action="#" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$client->id}}">
                                            <button type="submit" class="btn" style="background-color: rgb(255, 255, 255); color: rgb(94, 94, 94); padding: 8px 12px; text-decoration: none; border-radius: 5px;"><i class="fas fa-trash"></i></button>
                                        </form>
                                    @endif
                                    {{-- Add --}}
                                    <form action="{{ route('addresses.add')}}" method="GET" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$client->id}}">
                                        <button type="submit" class="btn" style="background-color: rgb(255, 255, 255); color: rgb(94, 94, 94); padding: 8px 12px; text-decoration: none; border-radius: 5px;"><i class="fas fa-add"></i></button>
                                    </form>
                                </div>
                            </h2>
                            @if($client->addresses()->exists())
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th> </th>
                                            <th> </th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($client->addresses as $addresses)
                                        <tr>
                                            <td style="text-align: right;">{{$addresses->title}}</td> 
                                            <td style="justify-content:left">
                                                <div style="position: relative; display: flex; justify-content:left">
                                                    <button onclick="toggleOptions(this)" style="background: none; border: none; cursor: pointer;">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <div class="options-menu">
                                                        <form action="{{ route('addresses.edit')}}" method="GET" style="display: inline;">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{$client->id}}">
                                                            <button type="submit" id="edit"><i class="fas fa-edit"></i> تعديل</button>
                                                        </form>                                                
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        @endforeach
                                    </tbody>
                                </table>
                                <br><br><br>
                            @endif
                        </div>
                    </div>

                @endforeach
            @endif 
        </div>
    </div>
</div>
</div>

@include('panel.static.footer')