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
                <h1>تعديل عامل التوصيل {{$deliver->name}}</h1>
            </div>
        </div>
        <script>
            function sendName() {
                const name = document.getElementById('nameInput').value;
                document.getElementById('nameCityHidden').value = name;  
                document.getElementById('nameAreaHidden').value = name;  
            }
            function sendEmail() {
                const email = document.getElementById('emailInput').value;
                document.getElementById('emailCityHidden').value = email;  
                document.getElementById('emailAreaHidden').value = email;  
            }
            function sendMobile() {
                const mobile = document.getElementById('mobileInput').value;
                document.getElementById('mobileCityHidden').value = mobile;  
                document.getElementById('mobileAreaHidden').value = mobile;  
            }
        </script>
        <div class="content-body">
            <br><br>
            @if(session()->has("error"))
                <div style="background-color: #ffe8e8; border-right: 6px solid #c20c0c; padding: 20px; border-radius: 10px;">
                    <p style="font-size: 20px; margin: 0;">
                        {{session("error")}}
                    </p>
                </div> 
                <br><br><br>
            @endif
            
            <!-- Begin forms section -->
            <div style="display: flex; flex-direction: column; gap: 20px; width: 100%; margin: inherit;">
                
                <!-- Form 1: POST request -->
                <form id="myForm" action="{{Route('delivers.update')}}" method="post" style="display: flex; flex-direction: column; gap: 10px;">
                    @csrf
                    <input type="hidden" name="id" value="{{$deliver->id}}">
                    <input type="hidden" name="city_id" value="{{$selectedCityId}}">
                    <input type="hidden" name="area_id" value="{{$selectedAreaId}}">

                    <label for="nameInput">تعديل الاسم </label>
                    @error('name')
                         <p style="color: red" > * {{$message}}</p>
                    @enderror
                    <input type="text" oninput="sendName()" id="nameInput" name='name' value="{{old('name')?old('name'):($name?$name:$deliver->name)}}" style="padding: 10px; width: 100%; border-radius: 5px; border: 1px solid #ccc;">
                    @if (Auth::User()->type == "admin")
                            <label for="mobileInput">تعديل رقم الهاتف </label>
                            @error('mobile')
                            <p style="color: red" > * {{$message}}</p>
                            @enderror
                            <input type="text" oninput="sendMobile()" id="mobileInput" name='mobile' value="{{old('mobile')?old("mobile"):($mobile?$mobile:$deliver->mobile)}}" style="padding: 10px; width: 100%; border-radius: 5px; border: 1px solid #ccc;">
                            
                            <label for="emailInput">تعديل البريد الالكتروني </label>
                            @error('email')
                            <p style="color: red" > * {{$message}}</p>
                            @enderror
                            <input type="text" oninput="sendEmail()" id="emailInput" name='email' value="{{old("email")?old("email"):($email?$email:$deliver    ->email)}}" style="padding: 10px; width: 100%; border-radius: 5px; border: 1px solid #ccc;">
                    @endif
                </form>
                <div style="display: flex;flex-direction:initial; gap:10px">

                <!-- Form 2: City Select -->
                <form method="get" action="{{ route('delivers.edit') }}" style="width: 50%; gap: 10px;">
                    <input type="hidden" name="id" value="{{$deliver->id}}">
                    <input type="hidden" id="nameCityHidden" name="name" value="{{old('name')?old('name'):($name?$name:'')}}">
                    <input type="hidden" id="emailCityHidden" name="email" value="{{old('email')?old("email"):($email?$email:'')}}">
                    <input type="hidden" id="mobileCityHidden" name="mobile" value="{{old('mobile')?old("mobile"):($mobile?$mobile:"")}}">
                   
                    
                    <label for="city_id" id="" style="font-size: 1rem;">حدد مدينة:</label>
                    <select name="city_id" id="city_id" onchange="this.form.submit()" style="padding: 10px; width: 100%; border-radius: 5px; border: 1px solid #ccc;">
                        <option>حدد مدينة</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}" {{ $selectedCityId == $city->id ? 'selected' : '' }}>
                                {{ $city->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('city_id')
                    <p style="color: red" >* {{$message}}</p>
                    @enderror
                </form>
                
                <!-- Form 3: Area Select -->
                <form method="GET" action="{{ route('delivers.edit') }}" style="width:50% ; gap: 10px;">
                    <input type="hidden" name="id" value="{{$deliver->id}}">
                    <input type="hidden" name="city_id" value="{{ $selectedCityId }}">
                    <input type="hidden" id="nameAreaHidden" name="name" value="{{old('name')?old('name'):($name?$name:'')}}">
                    <input type="hidden" id="emailAreaHidden" name="email"value="{{old('email')?old("email"):($email?$email:'')}}">
                    <input type="hidden" id="mobileAreaHidden" name="mobile" value="{{old('mobile')?old("mobile"):($mobile?$mobile:"")}}">

                    
                    <label for="area_id" style="font-size: 1rem;">حدد منطقة:</label>
                    <select name="area_id" id="area_id" onchange="this.form.submit()" style="padding: 10px; width: 100%; border-radius: 5px; border: 1px solid #ccc;">
                        <option value="">حدد منطقة</option>
                        @foreach ($areas as $area)
                        <option value="{{ $area->id }}" {{ $selectedAreaId == $area->id ? 'selected' : '' }}>
                            {{ $area->title }}
                        </option>
                        @endforeach
                    </select>
                    @error('area_id')
                     <p style="color: red" > * {{$message}}</p>
                    @enderror
                </form>
                </div>


                <div style="display: flex; gap: 10px;width: 25%">
                    <button type="submit" onclick="document.getElementById('myForm').submit();" class="rounded-button" style="padding: 10px; width: 50%; color: white; background-color: #007bff; border: none; border-radius: 5px;">تعديل</button>
                    <button type="button" class="btn rounded-button" style="background-color: #ccc;width: 50%;color: black; padding: 7.0px 20px;" onclick='window.location.href="{{ route("delivers.show") }}";'>الغاء</button>
                    
                </div>
                
            </div>
            <!-- End forms section -->
            
        </div>
    </div>
</div>
<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

@include('panel.static.footer')
