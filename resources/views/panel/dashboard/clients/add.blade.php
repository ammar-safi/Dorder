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
                <div style="display: flex; gap: inherit; align-items: center;">
                    <h1>اضافة عميل</h1>
                    <!-- تعديل الصورة -->

                    <div style="display: grid; justify-items: center; align-items: center;position: absolute;
                    top: 79%;
                    left: 30px;
                    transform: translateY(-50%);
                    cursor: pointer;">
                        @php
                            // $image = $client->image?Storage::url($client->image->url):'../../../../app-assets/images/portrait/small/images.png'  ;
                        @endphp
                        <a id="clientShowImage" href="../../../../app-assets/images/portrait/small/images.png">                            
                            <img id="clientImage" src="../../../../app-assets/images/portrait/small/images.png" alt="صورة العميل" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                        </a>
                        <label for="imageUpload" style="cursor: pointer; margin-top: 10px;">تعديل</label>
                    </div>
                    
                </div>
                
            </div>
        <style>
         .password-input {
        position: relative;
        }

        .password-toggle {
        position: absolute;
        top: 50%;
        left: 10px; /* تم تغيير right إلى left */ 
        transform: translateY(-50%);
        cursor: pointer;
        }


        </style>
            <script>
                function previewImage(event) {
                    var reader = new FileReader();
                    reader.onload = function(){
                        var output = document.getElementById('clientImage');
                        var a = document.getElementById('clientShowImage');
                        output.src = reader.result;
                        a.href = reader.result;
                    }
                    reader.readAsDataURL(event.target.files[0]);
                }

                function togglePasswordVisibility(inputId) {
                    var passwordInput = document.getElementById(inputId);
                    if (passwordInput.type === "password") {
                        passwordInput.type = "text";
                    } else {
                        passwordInput.type = "password";
                    }
                }
            </script>
        </div>
        <div class="content-body">
            <br><br>
            
            @if(session()->has("error"))
                <br><br>
                <div style="background-color: #ffb3b3; border-right: 6px solid #c20c0c; padding: 20px; border-radius: 10px;">
                    <strong>
                        ⚠ {{session("error")}}
                    </strong>
                    {{-- <p style="font-size: 20px; margin: 0;">
                    </p> --}}
                </div> 
            @endif  

            @error('profile_image')
            <br><br>
            <div style="background-color: #fce5d3; border-right: 6px solid #c55c05; padding: 20px; border-radius: 10px;">
                <strong>
                    ⚠ تنبيه   : {{$message}}
                </strong>
            </div> 
            @enderror

            
            <div class="row">
                <div class="col-12"> 
                    <form action="{{ route('clients.store') }}" method="POST"  style="width: 100%;" enctype="multipart/form-data"> 
                        @csrf
                        <input type="file" id="imageUpload" name="profile_image" accept="image/*" style="display: none;" onchange="previewImage(event)">

                        <div style="display: flex;gap:10px  ;flex-direction:icnitial;">
                        <div class="form-group" style="width: 50%;">
                            <label for="name">الاسم:</label>
                            @error('name')
                            <div style="color: #c20c0c" >    
                                * {{$message}}
                                <br><br>
                            </div>
                            @enderror
                            <input type="text" class="form-control" id="name" name="name" placeholder="أدخل الاسم" value="{{old('name')}}">
                        </div>
                        <div class="form-group" style="width: 50%;">
                            <label for="email">البريد الإلكتروني:</label>
                            @error('email')
                            <div style="color: #c20c0c" >    
                                * {{$message}}
                                <br><br>
                            </div>
                            @enderror
                            <input type="email" class="form-control" id="email" name="email" placeholder="أدخل البريد الإلكتروني" value="{{old('email')}}">
                        </div>
                        </div>
                     
                        <div style="display: flex;gap:10px  ;flex-direction:icnitial;">
                          
                        
                        <div class="form-group" style="width: 40%;">
                            <label for="mobile">رقم الهاتف:</label>
                            @error('mobile')
                            <div style="color: #c20c0c" >    
                                * {{$message}}
                                <br><br>
                            </div>
                            @enderror
                            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="أدخل رقم الهاتف"value="{{old('mobile')}}">
                        </div>
                        <div class="form-group" style="width: 30%;">
                            <label for="password"> كلمة المرور:</label>
                            @error('password')
                            <div style="color: #c20c0c" >    
                                * {{$message}}
                                <br><br>
                            </div>
                            @enderror
                            <div class="password-input">
                                <input type="password" class="form-control" id="password" name="password" placeholder=" كلمة المرور" value="{{old('password')}}">
                                <span class="password-toggle" onclick="togglePasswordVisibility('password')">
                                    <i class="fa fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        <div class="form-group" style="width: 30%">
                            <label for="password_confirmation">تأكيد كلمة المرور:</label>
                            @error('password_confirmation')
                            <div style="color: #c20c0c" >    
                                * {{$message}}
                                <br><br>
                            </div>
                            @enderror
                            <div class="password-input">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="تأكيد كلمة المرور" value="{{old('password_confirmation')}}">
                            <span class="password-toggle" onclick="togglePasswordVisibility('password_confirmation')">
                                <i class="fa fa-eye"></i>
                            </span>
                            </div>
                        </div>
                        </div>
                        <div style="display: flex;gap:10px  ;flex-direction:icnitial;">

                        <div class="form-group" style="width: 33%">
                            <label for="subscription_fees">عدد الطلبات المتاحة :</label>
                            @error('subscription_fees')
                            <div style="color: #c20c0c" >    
                                * {{$message}}
                                <br><br>
                            </div>
                            @enderror
                            <input type="text" class="form-control" id="subscription_fees" name="subscription_fees" placeholder="أدخل عدد الطلبات"value="{{old('subscription_fees')}}">
                        </div>
                        <div class="form-group" style="width: 33%">
                            <label for="expire">تاريخ انتهاء صلاحية الحساب :</label>
                            @error('expire')
                            <div style="color: #c20c0c" >    
                                * {{$message}}
                                <br><br>
                            </div>
                            @enderror
                            <input type="date" class="form-control" id="expire" name="expire" placeholder="أدخل رقم "value="{{old('expire')}}">
                        </div>

                        <div style="width: 33%">
                            @error('area_id')
                            <div style="color: #c20c0c" >    
                                * {{$message}}
                                <br><br>
                            </div>
                            @enderror
                            <label for="">اختر المنطقة التي يعيش فيها العميل </label>
                            <select name="area_id" class="form-control rounded-input" style="margin-left: 10px;padding-left:5.0rem">
                                <option value="">اختر المنطقة</option>
                                @foreach ($collection as $cityName => $areas)
                                    <optgroup label="{{ $cityName }}">
                                        @foreach ($areas as $area)
                                            @php
                                                $Area = old('area_id')
                                            @endphp
                                            <option value="{{ $area->id }}" {{($Area==$area->id)?'selected':''}} >{{ $area->title }} </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>                  
                        </div>
                        <div>
                            <br>
                            <button type="submit" class="btn btn-primary">اضافة</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

@include('panel.static.footer')
