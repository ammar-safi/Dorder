@include('panel.static.header')
@include('panel.static.main')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
    
            <script>
                function calculateUnitPrice() {
                    const orderCount = document.getElementById('orderCount').value;
                    const totalPrice = document.getElementById('totalPrice').value;
                    let unitPrice = 0;
        
                    if (orderCount && totalPrice) {
                        unitPrice = totalPrice / orderCount;
                    }
        
                    document.getElementById('unitPrice').value = unitPrice.toFixed(2); // عرض النتيجة مع دقتين عشريتين
                }
            </script>
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
        <h2>تعديل الحزمة : {{$package->title}}</h2>
        <br>

        <br>
        <form action="{{ route('packages.update') }}" method="POST" style="display: block; max-width: 100%;">
            @csrf
            <input type="hidden" name="id" value="{{$package->id}}">
            <div style="margin-bottom: 15px;">
                <label for="title" style="display: block; margin-bottom: 5px; font-weight: bold;" >اسم الحزمة</label>
                @error("title")
                <div style="color: red" >
                    {{$message}}
                </div>
                @enderror 
                <input type="text" name="title" id="title" placeholder="ادخل اسم الحزمة" value="{{old('title')?old('title'):$package->title}}" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label for="orderCount" style="display: block; margin-bottom: 5px; font-weight: bold;">عدد الطلبات:</label>
                @error("orderCount")
            <div style="color: red" >
                {{$message}}
            </div>
            @enderror 
                <input type="number" value="{{old('orderCount')?old('orderCount'):$package->count_of_orders}}"  name="orderCount" id="orderCount" placeholder="أدخل عدد الطلبات" oninput="calculateUnitPrice()" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
            </div>  
        
            <div style="margin-bottom: 15px;">
                <label for="totalPrice" style="display: block; margin-bottom: 5px; font-weight: bold;">السعر الكلي:</label>

                @error("totalPrice")
                <div style="color: red" >{{$message}}</div>
                @enderror 

                <input type="number"  value="{{old('totalPrice')?old('totalPrice'):$package->package_price}}" name="totalPrice" id="totalPrice" placeholder="أدخل السعر الكلي" oninput="calculateUnitPrice()" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
            </div>
        
            <div style="margin-bottom: 15px;">
                <label for="unitPrice" style="display: block; margin-bottom: 5px; font-weight: bold;">سعر الطلب :</label>
                <input type="text" id="unitPrice" readonly style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc; background-color: #f5f5f5; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
            </div>
           
            <button type="submit" class="btn btn-primary rounded-button" style="color: black; padding: 10px 20px; margin-right: 10px; background-color: #007bff; border: none; border-radius: 5px;">تعديل</button>
            <button type="button" class="btn rounded-button" style="color: black; padding: 10px 20px; background-color: #bccad8; border: none; border-radius: 5px;" onclick="history.back()">تراجع</button>
        </form>
        
<br><br>            
            </div>
        </div>

    </div>

    @include('panel.static.footer')
