@include('panel.static.header')
@include('panel.static.main')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <style>
                .form-inline {
                    display: flex;
                    align-items: center;
                }
                .form-inline input {
                    margin-right: 10px; 
                }
             </style>
            
        </style>
        </div>
        <div class="content-body">
            <br><br><br>
            @if ($add)                        
            <div style="background-color: #c8ffc1; border-right: 6px solid #25db0d; padding: 20px; border-radius: 10px; ">
                <p style="font-size: 20px; margin: 0;">
                    <strong>تمت إضافة المدينة بنجاح</strong> <br>
                </p>
            </div>
            
                <br><br>
            @endif
            <h1>اضافة مدينة جديدة</h1>
            <br>
            <h5>عند اضافة مدينة جديدة ستكون خدمات "Derrebni Order" متاحة في هذه المدينة</h5>
            
            {{-- <h5> ❗ </h5> --}}
            <br><br><br>
            <label for="title"><h6>ادخل اسم المدينة الجديدة</h6></label><br>
            @error('title')
                <p style="color: red">{{$message}}</p>                   
            @enderror
            <form method="POST" action="{{ route('cities.conform.adding') }}" class="form-inline"> 
                @csrf
                <input type="text" id="title" class="form-control rounded-input" name="title" placeholder="اسم المدينة" value="{{ old('title') }}" style="margin-left: 10px;">
                <button type="submit"  class="btn btn-primary rounded-button" style="color: black; padding: 7.0px 20px; margin-left: 10px;">اضافة</button>
    
            </form>
            
            
            
            
            <div>

            </div>
        </div>
        </div>
</div>

@include('panel.static.footer')
