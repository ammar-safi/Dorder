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
            <br>
            @error('id')
            <div style="background-color: #f5caca; border-right: 6px solid #e72626; padding: 10px; border-radius: 10px;">
                <p style="font-size: 20px; margin: 0;">
                    <strong> ⚠ {{$message}}</strong> 
                </p>
            </div>
            <br><br>
            @enderror
            @error('delivers')
                <div style="background-color: #f5caca; border-right: 6px solid #e72626; padding: 10px; border-radius: 10px;">
                    <p style="font-size: 20px; margin: 0;">
                        <strong> ⚠ {{$message}}</strong> 
                        
                    </p>
                </div>
            @enderror
            @error('area_id')
                <div style="background-color: #f5caca; border-right: 6px solid #e72626; padding: 10px; border-radius: 10px;">
                    <p style="font-size: 20px; margin: 0;">
                        <strong> ⚠ {{$message}}</strong
                        
                    </p>
                </div>
            @enderror



            <div>
                <br><br>
                <h1>
                    تعيين عامل التوصيل {{$deliver->name}} 
                    
                </h1>
                <br>
                <hr>
                <br>
            </div>
            <div>
                <h3>قم بتحديد المدينة والمنطقة التي تريد التوظيف فيها</h3>
<br><br>
                <div style="display: flex; flex-direction: column; gap: 20px; width: 50%; margin: inherit;">
                                
                    
                    <form method="get" action="{{ route('delivers.employ') }}" style="display: flex; flex-direction: column; gap: 10px;">

                        <input type="hidden" name="id" value="{{ $deliver->id }}">


                        <select name="city_id" id="city_id" onchange="this.form.submit()"  class="custom-select" >
                            <option value="">حدد مدينة</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}" {{ $selectedCityId == $city->id ? 'selected' : '' }}>
                                    {{ $city->title }}
                                </option>
                            @endforeach
                        </select>
                        
                        <select name="area_id" id="area_id" onchange="this.form.submit()"   class="custom-select">
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
                    <form action="{{Route('delivers.set.employ')}}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{$deliver->id}}">
                        <input type="hidden" name="area_id" value="{{$selectedAreaId}}">
                        <button type="submit"  class="btn btn-primary rounded-button" style="color: black; padding: 7.0px 20px; margin-left: 10px;">تعيين</button>
                        <button type="button" class="btn" style="background-color: gainsboro;color: black; padding: 7.0px 20px;" onclick="window.location.href='{{ route('delivers.show') }}';">الغاء</button>
                    </form>
                </div>
            </div>



        </div>
    </div>
</div>

@include('panel.static.footer')
