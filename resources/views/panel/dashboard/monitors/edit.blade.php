@include('panel.static.header')
@include('panel.static.main')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
            <div class="col-12">
                <br><br>
                <h1>تعديل المشرف {{$monitor->user->name}}</h1>
            </div>
        </div>
        <div class="content-body">
            <br><br>
            @if(session()->has("error"))
                <div style="background-color: #ffb3b3; border-right: 6px solid #c20c0c; padding: 20px; border-radius: 10px;">
                    <p style="font-size: 20px; margin: 0;">
                            {{session("error")}}
                    </p>
                </div> 
                <br><br><br>
            @endif
            
            <!-- Begin forms section -->
            <div style="display: flex; flex-direction: column; gap: 20px; width: 50%; margin: inherit;">
                
                <!-- Form 1: POST request -->
                <form id="myForm" action="{{Route('monitors.update')}}" method="post" style="display: flex; flex-direction: column; gap: 10px;">
                    @csrf
                    @error('name')
                         <p style="color: red" > * {{$message}}</p>
                    @enderror
                    <input type="text" name='name' value="{{old('name')?old('name'):$monitor->user->name}}" style="padding: 10px; width: 100%; border-radius: 5px; border: 1px solid #ccc;">
                    <input type="hidden" name="id" value="{{$monitor->id}}">
                    <input type="hidden" name="city_id" value="{{$selectedCityId}}">
                    <input type="hidden" name="area_id" value="{{$selectedAreaId}}">
                </form>
                
                <!-- Form 2: City Select -->
                <form method="get" action="{{ route('monitors.edit') }}" style="display: flex; flex-direction: column; gap: 10px;">
                    @error('city_id')
                    <p style="color: red" >* {{$message}}</p>
                    @enderror

                    <input type="hidden" name="id" value="{{$monitor->id}}">
                    <label for="city_id" style="font-size: 1rem;">حدد مدينة:</label>
                    <select name="city_id" id="city_id" onchange="this.form.submit()" style="padding: 10px; width: 100%; border-radius: 5px; border: 1px solid #ccc;">
                        <option value="">حدد مدينة</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}" {{ $selectedCityId == $city->id ? 'selected' : '' }}>
                                {{ $city->title }}
                            </option>
                        @endforeach
                    </select>
                </form>
                
                <!-- Form 3: Area Select -->
                <form method="GET" action="{{ route('monitors.edit') }}" style="display: flex; flex-direction: column; gap: 10px;">
                    @error('area_id')
                     <p style="color: red" > * {{$message}}</p>
                    @enderror

                    <input type="hidden" name="id" value="{{ $monitor->id }}">
                    <input type="hidden" name="city_id" value="{{ $selectedCityId }}">
                    <label for="area_id" style="font-size: 1rem;">حدد منطقة:</label>
                    <select name="area_id" id="area_id" onchange="this.form.submit()" style="padding: 10px; width: 100%; border-radius: 5px; border: 1px solid #ccc;">
                        <option value="">حدد منطقة</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}" {{ $selectedAreaId == $area->id ? 'selected' : '' }}>
                                {{ $area->title }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" onclick="document.getElementById('myForm').submit();" class="rounded-button" style="padding: 10px; width: 50%; color: white; background-color: #007bff; border: none; border-radius: 5px;">تعديل</button>
                    <button type="submit" onclick="history.back()" class="rounded-button" style="padding: 10px; width: 50%; color: black; background-color: white; border: 1px solid #ccc; border-radius: 5px;">الغاء</button>
                </div>
                
            </div>
            <!-- End forms section -->
            
        </div>
    </div>
</div>
<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

@include('panel.static.footer')
