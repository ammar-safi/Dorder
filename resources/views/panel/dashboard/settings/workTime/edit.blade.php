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
        <div style="background-color: #e4d4b3; border-right: 6px solid #eead22; padding: 10px; border-radius: 10px;">
            <p style="font-size: 20px; margin: 0;">
                <strong>تنبيه ⚠</strong> <br><br>
                    {{session("error")}}
            </p>
        </div> 
        <br><br>
        @endif
        @if(session()->has("success"))
        <br>
        <div style="background-color: #b7ecb7; border-right: 6px solid #22ee22; padding: 10px; border-radius: 10px;">
            <p style="font-size: 20px; margin: 0;">
                    {{session("success")}}
            </p>
        </div> 
        <br><br>
        @endif
        <h2>تعديل اوقات الدوام ليوم   {{$WorkTime->day}}</h2>
        <br>

        <br>
        <form action="{{ route('work.times.update') }}" method="POST" style="display: block; max-width: 100%;">
            @csrf
            <input type="hidden" name="id" value="{{$WorkTime->id}}">
            <div style="margin-bottom: 15px;">
                <label for="from_time" style="display: block; margin-bottom: 5px; font-weight: bold;" >يبدأ الدوام في الساعة </label>
                @error("from_time")
                <div style="color: red" >
                    {{$message}}
                    <br>
                </div>
                @enderror 
                <input type="text" name="from_time" id="from_time" placeholder="HH:MM" value="{{old('from_time')?old('from_time'):date('H:i', strtotime($WorkTime->from_time))}}" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label for="to_time" style="display: block; margin-bottom: 5px; font-weight: bold;">ينتهي الدوام في الساعة :</label>
                @error("to_time")
                    <div style="color: red">
                        {{$message}}
                        <br>
                    </div>
                 @enderror 
                <input type="text" value="{{old('to_time')?old('to_time'):date('H:i', strtotime($WorkTime->to_time))}}"  name="to_time" id="to_time" placeholder="HH:MM"  style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
            </div>  
   
        
           
            <button type="submit" class="btn btn-primary rounded-button" style="color: black; padding: 10px 20px; margin-right: 10px; background-color: #007bff; border: none; border-radius: 5px;">تعديل</button>
            <button type="button" class="btn rounded-button" style="color: black; padding: 10px 20px; background-color: #bccad8; border: none; border-radius: 5px;" onclick="history.back()">تراجع</button>
        </form>
        
<br><br>            
            </div>
        </div>

    </div>

    @include('panel.static.footer')
