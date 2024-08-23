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
                function confirmDelete(cityId) {
                    if (confirm(' هل أنت متأكد أنك تريد حذف هذه المدينة؟ سيتم حذف جميع المناطق في هذه المدينة مع المشرفين والمراسلين')) {
                        document.getElementById('delete-form-' + cityId).submit();
                    }
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
                        <h1 style="margin: 0;">المدن المخدمة</h1>
                    </div>
                    @if (Auth::User()->type == "admin")
                    <a href="{{ route('cities.add') }}" id="add-city" class="btn btn-primary rounded-button" style="background-color: rgb(23, 54, 139); color: white; padding: 8px 12px; text-decoration: none; border-radius: 5px;">إضافة مدينة</a>
                    @endif
                </div>
                <br>
                <hr>
                <br> 


                <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                    <form method="GET" action="{{ route('cities.show') }}" style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
                        <div style="position: relative; display: flex; align-items: center; width: 200px;">
                            <input type="text" name="search_name" id="search_name" value="{{ $searchName }}" placeholder="اسم المدينة" style="padding: 5px 40px 5px 10px; width: 100%; font-size: 0.875rem; border-radius: 5px; border: 1px solid #ccc;">
                            <button type="submit" class="btn btn-primary rounded-button" style="position: absolute; left: 0; top: 0; bottom: 0; padding: 5px 10px; font-size: 0.875rem; background-color: rgb(23, 54, 139); color: white; border-radius: 5px; border: none;">بحث</button>
                        </div>
                        @if ($searchName) 
                        <button type="button" class="btn btn-primary rounded-button" style="padding: 5px 10px; font-size: 0.875rem; background-color: rgb(23, 54, 139); color: white; border-radius: 5px; border: none;" onclick="document.getElementById('search_name').value=''; this.form.submit();">إلغاء</button>
                        @endif
                    </form>
                                       
                <table>
                    <thead>
                        <tr>
                            <th>المدينة</th>
                            <th>عدد المناطق</th>
                            <th>عدد المشرفين</th>
                            <th>عدد عمال التوصيل</th>
                            <th>عدد المشتركين</th>
                            @if (Auth::User()->type == "admin")
                                <th>العمليات</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>

                        
                        @foreach ($cities as $city)
                                                
                            <tr>
                                <td>{{$city->title}}</td>
                                <td>{{$city->count_of_areas}}</td>
                                <td>{{$city->count_of_monitors}}</td>
                                <td>{{$city->count_of_delivers}}</td>
                                <td>{{$city->count_of_clients}}</td>
                                @if (Auth::User()->type == "admin")
                                <td>
                                    {{-- <div style="display: inline-block; margin-right: 10px;">
                                        <a href="" id="edit">تعديل</a>
                                            <span class="icon" onclick="edit()"><i class="fas fa-edit"></i></span>
                                    </div> --}}
                                    <div style="display: inline-block; margin-right: 10px;">
                                        <form action="{{ route('cities.show.city')}}" method="GET" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$city->id}}">
                                            <input type="hidden" name="route" value="cities.show">
                                            <button type="submit" id="edit" style="background: none; border: none; color: rgb(59, 98, 206); cursor: pointer;">تعديل</button>
                                        </form>
                                        <span class="icon" onclick="edit()"><i class="fas fa-edit"></i></span>
                                    </div>
                                    
                                    <div style="display: inline-block;">
                                        <form id="delete-form-{{ $city->id }}" action="{{ route('cities.soft.delete') }}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $city->id }}">
                                            <input type="hidden" name="route" value="cities.show">
                                        </form>
                                        <button type="submit" id="delete" onclick="confirmDelete({{ $city->id }})" style="background: none; border: none; color: rgb(206, 59, 59); cursor: pointer;">حذف</button>
                                        <span class="icon" onclick="confirmDelete({{ $city->id }})" onclick="deleteRow()" style="cursor: pointer;">
                                            <i class="fas fa-trash"></i>
                                        </span>
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