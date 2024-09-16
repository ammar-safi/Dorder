@include('panel.static.header')
@include('panel.static.main')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
            <div class="col-12">
                <br><br>
                <h1>تعديل المشرف {{$monitor->name}}</h1>
            </div>
        </div>
        <script>
           let deletedAreas = [];
    let remainingAreas = [];

    // يتم تعبئة remainingAreas عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function () {
        const rows = document.querySelectorAll('#areasTable tr[data-id]');
        remainingAreas = Array.from(rows).map(row => parseInt(row.dataset.id));
        updateFormInputs();
    });

    function deleteArea(id) {
        let row = document.getElementById('row-' + id);
        row.style.textDecoration = 'line-through';
        row.querySelector('.delete-btn').style.display = 'none';
        document.getElementById('undo-btn-' + id).style.display = 'inline-block';

        if (!deletedAreas.includes(id)) {
            deletedAreas.push(id);
        }

        // إزالة المنطقة المحذوفة من مصفوفة المناطق المتبقية
        remainingAreas = remainingAreas.filter(areaId => areaId !== id);

        updateFormInputs();
    }

    function undoDelete(id) {
        let row = document.getElementById('row-' + id);
        row.style.textDecoration = 'none';
        document.getElementById('undo-btn-' + id).style.display = 'none';
        row.querySelector('.delete-btn').style.display = 'inline-block';

        deletedAreas = deletedAreas.filter(areaId => areaId !== id);

        // إعادة المنطقة إلى مصفوفة المناطق المتبقية
        if (!remainingAreas.includes(id)) {
            remainingAreas.push(id);
        }

        updateFormInputs();
    }

    function updateFormInputs() {
        document.getElementById('deletedAreasInput').value = JSON.stringify(deletedAreas);
        document.getElementById('remainingAreasInput').value = JSON.stringify(remainingAreas);
        
        console.log("المناطق المحذوفة: ", deletedAreas);
        console.log("المناطق المتبقية: ", remainingAreas);
    }

    function addArea(id, cityTitle, areaTitle) {
        if (!remainingAreas.includes(id)) {
            remainingAreas.push(id);
        }

        const tableBody = document.querySelector('#areasTable tbody');
        const row = document.createElement('tr');
        row.id = `row-${id}`;
        row.dataset.id = id;
        row.innerHTML = `
            <td class="city-title">${cityTitle}</td>
            <td class="area-title">${areaTitle}</td>
            <td>
                <button type="button" class="delete-btn" onclick="deleteArea(${id})">
                    <i class="fas fa-trash"></i>
                </button>
                <button type="button" class="undo-btn" id="undo-btn-${id}" style="display: none;" onclick="undoDelete(${id})">
                    <i class="fas fa-undo"></i> تراجع
                </button>
            </td>
        `;
        tableBody.appendChild(row);
        updateFormInputs();
    }

        </script>
        <style>
            .table-container {
                margin: 0px;
                width: 100%; /* يضمن أن الجدول يأخذ عرض الشاشة بالكامل */
                border: 1px solid #ddd;
                border-radius: 12px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }
            .data-table {
                width: 100%; /* يجعل الجدول يأخذ عرض الشاشة بالكامل */
                border-collapse: collapse; /* إزالة الفراغات بين الخلايا */
            }


            .table-title {
                background-color: #ffffff; /* لون خلفية العنوان */
                color: rgb(0, 0, 0); /* لون النص */
                padding: 10px;
                cursor: pointer;
                text-align:right;
                font-size: 1.5em;
                display: flex; /* استخدام الفليكس لتوزيع العناصر */
                justify-content: space-between; /* توزيع المساحة بين العناصر */
                align-items: center; /* محاذاة العناصر في المنتصف */
            }

            .table-title:hover {
                background-color: #f3f5f7; /* تأثير عند التحويم */
            }

            table {
                width: 100%;
                border-collapse: collapse; /* إزالة الفراغات بين الخلايا */
            }

            th, td {
                padding: 12px;
                text-align: center;
                border-bottom: 1px solid #ddd; /* خط تحت الخلايا */
            }

            th {
                background-color: #f2f2f2; /* لون خلفية رأس الجدول */
            }

            tr:hover {
                background-color: #e2e9eb; /* تأثير عند التحويم على الصفوف */
            }
            .delete-btn, .undo-btn {
                /* background-color: #f44336; */
                border: none;
                color: rgb(0, 0, 0);
                padding: 7px 15px;
                border-radius: 5px;
                cursor: pointer;
                font-size: 14px;
                display: inline-block;
            }

            /* .undo-btn {
                background-color: #007bff;
            } */

            .delete-btn i, .undo-btn i {
                margin-right: 5px;
            }

            /* .undo-btn:hover, .delete-btn:hover {
                background-color: #373f47;
            } */

            /* شطب الصف */
            tr {
                transition: text-decoration 0.3s ease;
            }

            tr.striked {
                text-decoration: line-through;
                color: gray;
            }
        </style>
        <div class="content-body">
            <br><br>
            @if(session()->has("error"))
                <div style="background-color: #ffb3b3; border-right: 6px solid #c20c0c; padding: 20px; border-radius: 10px;">
                    <p style="font-size: 20px; margin: 0;">
                        {{session("error")}}
                    </p>
                </div> 
                <br><br><br>
            @endif
            
            <!-- Begin forms section -->
            <div style="display: flex; flex-direction: column; gap: 20px; width: 100%; margin: inherit;">
                
                <!-- Form 1: POST request -->
                <form id="myForm" action="{{Route('monitors.update')}}" method="post" style="display: flex; flex-direction:column; gap: 10px;">
                    @csrf
                    <input type="hidden" name="id" value="{{$monitor->id}}">
                    <input type="hidden" name="area_id" id="deletedAreasInput">
                    <div style="display: flex;gap:10px  ;flex-direction:initial;">
                        <div style="width: 50%">
                            <label for="nameInput">تعديل الاسم </label>
                            <input type="text" oninput="sendName()" id="nameInput" name='name' value="{{old('name')?old('name'):($name?$name:$monitor->name)}}" style="padding: 10px; width: 100%; border-radius: 5px; border: 1px solid #ccc;">
                            @error('name')
                            <p style="color: red" > * {{$message}}</p>
                            @enderror
                        </div>
                        <div style="width: 50%">
                            <label for="mobileInput">تعديل رقم الهاتف </label>
                            <input type="text" oninput="sendMobile()" id="mobileInput" name='mobile' value="{{old('mobile')?old("mobile"):($mobile?$mobile:$monitor->mobile)}}" style="padding: 10px; width: 100%; border-radius: 5px; border: 1px solid #ccc;">
                            @error('mobile')
                            <p style="color: red" > * {{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div>

                        <label for="emailInput">تعديل البريد الالكتروني </label>
                        <input type="text" oninput="sendEmail()" id="emailInput" name='email' value="{{old("email")?old("email"):($email?$email:$monitor->email)}}" style="padding: 10px; width: 100%; border-radius: 5px; border: 1px solid #ccc;">
                        @error('email')
                        <p style="color: red" > * {{$message}}</p>
                        @enderror
                    </div>
                </form><br><br>
                <h5>المدن التي بعمل بها المشرف</h5>

                <table>
                    <thead>
                        <th>المدينة</th>
                        <th>المنطقة</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach($areas as $area)
                        <tr id="row-{{$area->id}}" data-id="{{$area->id}}">
                            <td class="city-title">{{$area->city->title}}</td>
                            <td class="area-title">{{$area->title}}</td>
                            <td>
                                <button type="button" class="delete-btn" onclick="deleteArea({{ $area->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button type="button" class="undo-btn" id="undo-btn-{{$area->id}}" style="display: none;" onclick="undoDelete({{ $area->id }})">
                                    <i class="fas fa-undo"></i> تراجع
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <!-- Form 2: City Select -->
                {{-- <form method="get" action="{{ route('monitors.edit') }}" style="display: flex; flex-direction: column; gap: 10px;">
                    <input type="hidden" name="id" value="{{$monitor->id}}">
                    <input type="hidden" id="nameCityHidden" name="name" value="{{old('name')?old('name'):($name?$name:'')}}">
                    <input type="hidden" id="emailCityHidden" name="email" value="{{old('email')?old("email"):($email?$email:'')}}">
                    <input type="hidden" id="mobileCityHidden" name="mobile" value="{{old('mobile')?old("mobile"):($mobile?$mobile:"")}}">
                   
                    @error('city_id')
                    <p style="color: red" >* {{$message}}</p>
                    @enderror

                    <label for="city_id" id="" style="font-size: 1rem;">حدد مدينة:</label>
                    <select name="city_id" id="city_id" onchange="this.form.submit()" style="padding: 10px; width: 100%; border-radius: 5px; border: 1px solid #ccc;">
                        <option>حدد مدينة</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}" {{ $selectedCityId == $city->id ? 'selected' : '' }}>
                                {{ $city->title }}
                            </option>
                        @endforeach
                    </select>
                </form>
                
                <!-- Form 3: Area Select -->
                <form method="GET" action="{{ route('monitors.edit') }}" style="display: flex; flex-direction: column; gap: 10px;">
                    <input type="hidden" name="id" value="{{$monitor->id}}">
                    <input type="hidden" name="city_id" value="{{ $selectedCityId }}">
                    <input type="hidden" id="nameAreaHidden" name="name" value="{{old('name')?old('name'):($name?$name:'')}}">
                    <input type="hidden" id="emailAreaHidden" name="email"value="{{old('email')?old("email"):($email?$email:'')}}">
                    <input type="hidden" id="mobileAreaHidden" name="mobile" value="{{old('mobile')?old("mobile"):($mobile?$mobile:"")}}">

                    @error('area_id')
                     <p style="color: red" > * {{$message}}</p>
                    @enderror

                    <label for="area_id" style="font-size: 1rem;">حدد منطقة:</label>
                    <select name="area_id" id="area_id" onchange="this.form.submit()" style="padding: 10px; width: 100%; border-radius: 5px; border: 1px solid #ccc;">
                        <option value="">حدد منطقة</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}" {{ $selectedAreaId == $area->id ? 'selected' : '' }}>
                                {{ $area->title }}
                            </option>
                        @endforeach
                    </select>
                </form> --}}

                <div style="display: flex; gap: 10px;width: 30%;">
                    <button type="submit" onclick="document.getElementById('myForm').submit();" class="rounded-button" style="padding: 10px; width: 50%; color: white; background-color: #007bff; border: none; border-radius: 5px;">تعديل</button>
                    <button type="button" class="btn rounded-button" style="background-color: #ccc;width: 50%;color: black; padding: 7.0px 20px;" onclick='window.location.href="{{ route("monitors.show") }}";'>الغاء</button>
                    
                </div>
                
            </div>
            <!-- End forms section -->
            
        </div>
    </div>
</div>
<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

@include('panel.static.footer')
