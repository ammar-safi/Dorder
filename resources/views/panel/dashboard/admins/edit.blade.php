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
                    <h1>تعديل المدير {{$admin->name}}</h1>
                    <!-- تعديل الصورة -->
{{-- 
                    <div style="display: grid; justify-items: center; align-items: center; margin-right: 400px;">
                        @php
                            $image = $admin->image?Storage::url($admin->image->url):'../../../../app-assets/images/portrait/small/images.png'  ;
                        @endphp
                        <a  href="{{ $image }}">
                            <!-- صورة المدير -->
                            
                            <img id="adminImage" src="{{ $image }}" alt="صورة المدير" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                        </a>
                        <label for="imageUpload" style="cursor: pointer; margin-top: 10px;">تعديل</label>
                    </div> --}}
                    
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
                        var output = document.getElementById('adminImage');
                        output.src = reader.result;
                    }
                    reader.readAsDataURL(event.target.files[0]);
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
                    <form action="{{ route('admins.update' , ['id'=>$admin->id]) }}" method="POST"  enctype="multipart/form-data"> 
                        @csrf
                        <input type="file" id="imageUpload" name="profile_image" accept="image/*" style="display: none;" onchange="previewImage(event)">


                        <div style="display: flex; flex-direction: initial; gap: 20px; width: 100%; margin: inherit;">
                            <div class="form-group" style="width: 50%">
                                <label for="name">الاسم:</label>
                                @error('name')
                                <div style="color: #c20c0c" >    
                                    * {{$message}}
                                    <br><br>
                                </div>
                                @enderror
                                <input type="text" class="form-control" id="name" name="name" placeholder="أدخل الاسم" value="{{old('name')?old('name'):$admin->name}}">
                            </div>
                            <div class="form-group" style="width: 50%">
                                <label for="email">البريد الإلكتروني:</label>
                                @error('email')
                                <div style="color: #c20c0c" >    
                                    * {{$message}}
                                    <br><br>
                                </div>
                                @enderror
                                <input type="email" class="form-control" id="email" name="email" placeholder="أدخل البريد الإلكتروني" value="{{old('email')?old('email'):$admin->email}}">
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
                            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="أدخل رقم الهاتف"value="{{old('mobile')?old('mobile'):$admin->mobile}}">
                        </div>
                        
                        {{-- <div class="form-group">
                            <label for="subscription_fees">عدد الطلبات المتاحة :</label>
                            @error('subscription_fees')
                            <div style="color: #c20c0c" >    
                                * {{$message}}
                                <br><br>
                            </div>
                            @enderror
                            <input type="text" class="form-control" id="subscription_fees" name="subscription_fees" placeholder="أدخل رقم الهاتف"value="{{old('subscription_fees')?old('subscription_fees'):$admin->subscription_fees}}">
                        </div> --}}
                        {{-- <div class="form-group">
                            <label for="expire">تاريخ انتهاء صلاحية الحساب :</label>
                            @error('expire')
                            <div style="color: #c20c0c" >    
                                * {{$message}}
                                <br><br>
                            </div>
                            @enderror
                            <input type="date" class="form-control" id="expire" name="expire" placeholder="أدخل رقم الهاتف"value="{{old('expire')?old('expire'):$admin->expire}}">
                        </div> --}}

                        {{-- <div>
                            @error('area_id')
                            <div style="color: #c20c0c" >    
                                * {{$message}}
                                <br><br>
                            </div>
                            @enderror
                            <label for="">اختر المنطقة التي يعيش فيها المدير </label>
                            <select name="area_id" class="form-control rounded-input" style="margin-left: 10px;padding-left:5.0rem">
                                <option value="">اختر المنطقة</option>
                                @foreach ($collection as $cityName => $areas)
                                    <optgroup label="{{ $cityName }}">
                                        @foreach ($areas as $area)
                                            @php
                                                $Area = old('area_id')?old('area_id'):$admin->area_id
                                            @endphp
                                            <option value="{{ $area->id }}" {{($Area==$area->id)?'selected':''}} >{{ $area->title }} </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>                   --}}
                        {{-- <div>
                            <input id='active' type="checkbox" name="active" value="1">
                            <label for='active'><h6>حساب نشط</h6></label>
                        </div> --}}
                        <div>
                            <br>
                            <button type="submit" class="btn btn-primary" style="color: black">تعديل</button>
                            <button type="button" class="btn" style="background-color: gainsboro;color: black; padding: 7.0px 20px;" onclick="window.location.href='{{ route('admins.show') }}';">الغاء</button>

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
