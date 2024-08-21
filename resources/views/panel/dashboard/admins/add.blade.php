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
                <h1>إضافة مدير جديد</h1>
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
            <div style="background-color: #f5e8cc; border-right: 6px solid #e0a627; padding: 20px; border-radius: 10px;">
                <strong>
                     ملاحظة ⚠
                </strong>

                {{-- <p style="font-size: 20px; margin: 0;"> --}}
                عند اضافة حساب ذو صلاحيات مدير سيكون قادر على الوصول الى جميع بيانات النظام والتعديل عليها 
                {{-- </p> --}}
            </div> 
            @if(session()->has("error"))
                <br><br>
                <div style="background-color: #ffb3b3; border-right: 6px solid #c20c0c; padding: 20px; border-radius: 10px;">
                    <strong>
                         يبدو ان هناك مشكلة , حاول مرة اخرى  ⚠
                    </strong>
                    <p style="font-size: 20px; margin: 0;">
                            {{session("error")}}
                    </p>
                </div> 
            @endif
            <div class="row">
                <div class="col-12"> 
                    <form action="{{ route('admins.store') }}" method="POST"> 
                        @csrf
                        <div class="form-group">
                            <label for="name">الاسم:</label>
                            @error('name')
                            <div style="color: #c20c0c" >    
                                * {{$message}}
                                <br><br>
                            </div>
                            @enderror
                            <input type="text" class="form-control" id="name" name="name" placeholder="أدخل الاسم" value="{{old('name')}}">
                        </div>
                        <div class="form-group">
                            <label for="email">البريد الإلكتروني:</label>
                            @error('email')
                            <div style="color: #c20c0c" >    
                                * {{$message}}
                                <br><br>
                            </div>
                            @enderror
                            <input type="email" class="form-control" id="email" name="email" placeholder="أدخل البريد الإلكتروني" value="{{old('email')}}">
                        </div>
                        <div class="form-group">
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
                          
                        <div class="form-group">
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
                          
                        
                        <div class="form-group">
                            <label for="mobile">رقم الهاتف:</label>
                            @error('mobile')
                            <div style="color: #c20c0c" >    
                                * {{$message}}
                                <br><br>
                            </div>
                            @enderror
                            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="أدخل رقم الهاتف"value="{{old('mobile')}}">
                        </div>
                        {{-- <div>
                            <input id='active' type="checkbox" name="active" value="1">
                            <label for='active'><h6>حساب نشط</h6></label>
                        </div> --}}
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
