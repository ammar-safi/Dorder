<!-- BEGIN: Footer-->
<footer class="footer footer-static footer-light">
    <p class="clearfix mb-0">
        <span class="float-left d-inline-block">2024 © Derrebni Order</span>
        <span class="float-right d-sm-inline-block d-none">Designed And Programming  with<i class="bx bxs-heart pink mx-50 font-small-3"></i>
            by <a class="text-uppercase" href="https://fb.com/fenix.p2h" target="_blank">Muhammad Khalaf 😎</a></span>
        <button class="btn btn-primary btn-icon scroll-top" type="button"><i class="bx bx-up-arrow-alt"></i></button>
    </p>
</footer>
<!-- END: Footer-->

<!-- BEGIN: Vendor JS-->
<script src="{{ asset('app-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js') }}"></script>
<script src="{{ asset('app-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js') }}"></script>
<script src="{{ asset('app-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js') }}"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="{{ asset('app-assets/js/scripts/configs/vertical-menu-dark.js') }}"></script>
<script src="{{ asset('app-assets/js/core/app-menu.js') }}"></script>
<script src="{{ asset('app-assets/js/core/app.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/components.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/footer.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/pages/table-extended.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/extensions/toastr.min.js') }}"></script>

@if(auth()->user())


{{--<script src="{{ asset('app-assets/js/scripts/forms/validation/form-validation.js') }}"></script>--}}
<!-- END: Theme JS-->
@auth
<!-- BEGIN: Page JS-->

<script>
    function DeleteMonitorArea(id){
        swal({
            title: 'هل أنت متأكد من الحذف؟',
            text: 'عن الموافقة لن تستطيع المتابعة',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'إلغاء',
            confirmButtonText: 'نعم قم بالحذف',
            closeOnConfirm: false
        }).then(function(result) {
            if(result.value){
                $.ajax({
                    url: '{{ route('Monitor-Area-Delete') }}',
                    type: "post",
                    data:{ _token: "{{csrf_token()}}",q:id }
                }).done(function() {
                    swal({
                        title: "تم الحذف",
                        text: "لقد تم الحذف بشكل سليم",
                        type: "success"
                    }).then(function() {
                        location.href = window.location;
                    });
                });
            }else if(result.dismiss == 'cancel'){
                console.log('cancel');
            }

        });
    }

    function DeleteDeliveryArea(id){
        swal({
            title: 'هل أنت متأكد من الحذف؟',
            text: 'عن الموافقة لن تستطيع المتابعة',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'إلغاء',
            confirmButtonText: 'نعم قم بالحذف',
            closeOnConfirm: false
        }).then(function(result) {
            if(result.value){
                $.ajax({
                    url: '{{ route('Delivery-Area-Delete') }}',
                    type: "post",
                    data:{ _token: "{{csrf_token()}}",q:id }
                }).done(function() {
                    swal({
                        title: "تم الحذف",
                        text: "لقد تم الحذف بشكل سليم",
                        type: "success"
                    }).then(function() {
                        location.href = window.location;
                    });
                });
            }else if(result.dismiss == 'cancel'){
                console.log('cancel');
            }

        });
    }

    // Delete City
    function DeleteCity(id){
        swal({
            title: 'هل أنت متأكد من الحذف؟',
            text: '   انتبه يمكنك حذف المحافظه هذه ولكن جميع الارتباطات مثل المناطق والمراسلين والمشتركين والطلبات والمراسلات سيتم حذفها ولا يمكن استعادة هذه البيانات فهل أنت متأكد من الحذف',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'إلغاء',
            confirmButtonText: 'نعم قم بالحذف',
            closeOnConfirm: false
        }).then(function(result) {
            if(result.value){
                $.ajax({
                    url: '{{ route('Remove-City') }}',
                    type: "post",
                    data:{ _token: "{{csrf_token()}}",q:id }
                }).done(function() {
                    swal({
                        title: "تم الحذف",
                        text: "لقد تم الحذف بشكل سليم",
                        type: "success"
                    }).then(function() {
                        location.reload();
                    });

                });
            }else if(result.dismiss == 'cancel'){
                swal({
                    title: "لم يتم الحذف",
                    text: "تم الغاء عملية الحذف",
                    type: "warning"
                });
            }

        });
    }
</script>
<!-- END: Page JS-->

<!-- Pusher -->
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    let NotificationSound   = '{{ asset('app-assets/Sounds/notify.wav') }}';
    let UserSound           = '{{ asset('app-assets/Sounds/login.wav') }}';
    let ErrorSound          = '{{ asset('app-assets/Sounds/error.mp3') }}';
    let TimeOut             = '{{ asset('app-assets/Sounds/TimeOut.mp3') }}';
</script>
<script src="{{ asset('js/Order-Pusher.js') }}"></script>
<script src="{{ asset('js/Customer-Pusher.js') }}"></script>
<script src="{{ asset('js/Schedule-Notify.js') }}"></script>
<script src="{{ asset('js/AdminJs.js') }}"></script>
<div class="DexterAreLegend"></div>
<script type="text/javascript">
    $(function (){
        let interval = setInterval(function () {
            DexterAreLegend();
        }, 60000);

        function DexterAreLegend(){
            $.ajax({
                url: "{{ route('Dexter') }}",
                type: "get",
                success: function(data) {
                    //$('.DexterAreLegend').text(data);
                    console.log('SaY Hi To Muhammad Khalaf ');
                },
                error: function(){
                    console.log('Bye');
                }
            });

        }
    });



</script>
@endauth
@endif
<!-- Pusher -->

</body>
<!-- END: Body-->

</html>
