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

        <h2>اضافة عنوان للعميل {{$client->name}}</h2>
        <br>
        <hr>
        @error('title')
        <div style="color: red;"> * {{ $message }}</div>
        @enderror
        <br>
        <form action="{{ route('addresses.store') }}"  method="POST" style="display: flex;flex-direction: row;gap: 10px" >
            @csrf
            <input type="hidden" name="id" value="{{$client->id}}">
            <input type="text" name="title" placeholder="العنوان" class="form-control rounded-input" style="width: 30%">
            <button type="submit"  class="btn btn-primary rounded-button" style="color: rgb(255, 255, 255); padding: 7.0px 20px; margin-left: 10px;">اضافة</button>
            
        </form>
        
        <br><br>            
        </div>
    </div>
        

</div>

@include('panel.static.footer')
