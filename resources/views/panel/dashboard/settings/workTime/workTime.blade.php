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
                <div style="background-color: #a7eba4; border-right: 6px solid #12c20c; padding: 20px; border-radius: 10px;">
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
                        <h1 style="margin: 0;">اوقات الدوام  </h1>
                    </div>
                </div>
                <br>
                <hr>
                <br>
                <table>
                    <thead>
                        <tr>
                            <th>اليوم</th>
                            <th>بداية الدوام</th>
                            <th>نهاية الدوام</th>
                            <th>تعديل</th>
                        </tr>
                    </thead>
                    <tbody>

                        
                        @foreach ($WorkTimes as $WorkTime)
                                                
                            <tr>
                                <td>{{$WorkTime->day}}</td>
                                <td>{{$WorkTime->from_time?$WorkTime->from_time:'-'}}</td>
                                <td>{{$WorkTime->to_time?$WorkTime->to_time:'-'}}</td>
                                <td>
      
                                    <div style="display: inline-block;">
                                        <form action="{{ route('work.times.edit')}}" method="get" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$WorkTime->id}}">
                                            <button type="submit" id="edit" style="background: none; border: none; color: rgb(17, 75, 161); cursor: pointer;">تعديل</button>
                                        </form>
                                        <span class="icon" onclick="deleteRow()"><i class="fas fa-edit"></i></span>
                                    </div>
                                    <div style="display: inline-block;">
                                        <form action="{{ route('work.times.soft.delete')}}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$WorkTime->id}}">
                                            <button type="submit" id="delete" style="background: none; border: none; color: rgb(161, 17, 17); cursor: pointer;">الغاء الاوقات</button>
                                        </form>
                                        <span class="icon" onclick="deleteRow()"><i class="fas fa-trash"></i></span>
                                    </div>
                                </td> 
                                
                            </tr>
                            
                        @endforeach
                    </tbody>
                </table>
                
                
                {{-- <button type="button" class="btn rounded-button" style="color: black; padding: 10px 20px; background-color: #bccad8; border: none; border-radius: 5px;" onclick="history.back()">تراجع</button> --}}
                <button type="button" class="btn rounded-button" style="background-color: #bccad8;color: black; padding: 7.0px 20px;" onclick='window.location.href="{{ route("settings.show") }}";'>تراجع</button>

            </div>
            </div>
        </div>
    </div>

@include('panel.static.footer')