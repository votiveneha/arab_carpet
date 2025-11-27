@extends('web.seller.layout.layout')

@section('content')
    <!-- partial -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">


                    <?php
                    $first_name = $last_name = $email = $gender = $user_id = $zip_code = $address1 = $address2 = $user_name = $profile_image = '';
                    $country_id = $state_id = $city_id = $mobile = $mobile_2 = $whatsapp1 = $whatsapp2 = $latitude = $longitude = 0;
                    $address1_ar = $address2_ar = '';
                    if ($user_detail) {
                        $user_id = $user_detail->id;
                        $first_name = $user_detail->first_name;
                        $last_name = $user_detail->last_name;
                        $email = $user_detail->email;
                        $gender = $user_detail->gender;
                        $country_id = $user_detail->country_id;
                        $state_id = $user_detail->state_id;
                        $city_id = $user_detail->city_id;
                        $address1 = $user_detail->address1;
                        $address2 = $user_detail->address2;
                        $address1_ar = $user_detail->address1_ar ?? '';
                        $address2_ar = $user_detail->address2_ar ?? '';
                        $zip_code = $user_detail->zip_code;
                        $user_name = $user_detail->user_name;
                        $profile_image = $user_detail->profile_image;
                        $mobile = $user_detail->mobile ?? '';
                        $mobile_2 = $user_detail->mobile_2 ?? '';
                        $whatsapp1 = $user_detail->whatsapp1 ?? '';
                        $whatsapp2 = $user_detail->whatsapp2 ?? '';
                        $latitude = $user_detail->latitude;
                        $longitude = $user_detail->longitude;
                    }

                    $shop_name = $shop_logo = $shop_banner = $about_shop = $qr_code = $shop_name_ar = '';
                    if ($shop_detail) {
                        $shop_name = $shop_detail->shop_name ?? '';
                        $shop_name_ar = $shop_detail->shop_name_ar ?? '';
                        $shop_logo = $shop_detail->shop_logo;
                        $shop_banner = $shop_detail->shop_banner;
                        $about_shop = $shop_detail->about_shop ?? '';
                        $about_shop_ar = $shop_detail->about_shop_ar ?? '';
                        $about_shop_fr = $shop_detail->about_shop_fr ?? '';
                        $about_shop_ru = $shop_detail->about_shop_ru ?? '';
                        $about_shop_fa = $shop_detail->about_shop_fa ?? '';
                        $about_shop_ur = $shop_detail->about_shop_ur ?? '';
                        $qr_code = $shop_detail->qr_code;
                    }
                    ?>

                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">{{ __('messages.my_profile') }}</h4>
                            <!--p class="card-description"> Add / Update Blog  </p-->



                            <!-- Tab panes -->


                            <form class="forms-sample" method="post" action="{{ route('seller.myProfile') }}"
                                enctype="multipart/form-data">
                                {!! csrf_field() !!}
                                <div class="row">
                                    <input type="hidden" name="user_id" value="{{ $user_id }}">
                                    {{-- <div class="form-group col-md-6">
                              <label for="exampleInputUsername1">{{__('messages.business_man')}}</label>
                              <input type="text" class="form-control form-control-sm" placeholder="{{__('messages.business_man')}}" aria-label="Username" name="first_name" value="{{$first_name}}">
                                </div> --}}
                                        {{-- <div class="form-group col-md-6">
                                <label for="exampleInputUsername1">{{__('messages.last_name')}}</label>
                                <input type="text" class="form-control form-control-sm" placeholder="{{__('messages.last_name')}}" aria-label="Username" name="last_name" value="{{$last_name}}" readonly>
                                </div>
                                --}}

                                        {{-- <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">{{__('messages.email_id')}}</label>
                                <input type="email" class="form-control form-control-sm" id="exampleInputEmail1" placeholder="{{__('messages.email_id')}}" name="email" value="{{$email}}"  >
                                </div> --}}

                                        {{-- <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">{{__('messages.gender')}}</label>
                                <select required class="form-select form-select-sm" id="exampleFormControlSelect3" name="gender">
                                    <option value="1" {{$gender==1?'selected':''}}>{{__('messages.male')}}</option>
                                    <option value="2" {{$gender==2?'selected':''}}>{{__('messages.female')}}</option>
                                    <option value="3" {{$gender==3?'selected':''}}>{{__('messages.other')}}</option>
                                </select>
                                </div> --}}

                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">{{ __('messages.shop_name') }}</label>
                                        <input type="text" class="form-control form-control-sm" id="exampleInputEmail1"
                                            placeholder="{{ __('messages.shop_name') }}" name="shop_name"
                                            value="{{ $shop_name }}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">{{ __('messages.shop_name_ar') }}</label>
                                        <input type="text" class="form-control form-control-sm" id="exampleInputEmail1"
                                            placeholder="{{ __('messages.shop_name_ar') }}" name="shop_name_ar"
                                            value="{{ $shop_name_ar }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">{{ __('messages.about_shop') }}</label>
                                        <textarea class="form-control form-control-sm short_bio" name="about_shop" maxlength="300" id="short_bio1">{{ $about_shop }}</textarea>
                                        <small id="bioCounter1">300 characters remaining</small>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">{{ __('messages.about_shop_ar') }}</label>
                                        <textarea class="form-control form-control-sm short_bio" name="about_shop_ar" maxlength="300" id="short_bio2">{{ $about_shop_ar }}</textarea>
                                        <small id="bioCounter2">300 characters remaining</small>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">{{ __('messages.about_shop_ru') }}</label>
                                        <textarea class="form-control form-control-sm short_bio" name="about_shop_ru" maxlength="300" id="short_bio3">{{ $about_shop_ru }}</textarea>
                                        <small id="bioCounter3">300 characters remaining</small>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">{{ __('messages.about_shop_fr') }}</label>
                                        <textarea class="form-control form-control-sm short_bio" name="about_shop_fr" maxlength="300" id="short_bio4">{{ $about_shop_fr }}</textarea>
                                        <small id="bioCounter4">300 characters remaining</small>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">{{ __('messages.about_shop_fa') }}</label>
                                        <textarea class="form-control form-control-sm" name="about_shop_fa" maxlength="300" id="short_bio5">{{ $about_shop_fa }}</textarea>
                                        <small id="bioCounter5">300 characters remaining</small>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">{{ __('messages.about_shop_ur') }}</label>
                                        <textarea class="form-control form-control-sm short_bio" name="about_shop_ur" maxlength="300" id="short_bio6">{{ $about_shop_ur }}</textarea>
                                        <small id="bioCounter6">300 characters remaining</small>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputUsername1">{{ __('messages.phone_num_1') }}</label>
                                        <input type="text" class="form-control form-control-sm"
                                            placeholder="{{ __('messages.phone_num_1') }}" aria-label="Username"
                                            name="mobile" value="{{ $mobile }}"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')" minlength="4"
                                            maxlength="15">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputUsername1">{{ __('messages.phone_num_2') }}</label>
                                        <input type="text" class="form-control form-control-sm"
                                            placeholder="{{ __('messages.phone_num_2') }}" aria-label="Username"
                                            name="mobile_2" value="{{ $mobile_2 }}"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')" minlength="4"
                                            maxlength="15">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputUsername1">{{ __('messages.whatsapp_1') }}</label>
                                        <input type="text" class="form-control form-control-sm"
                                            placeholder="{{ __('messages.whatsapp_1') }}" aria-label="Username"
                                            name="whatsapp1" value="{{ $whatsapp1 }}"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')" minlength="4"
                                            maxlength="15">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputUsername1">{{ __('messages.whatsapp_2') }}</label>
                                        <input type="text" class="form-control form-control-sm"
                                            placeholder="{{ __('messages.whatsapp_2') }}" aria-label="Username"
                                            name="whatsapp2" value="{{ $whatsapp2 }}"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')" minlength="4"
                                            maxlength="15">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="exampleInputUsername1">{{ __('messages.address1') }}</label>
                                        <input type="text" class="form-control form-control-sm"
                                            placeholder="{{ __('messages.address1') }}" aria-label="Ussername"
                                            name="address1" value="{{ $address1 }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputUsername1">{{ __('messages.address2') }}</label>
                                        <input type="text" class="form-control form-control-sm"
                                            placeholder="{{ __('messages.address2') }}" aria-label="Usedrname"
                                            name="address2" value="{{ $address2 }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="exampleInputUsername1">{{ __('messages.address1_ar') }}</label>
                                        <input type="text" class="form-control form-control-sm"
                                            placeholder="{{ __('messages.address1_ar') }}" aria-label="Ussername"
                                            name="address1_ar" value="{{ $address1_ar }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputUsername1">{{ __('messages.address2_ar') }}</label>
                                        <input type="text" class="form-control form-control-sm"
                                            placeholder="{{ __('messages.address2_ar') }}" aria-label="Usedrname"
                                            name="address2_ar" value="{{ $address2_ar }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">{{ __('messages.country') }}</label>
                                        <select class="form-select form-select-sm" id="country" name="country_id" required>
                                            <option value="">{{ __('messages.country') }}</option>
                                            @if ($country)
                                                @foreach ($country as $country)
                                                    <option value="{{ $country->country_id }}"
                                                        {{ $country_id == $country->country_id ? 'selected' : '' }}>
                                                        {{ $country->country_name }}</option>
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>
                                    {{-- <div class="form-group col-md-6">
                              <label for="exampleInputEmail1">{{__('messages.state')}}</label>
                              <select  class="form-select form-select-sm" id="state" name="state_id" disabled>
                                <option>{{__('messages.state')}}</option>
                                @if ($state)
                                @foreach ($state as $states)
                                <option value="{{$states->state_id }}" {{$state_id==$states->state_id?'selected':''}}>{{$states->state_name}}</option>
                                @endforeach
                                @endif
                              </select>
                                </div> --}}
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">{{ __('messages.city') }}</label>
                                        <select class="form-select form-select-sm" id="city" name="city_id" required>
                                            <option value="">{{ __('messages.city') }}</option>
                                            @if ($city)
                                                @foreach ($city as $cities)
                                                    <option value="{{ $cities->city_id }}"
                                                        {{ $city_id == $cities->city_id ? 'selected' : '' }}>
                                                        {{ $cities->city_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>




                                    {{-- <div class="form-group col-md-6">
                                        <label for="exampleInputUsername1">{{ __('messages.zip') }}</label>
                                        <input type="text" class="form-control form-control-sm"
                                            placeholder="{{ __('messages.zip') }}" aria-label="Usersname"
                                            name="zip_code" value="{{ $zip_code }}">
                                    </div> --}}
                                    {{-- <div class="form-group col-md-6">
                              <label for="exampleInputEmail1">{{__('messages.profile_image')}}</label>
                              <input  type="file" class="form-control form-control-sm" id="profileImageInput"  name="profile_image" accept="image/png, image/gif, image/jpeg">
                              <div>
                                  <img id="profileImagePreview" src="{{ !empty($profile_image) ? asset('public/uploads/profile_image/' . $profile_image) :  asset('public/admin_assets/images/faces/face28.jpg') }}"
                                      alt="Profile Preview" style="max-width: 150px; border: 1px solid #ddd; padding: 5px;">
                              </div>
                                </div> --}}

                                </div>

                                <div class="row">


                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">{{ __('messages.latitude') }}</label>
                                        <input type="text" class="form-control form-control-sm" maxlength="12"
                                            id="latitude" placeholder="22.7124976" name="latitude"
                                            value="{{ $latitude }}" pattern="^-?([1-8]?[0-9](\.\d+)?|90(\.0+)?)$"
                                            title="Enter a valid latitude between -90 and 90">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">{{ __('messages.longitude') }}</label>
                                        <input type="text" class="form-control form-control-sm" maxlength="12"
                                            id="longitude" placeholder="75.8486807" name="longitude"
                                            value="{{ $longitude }}"
                                            pattern="^-?((1[0-7][0-9]|[1-9]?[0-9])(\.\d+)?|180(\.0+)?)$"
                                            title="Enter a valid longitude between -180 and 180">
                                    </div>

                                    {{-- <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">{{ __('messages.shop_banner') }}</label>
                                        <input type="file" class="form-control form-control-sm" id="shop_banner"
                                            name="shop_banner" accept="image/png, image/gif, image/jpeg">
                                        <div>
                                            <img id="shop_bannerPreview"
                                                src="{{ !empty($shop_banner) ? asset('public/uploads/shop_image/' . $shop_banner) : asset('public/admin_assets/images/no_image.png') }}"
                                                alt="shop Preview"
                                                style="max-width: 150px; border: 1px solid #ddd; padding: 5px;">
                                        </div>
                                    </div> --}}

                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">{{ __('messages.shop_photo') }}</label>
                                        <input type="file" class="form-control form-control-sm" id="shop_logo"
                                            name="shop_logo" accept="image/png, image/gif, image/jpeg">
                                        <div>
                                            <img id="shop_logoPreview"
                                                src="{{ !empty($shop_logo) ? asset('public/uploads/shop_image/' . $shop_logo) : asset('public/admin_assets/images/no_image.png') }}"
                                                alt="shop Preview"
                                                style="max-width: 150px; border: 1px solid #ddd; padding: 5px;">
                                        </div>
                                    </div>

</div>
<div class="row">
                                    {{-- <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">{{ __('messages.services') }}</label>
                                        <ul>
                                            @foreach ($seller_service_ids as $service)
                                                <li>
                                                    {{ $service->service_icon }} &nbsp; {{ $service->service_name }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div> --}}
                                    {{-- <div class="form-group col-md-6">
                              <img src="{{ asset('/public/uploads/business_card/' . $shop_detail->digital_card) }}" alt="Parts Rack" class="img-fluid mb-3" style="width: 400px;"/>
                                </div> --}}
                                        {{-- <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">{{__('messages.services')}}</label>
                                <ul>
                                    @foreach ($services as $service)
                                    <li>
                                        <input type="checkbox" name="service_id[]" value="{{$service->id}}" {{in_array($service->id, $seller_service_ids)?'checked':''}}> {{$service->service_icon}} &nbsp; {{$service->service_name}}
                                    </li>
                                    @endforeach
                                </ul>
                                </div> --}}
                                    <div class="form-group col-md-6">
                                        <input type="hidden" id="current_user_id" value="{{ auth()->user()->id }}">
                                        <label for="examspleInputEmail1">{{ __('messages.username') }}</label>
                                        <input type="text" class="form-control form-control-sm"
                                            placeholder="{{ __('messages.username') }}" id="user_name_input"
                                            name="user_name" value="{{ $user_name }}"
                                            oninput="checkUsernameAvailability()">
                                        <small id="username_status" style="font-size: 13px; display: none;"></small>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">{{ __('messages.password') }}</label>
                                        <input type="password" class="form-control form-control-sm"
                                            id="exampleInputEmail1" placeholder="{{ __('messages.password') }}"
                                            name="password" autocomplete="off">
                                    </div>
                                </div>



                                <input type="hidden" name="user_time" value="" id="user_timezone">
                                <button type="submit" class="btn btn-primary me-2">Submit</button>
                            </form>





                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
    </div>
    <!-- main-panel ends -->
@endsection
@push('scripts')

    <script>
        document.getElementById('shop_banner').addEventListener('change', function(event) {
            let file = event.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('shop_bannerPreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
        document.getElementById('shop_logo').addEventListener('change', function(event) {
            let file = event.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('shop_logoPreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
        document.getElementById('profileImageInput').addEventListener('change', function(event) {
            let file = event.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profileImagePreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>

    <script>
        function checkUsernameAvailability() {
            const input = document.getElementById("user_name_input");
            const statusEl = document.getElementById("username_status");
            const userId = document.getElementById("current_user_id").value;
            const username = input.value.trim();

            if (username === "") {
                statusEl.style.display = input.dataset.touched === "true" ? "inline" : "none";
                statusEl.textContent = input.dataset.touched === "true" ? "⚠️ Please enter user name" : "";
                statusEl.style.color = "orange";
                return;
            }

            input.dataset.touched = "true";

            fetch(`{{ url('/check-seller-username') }}?username=${encodeURIComponent(username)}&user_id=${userId}`)
                .then(response => response.json())
                .then(data => {
                    statusEl.style.display = 'inline';
                    console.log(data);
                    if (data.available) {
                        statusEl.textContent = '✅ Username is available';
                        statusEl.style.color = 'green';
                    } else {
                        statusEl.textContent = '❌ Username is already taken';
                        statusEl.style.color = 'red';
                    }
                })
                .catch(err => {
                    console.error("Error:", err);
                    statusEl.textContent = '⚠️ Error checking username';
                    statusEl.style.color = 'orange';
                    statusEl.style.display = 'inline';
                });
        }
    </script>
    <script>
        var triggerTabList = [].slice.call(document.querySelectorAll('#myTab a'))
        triggerTabList.forEach(function(triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl)

            triggerEl.addEventListener('click', function(event) {
                event.preventDefault()
                tabTrigger.show()
            })
        })
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            document.getElementById("user_timezone").value = userTimezone;
        });
        $(document).ready(function() {
            $(document).on('change', '#country1', function() {
                var cid = this.value; //let cid = $(this).val(); we cal also write this.
                $.ajax({
                    url: "{{ url('/admin/getstate') }}",
                    type: "POST",
                    datatype: "json",
                    data: {
                        country_id: cid,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        $('#state').html('<option value="">Select State</option>');
                        $.each(result.state, function(key, value) {
                            $('#state').append('<option value="' + value.state_id +
                                '">' + value.state_name + '</option>');
                        });
                    },
                    errror: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $('#country').change(function() {
                var sid = this.value;
                $.ajax({
                    url: "{{ url('/admin/getcityByCountry') }}",
                    type: "POST",
                    datatype: "json",
                    data: {
                        country_id: sid,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        console.log(result);
                        $('#city').html('<option value="">Select City</option>');
                        $.each(result.city, function(key, value) {
                            $('#city').append('<option value="' + value.city_id + '">' +
                                value.city_name + '</option>')
                        });
                    },
                    errror: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const textareas = document.querySelectorAll('.short_bio');
            const max = 300;

            textareas.forEach((textarea, index) => {
                const counter = document.getElementById('bioCounter' + (index + 1));

                function updateCounter() {
                    const remaining = max - textarea.value.length;
                    counter.textContent = `${remaining} characters remaining`;
                }

                textarea.addEventListener('input', updateCounter);
                updateCounter(); // initial update
            });
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const biod = document.getElementById('detailed_bio');
            const counterd = document.getElementById('bioCounterDetail');
            const max = 1000;

            function updateCounterd() {
                const remaining = max - biod.value.length;
                counterd.textContent = `${remaining} characters remaining`;
            }

            biod.addEventListener('input', updateCounterd);
            updateCounterd(); // initial update
        });
    </script>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: "Success!",
                    text: "{{ session('success') }}",
                    icon: "success",
                    confirmButtonText: "OK"
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "{{ session('error') }}",
                    confirmButtonText: "OK"
                });
            });
        </script>
    @endif
@endpush
