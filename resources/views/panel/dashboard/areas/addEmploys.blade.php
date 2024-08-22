@include('panel.static.header')
@include('panel.static.main')

<!-- BEGIN: Content -->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row"></div>
        <div class="content-body">
            <br><br>
            
            <!-- Error Handling -->
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

            <!-- Form to Assign Monitors and Delivers -->
            <form action="{{Route("areas.store.employs")}}" method="POST">
                @csrf

                <!-- Hidden input for area id -->
                <input type="hidden" name="id" value="{{ $area->id }}">

                <!-- Container for select elements -->
                <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                    
                    <!-- Monitors Selection -->
                    <div style="flex: 1;">
                        <label for="monitors"><h3>اختر المشرفين:</h3></label>
                        <select id="monitors" name="monitors[]" multiple style="width: 100%; height: 150px;">
                            @foreach($monitors as $monitor)
                                <option value="{{ $monitor->id }}">{{ $monitor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Delivers Selection -->
                    <div style="flex: 1;">
                        <label for="delivers"><h3>اختر عمال التوصيل:</h3></label>
                        <select id="delivers" name="delivers[]" multiple style="width: 100%; height: 150px;">
                            @foreach($delivers as $deliver)
                                <option value="{{ $deliver->id }}">{{ $deliver->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <br>

                <!-- Submit Button -->
            <button type="submit"  class="btn btn-primary rounded-button" style="color: black; padding: 7.0px 20px; margin-left: 10px;">اضافة</button>
            <button type="button" class="btn btn-primary rounded-button" style="color: black; padding: 7.0px 20px;" onclick="history.back()">الغاء</button>

                {{-- <button type="submit" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">حفظ</button> --}}
            </form>

            <br><br>
            <hr>
            @error('title')
                <div style="color: red;"> * {{ $message }}</div>
            @enderror
            @error('city_id')
                <div style="color: red;"> * {{ $message }}</div>
            @enderror
            <br>
        </div>
    </div>
</div>

@include('panel.static.footer')
