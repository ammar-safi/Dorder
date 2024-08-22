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
            </script>

            <style>
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
                <div style="background-color: #ffb3b3; border-right: 6px solid #c20c0c; padding: 20px; border-radius: 10px;">
                    <p style="font-size: 20px; margin: 0;">
                        <strong>
                            {{session("ersuccessror")}}
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
                    <div>

                        <a href="{{ route('monitors.add') }}" id="add-monitor" class="btn btn-primary rounded-button" style="background-color: rgb(23, 54, 139); color: white; padding: 8px 12px; text-decoration: none; border-radius: 5px;">إضافة مشرف</a>
                        <a href="{{Route("monitors.edit.area")}}" id="add-monitor" class="btn btn-primary rounded-button" style="background-color: rgb(23, 54, 139); color: white; padding: 8px 12px; text-decoration: none; border-radius: 5px;"> تعديل المناطق</a>
                    </div>
                </div>
                <br>
                <hr>
                <br>


                <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                    <!-- نموذج البحث عن المشرف بالاسم -->
                    <form method="GET" action="{{ route('monitors.show') }}" style="margin-bottom: 10px; display: flex; align-items: center; gap: 0;">
                        <div style="position: relative; display: flex; align-items: center; width: 200px;">
                            <input type="text" name="search_name" id="search_name" value="{{ $searchName }}" placeholder="اسم المشرف" style="padding: 5px 40px 5px 10px; width: 100%; font-size: 0.875rem; border-radius: 5px; border: 1px solid #ccc;">
                            <button type="submit" class="btn btn-primary rounded-button" style="position: absolute; left: 0; top: 0; bottom: 0; padding: 5px 10px; font-size: 0.875rem; background-color: rgb(23, 54, 139); color: white; border-radius: 5px; border: none;">بحث</button>
                        </div>
                    </form>
                    
                    <!-- نموذج اختيار المدينة -->
                    <form method="GET" action="{{ route('monitors.show') }}" style="margin-bottom: 10px; display: flex; align-items: center; gap: 5px;">
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
                    
                    <!-- نموذج اختيار المنطقة (يظهر فقط إذا تم اختيار مدينة) -->
                    @if ($selectedCityId)
                        <form method="GET" action="{{ route('monitors.show') }}" style="margin-bottom: 10px; display: flex; align-items: center; gap: 5px;">
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
                
            </div>
                



                
                @if ($selectedCityId || $selectedAreaId || $searchName)
                <table>
                    <thead>
                        <tr>
                            <th>المشرف</th>
                            <th>المنطقة</th>
                            <th>المحافظة</th>
                            <th>البريد الاكتروني</th>
                            <th>رقم الهاتف</th>
                            {{-- <th>تاريخ الانضمام</th>
                            <th>تاريخ التعديل</th> --}}
                            {{-- <th>الحالة</th> --}}
                            <th>العمليات</th>
                        </tr>
                    </thead>
                    <tbody>

                        
                        @foreach ($monitors as $monitor)
                        {{-- @dd($Monitors) --}}
                            {{-- @dd($monitor->monitor->name) --}}
                            <tr>
                                <td>{{$monitor->monitor->name}}</td>
                                <td>{{$monitor->area->title}}</td>
                                <td>{{$monitor->area->city()->withTrashed()->first('title')->title}}</td>
                                <td>{{$monitor->monitor->email}}</td>
                                <td>{{$monitor->monitor->mobile}}</td>
                                {{-- <td>{{$monitor->monitor->created_at}}</td>
                                <td>{{$monitor->monitor->updated_at}}</td> --}}
                                {{-- <td style="text-align:right">{{  ['🔴 غير نشط','🟢 نشط']  [$monitor->monitor->active]}} </td> --}}
                                <td style="font-size:2ch" >
                                    </div>
                                    @if (Auth::user()->type == "admin")
                                    <div style="display: inline-block;">
                                        <form action="{{ route('monitors.edit')}}" method="GET" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$monitor->id}}">
                                            <input type="hidden" name="route" value="cities.show">
                                            <button type="submit" id="delete" style="background: none; border: none; color: rgb(42, 101, 177); cursor: pointer;">تعديل</button>
                                        </form>
                                        <span class="icon" onclick="deleteRow()"><i class="fas fa-edit"></i></span>
                                    </div>
                                    <div style="display: inline-block;">
                                        <form action="{{ route('monitors.soft.delete')}}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$monitor->id}}">
                                            <input type="hidden" name="route" value="cities.show">
                                            <button type="submit" id="delete" style="background: none; border: none; color: rgb(161, 17, 17); cursor: pointer;">حذف</button>
                                        </form>
                                        <span class="icon" onclick="deleteRow()"><i class="fas fa-trash"></i></span>
                                    </div>
                                    @endif
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