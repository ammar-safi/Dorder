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

        <h2>تعديل العنوان : {{$address->title}}</h2>
        <br>
        <br>
        @error('title')
            <div style="color: red;"> * {{ $message }}</div>
        @enderror
        @error('city_id')
            <div style="color: red;"> * {{ $message }}</div>
        @enderror
        <br>
        <form action="{{ route('addresss.update.address') }}" method="POST" >
            @csrf
            <input type="hidden" name="id" value="{{$address->id}}">
            {{-- <input type="hidden" name="route" value="{{$route}}"> --}}
            <div class="form-inline" >
                
                <input type="text" id="title" class="form-control rounded-input" name="title" placeholder="العنوان" value="{{ old('title') ? old('title') : $address->title }}" style="margin-left: 10px;">
                
                {{-- <select name="city_id" class="form-control rounded-input" style="margin-left: 10px;padding-left:5.0rem">
                    <option value="">اختر المدينة</option>
                    @foreach ($cities as $city)
                    <option value='{{$city->id}}' {{ (old('city') == $city->title || $address->city->title == $city->title) ? 'selected' : '' }} > {{$city->title}} </option>
                    @endforeach
                </select> --}}
                
            </div>
            <br>
            <button type="submit"  class="btn btn-primary rounded-button" style="color: black; padding: 7.0px 20px; margin-left: 10px;">تعديل</button>
            <button type="button" class="btn btn-primary rounded-button" style="color: black; padding: 7.0px 20px;" onclick="history.back()">تراجع</button>

        </form>
        
        <br><br>            
        </div>
    </div>
        

</div>

@include('panel.static.footer')
