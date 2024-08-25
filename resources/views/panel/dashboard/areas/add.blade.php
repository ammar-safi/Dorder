@include('panel.static.header')
@include('panel.static.main')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
    
        </div>
        <div class="content-body">
        <br><br> 
        @if(session()->has("error"))
        <br>
        <div style="background-color: #dfe7b1; border-right: 6px solid #cfee22; padding: 10px; border-radius: 10px;">
            <p style="font-size: 20px; margin: 0;">
                <strong>تنبيه ⚠</strong> <br><br>
                    {{session("error")}}
            </p>
        </div> 
        @elseif(session()->has('error')) 
        <br>
        <div style="background-color: #f5caca; border-right: 6px solid #e72626; padding: 10px; border-radius: 10px;">
            <p style="font-size: 20px; margin: 0;">
                <strong>تنبيه ⚠</strong> <br><br>
                    {{session("error")}}
            </p>
        </div> 
        <br><br>
        @endif
        <br><br>

        <h2>اضافة منطقة</h2>
        <h5>لا تنسى بان تقوم باضافة  عمال توصيل  ومشرفين للمنطقة الجديدة</h5>
        <br>
        <br>
        <hr>
        @error('title')
            <div style="color: red;"> * {{ $message }}</div>
        @enderror
        @error('city_id')
            <div style="color: red;"> * {{ $message }}</div>
        @enderror
        <br>
        <form action="{{ route('areas.stor') }}"  method="POST"  >
            @csrf

            <div class="form-inline" >

                <input type="text" id="title" class="form-control rounded-input" name="title"  placeholder="اسم المنطقة" style="margin-left: 10px;" value='{{old('title')?old('title'):NULL}}'>
                <select name="city_id" class="form-control rounded-input" style="margin-left: 10px;padding-left:5.0rem">
                    <option value="">اختر المدينة</option>
                    @foreach ($cities as $city)
                    <option value='{{$city->id}}' {{(old('city_id')&&old('city_id')==$city->id)?"selected":""}} > {{$city->title}} </option>
                    @endforeach
                </select>

            </div>
            <br><br>
            <button type="submit"  class="btn btn-primary rounded-button" style="color: black; padding: 7.0px 20px; margin-left: 10px;">اضافة</button>
            
        </form>
        
        <br><br>            
        </div>
    </div>
        

</div>

@include('panel.static.footer')
