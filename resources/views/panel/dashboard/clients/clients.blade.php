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
                    const optionsMenu = button.nextElementSibling;
                    optionsMenu.style.display = optionsMenu.style.display === 'block' ? 'none' : 'block';
                }

                document.addEventListener('click', function(event) {
                    const isClickInside = event.target.closest('.options-menu') || event.target.closest('button');
                    if (!isClickInside) {
                        document.querySelectorAll('.options-menu').forEach(function(menu) {
                            menu.style.display = 'none';
                        });
                    }
                });

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
                .options-menu i {
                    margin-right: 10px;

                }
                .options-menu button:hover {
                    background-color: #f1f1f1;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px auto;
                }

                th,
                td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: center;
                }

                th {
                    background-color: #f2f2f2;
                }

                .icon {
                    cursor: pointer;
                    margin: 0 5px;
                    font-size: 20px;
                    color: #333;
                }

                .icon:hover {
                    color: #007BFF;
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
                        <h1 style="margin: 0;">العملاء </h1>
                    </div>

                    <div>
                        <a href="{{ "#" }}" id="add-deliver" class="btn btn-primary rounded-button" style="background-color: rgb(23, 54, 139); color: white; padding: 8px 12px; text-decoration: none; border-radius: 5px;">إضافة عميل </a>
                    </div>
                   
                </div>
                <br>
                <hr>
                <br>


                <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                    <form method="GET" action="{{ route('clients.show') }}" style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
                        @if($selectedAreaId)
                            <input type="hidden" name="area_id" value="{{$selectedAreaId}}">
                        @endif
                        @if($selectedCityId)
                            <input type="hidden" name="city_id" value="{{$selectedCityId}}">
                        @endif
                        <div style="position: relative; display: flex; align-items: center; width: 200px;">
                            <input type="text" name="search_name" id="search_name" value="{{ $searchName }}" placeholder="اسم العميل" style="padding: 5px 40px 5px 10px; width: 100%; font-size: 0.875rem; border-radius: 5px; border: 1px solid #ccc;">
                            <button type="submit" class="btn btn-primary rounded-button" style="position: absolute; left: 0; top: 0; bottom: 0; padding: 5px 10px; font-size: 0.875rem; background-color: rgb(23, 54, 139); color: white; border-radius: 5px; border: none;">بحث</button>
                        </div>
                        @if ($searchName) 
                        <button type="button" class="btn btn-primary rounded-button" style="padding: 5px 10px; font-size: 0.875rem; background-color: rgb(23, 54, 139); color: white; border-radius: 5px; border: none;" onclick="document.getElementById('search_name').value=''; this.form.submit();">إلغاء</button>
                        @endif
                    </form>
                    
                    <form method="GET" action="{{ route('clients.show') }}" style="margin-bottom: 10px; display: flex; align-items: center; gap: 5px;">
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
                    
                    @if ($selectedCityId)
                        <form method="GET" action="{{ route('clients.show') }}" style="margin-bottom: 10px; display: flex; align-items: center; gap: 5px;">
                            <input type="hidden" name="search_name" value="{{$searchName}}">
                            <input type="hidden" name="city_id" value="{{ $selectedCityId }}">
                            <label for="area_id" style="margin: 0; font-size: 0.875rem;">حدد منطقة:</label>
                            <select name="area_id" id="area_id" onchange="this.form.submit()" style="padding: 5px; width: 150px; font-size: 0.875rem; border-radius: 5px;">
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


                        <div>
                            <input id="deleted" onchange="this.form.submit()" type="checkbox" value="deleted">
                            <label for="deleted">المحظورين</label>    
                        </div>
                    </form>
                </div>
            </div>
                
            
            
            
            
            @if ($selectedCityId || $selectedAreaId || $searchName)
            <table>
                    <thead>
                        <tr>
                            <th>الصورة الشخصية</th>
                            <th>الاسم</th>
                            <th>البريد الاكتروني</th>
                            <th>رقم الهاتف</th>
                            <th>المحافظة</th>
                            <th>المنطقة</th>
                            <th>الاشتراك</th>
                            <th>صلاحية الحساب</th>
                            <th>الطلبات المتاحة</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                        
                        @foreach ($clients as $client)
                        {{-- @dd($Monitors) --}}
                            {{-- @dd($monitor->user->name) --}}
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <!-- إضافة صورة العميل -->
                                        @php
                                            $image = $client->image?Storage::url($client->image->url):'../../../../app-assets/images/portrait/small/images.png'  ;
                                        @endphp
                                        <a href="{{$image}}">
                                            <img src="{{$image}}" alt="صورة العميل" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px;">
                                        </a>
                                    </div>
                                </td>                                
                                <td>{{$client->name}}</td>
                                <td>{{$client->email}}</td>
                                <td>{{$client->mobile}}</td>
                                <td>{{$client->area?->city->title}}</td>
                                <td>{{$client->area?->title}}</td>
                                <td>{{$client->package?->title}}</td>
                                <td>{{$client->expire}} <br> ({{$client->active?"نشط":"غير نشط"}})</td>
                                <td>{{$client->subscription_fees}}  </td>
                                <td>
                                    <div style="position: relative; display: flex; justify-content: center;">
                                        <button onclick="toggleOptions(this)" style="background: none; border: none; cursor: pointer;">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="options-menu" style="display: none; position: absolute; top: -60px; right: -100px; background-color: #f9f9f9; border: 1px solid #ccc; border-radius: 4px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); z-index: 100;">
                                            @if (Auth::user()->type=="admin")
                                            <form action="{{ route('clients.edit')}}" method="GET" style="display: block; margin: 0;">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$client->id}}">
                                                <button type="submit" style="background: none; border: none; color: rgb(4, 47, 139); padding: 8px 12px; width: 100%; text-align: left;">
                                                    تعديل <i class="fas fa-edit"></i>
                                                </button>
                                            </form>
                                            @endif
                                            <form action="{{ route('clients.soft.delete')}}" method="POST" style="display: block; margin: 0;">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$client->id}}">
                                                <button type="submit" style="background: none; border: none; color: rgb(161, 17, 17); padding: 8px 12px; width: 100%; text-align: left;">
                                                     حظر <i class="fas fa-user-minus"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>     
                                </td>
                                
                                
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