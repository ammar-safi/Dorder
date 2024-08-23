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
        <br><br>
        @endif

        <h2>تعديل مدينة {{$city->title}}</h2>
        <br>
        <h5>سيتم تغيير اسم هذه المدينة الى الاسم الذي سوف تدخله في الحقل ادناه</h5>
        <h5>يجب عليك ان تدخل اسم عربي فقط !</h5>
        <br>
        @error('title')
            <div style="color: red;">{{ $message }}</div>
        @enderror
        <br>
        <form action="{{ route('cities.update.city') }}" method="POST"  class="form-inline">
            @csrf
            <input type="hidden" name="id" value="{{$city->id}}">
            {{-- @dd($route); --}}
            <input type="hidden" name="route" value="{{$route}}">
            <input type="text" id="title" class="form-control rounded-input" name="title" placeholder="اسم المدينة" value="{{ old('title')?old("title"):$city->title }}" style="margin-left: 10px;">
            <button type="submit"  class="btn btn-primary rounded-button" style="color: black; padding: 7.0px 20px; margin-left: 10px;">تعديل</button>
            <button type="button" class="btn btn-primary rounded-button" style="color: black; padding: 7.0px 20px;" onclick="history.back()">تراجع</button>
    </form>
<br><br>            
        </div>
    </div>

</div>

@include('panel.static.footer')
