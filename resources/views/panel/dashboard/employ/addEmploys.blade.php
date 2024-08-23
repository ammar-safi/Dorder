@include('panel.static.header')
@include('panel.static.main')

<!-- BEGIN: Content -->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row"></div>
        <div class="content-body">
            <br><br>
            
            @if(session()->has('error'))
                <br>
                <div style="background-color: #f5caca; border-right: 6px solid #e72626; padding: 10px; border-radius: 10px;">
                    <p style="font-size: 20px; margin: 0;">
                        <strong>تنبيه ⚠</strong> <br><br>
                        {{ session('error') }}
                    </p>
                </div>
                <br><br>
            @endif



            <div>
                <br><br>
                <h1>
                    تعيين مشرفين وعمال توصيل 
                    
                </h1>
                <br>
                
               
                <br>
                <hr>
                <br>
            </div>
            <div>
                <h3>قم بتحديد المدينة والمنطقة التي تردي التزظيف غيها</h3>

                <!-- Begin forms section -->
                <div style="display: flex; flex-direction: column; gap: 20px; width: 50%; margin: inherit;">
                                
                    {{-- <!-- Form 1: POST request -->
                    <form id="myForm" action="{{Route("employs.store")}}" method="post" style="display: flex; flex-direction: column; gap: 10px;">
                        @csrf
                        <input type="hidden" name="city_id" value="{{$selectedCityId}}">
                        <input type="hidden" name="area_id" value="{{$selectedAreaId}}">
                    </form> --}}
                    
                    <!-- Form 2: City Select -->
                    <form method="get" action="{{ route('employs.create') }}" style="display: flex; flex-direction: column; gap: 10px;">
                        @error('city_id')
                        <p style="color: red" >* {{$message}}</p>
                        @enderror
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
                    <form method="GET" action="{{ route('employs.create') }}" style="display: flex; flex-direction: column; gap: 10px;">
                        @error('area_id')
                        <p style="color: red" > * {{$message}}</p>
                        @enderror

                        <input type="hidden" name="city_id" value="{{ $selectedCityId }}">
                        <label for="area_id" style="font-size: 1rem;">حدد منطقة:</label>
                        <select name="area_id" id="area_id" onchange="this.form.submit()" style="padding: 10px; width: 100%; border-radius: 5px; border: 1px solid #ccc;">
                            <option value="">حدد منطقة</option>
                            @if($areas)
                                @foreach ($areas as $area)
                                    <option value="{{ $area->id }}" {{ $selectedAreaId == $area->id ? 'selected' : '' }}>
                                        {{ $area->title }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </form>

                   
                </div>
                <!-- End forms section -->
            </div>


            <br><br><br>
            <h6>
                <li>

                    يمكنك تعيين اكثر من مشرف واكثر من عامل توصيل في نفس الوقت
                </li>
                <li>

                    ويمكنك تعيين مشرفين فقط او عمال توصيل فقط
                </li>
            </h6>
            <br><br><br>

            <!-- Form to Assign Monitors and Delivers -->
            <form action="{{Route("employs.store")}}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{$selectedAreaId}}">
                <!-- Container for select elements -->
                <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                    
                    <!-- Monitors Selection -->
                    <div style="flex: 1;">
                        <label for="monitors"><h3> المشرفين المتاحين:</h3></label>
                        <select id="monitors" name="monitors[]" multiple style="width: 100%; height: 150px;">
                            @foreach($monitors as $monitor)
                                <option value="{{ $monitor->id }}">{{ $monitor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Delivers Selection -->
                    <div style="flex: 1;">
                        <label for="delivers"><h3> عمال التوصيل المتاحين:</h3></label>
                        <select id="delivers" name="delivers[]" multiple style="width: 100%; height: 150px;">
                            @foreach($delivers as $deliver)
                                <option value="{{ $deliver->id }}">{{ $deliver->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <br>
                @error('id')
                    <div style="color: red;"> * {{ $message }}</div>
                @enderror
                @error('monitors.*')
                    <div style="color: red;"> * {{ $message }}</div>
                @enderror
                @error('delivers.*')
                    <div style="color: red;"> * {{ $message }}</div>
                @enderror
                <br>

                <!-- Submit Button -->
            <button type="submit"  class="btn btn-primary rounded-button" style="color: black; padding: 7.0px 20px; margin-left: 10px;">اضافة</button>
            <button type="button" class="btn btn-primary rounded-button" style="color: black; padding: 7.0px 20px;" onclick="history.back()">الغاء</button>

                {{-- <button type="submit" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">حفظ</button> --}}
            </form>

            <br><br>
            <hr>
        </div>
    </div>
</div>

@include('panel.static.footer')
