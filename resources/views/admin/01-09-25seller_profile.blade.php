@extends('admin.layouts.layout')

@section('content')
    <!-- partial -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <?php
                    $first_name = $last_name = $email = $gender = $user_id = $zip_code = $address1 = $address2 = $address1_ar = $address2_ar = $user_name = $profile_image = '';
                    $country_id = $state_id = $city_id = $latitude = $longitude = $mobile = $mobile_2 = $whatsapp1 = $whatsapp2 = 0;
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
                        $latitude = $user_detail->latitude;
                        $longitude = $user_detail->longitude;
                        $user_name = $user_detail->user_name;
                        $profile_image = $user_detail->profile_image;
                        $mobile = $user_detail->mobile;
                        $mobile_2 = $user_detail->mobile_2;
                        $whatsapp1 = $user_detail->whatsapp1;
                        $whatsapp2 = $user_detail->whatsapp2;
                    }

                    $shop_name = $shop_name_ar = $shop_logo = $shop_banner = $about_shop = $about_shop_ar = $about_shop_fr = $about_shop_ru = $about_shop_fa = $about_shop_ur = $qr_code = '';
                    if ($shop_detail) {
                        $shop_name = $shop_detail->shop_name;
                        $shop_name_ar = $shop_detail->shop_name_ar;
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
                            <a href="{{ route('admin.sellerList') }}" class="btn btn-outline-info btn-fw"
                                style="float: right;">Seller List</a>
                            <h4 class="card-title">Seller Management</h4>
                            <!--p class="card-description"> Add / Update Blog  </p-->

                            <!-- Nav tabs -->
                            <!--ul class="nav nav-tabs" id="myTab" role="tablist">
                                      <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Basic Profile</button>
                                      </li>
                                      <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false" {{ $user_id == '' ? 'disabled' : '' }}>Professional Profile</button>
                                      </li>
                                      <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="messages-tab" data-bs-toggle="tab" data-bs-target="#messages" type="button" role="tab" aria-controls="messages" aria-selected="false">Messages</button>
                                      </li>
                                      <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">Settings</button>
                                      </li>
                                    </ul-->

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <form class="forms-sample" method="post" action="{{ route('admin.addSeller') }}"
                                        enctype="multipart/form-data" autocomplete="off">
                                        {!! csrf_field() !!}
                                        <div class="row">

                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">BUSINESS NAME ENGLISH</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="exampleInputEmail1" placeholder="Entity Name" name="shop_name"
                                                    value="{{ $shop_name }}" required>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">BUSINESS NAME ARABIC</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="exampleInputEmail1" placeholder="BUSINESS NAME ARABIC"
                                                    name="shop_name_ar" value="{{ $shop_name_ar }}">
                                            </div>



                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">ABOUT BUSINESS ENGLISH</label>
                                                <textarea class="form-control form-control-sm short_bio" name="about_shop" maxlength="300" id="short_bio1">{{ $about_shop }}</textarea>
                                                <small id="bioCounter1">300 characters remaining</small>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">ABOUT BUSINESS ARABIC</label>
                                                <textarea class="form-control form-control-sm short_bio" name="about_shop_ar" maxlength="300" id="short_bio2">{{ $about_shop_ar }}</textarea>
                                                <small id="bioCounter2">300 characters remaining</small>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">ABOUT BUSINESS RUSSIAN</label>
                                                <textarea class="form-control form-control-sm short_bio" name="about_shop_ru" maxlength="300" id="short_bio3">{{ $about_shop_ru }}</textarea>
                                                <small id="bioCounter3">300 characters remaining</small>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">ABOUT BUSINESS FRENCH}</label>
                                                <textarea class="form-control form-control-sm short_bio" name="about_shop_fr" maxlength="300" id="short_bio4">{{ $about_shop_fr }}</textarea>
                                                <small id="bioCounter4">300 characters remaining</small>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">ABOUT BUSINESS DARI</label>
                                                <textarea class="form-control form-control-sm" name="about_shop_fa" maxlength="300" id="short_bio5">{{ $about_shop_fa }}</textarea>
                                                <small id="bioCounter5">300 characters remaining</small>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">ABOUT BUSINESS URDU</label>
                                                <textarea class="form-control form-control-sm short_bio" name="about_shop_ur" maxlength="300" id="short_bio6">{{ $about_shop_ur }}</textarea>
                                                <small id="bioCounter6">300 characters remaining</small>
                                            </div>


                                            <div class="form-group col-md-6">
                                                <label for="exampleInputUsername1">PHONE NUMBER 1</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    placeholder="Phone number1" aria-label="Username" name="mobile"
                                                    value="{{ $mobile }}"
                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                    minlength="4" maxlength="15">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputUsername1">PHONE NUMBER 2</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    placeholder="Phone number2" aria-label="Username" name="mobile_2"
                                                    value="{{ $mobile_2 }}"
                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                    minlength="4" maxlength="15">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputUsername1">WHATSAPP NUMBER 1</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    placeholder="Whatsapp number 1" aria-label="Username"
                                                    name="whatsapp1" value="{{ $whatsapp1 }}"
                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                    minlength="4" maxlength="15">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputUsername1">WHATSAPP NUMBER 2</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    placeholder="Whatsapp number 2" aria-label="Username"
                                                    name="whatsapp2" value="{{ $whatsapp2 }}"
                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                    minlength="4" maxlength="15">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="exampleInputUsername1">BUSINESS ADDRESS LINE 1</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    placeholder="Address Line 1" aria-label="Ussername" name="address1"
                                                    value="{{ $address1 }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputUsername1">BUSINESS ADDRESS LINE 2</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    placeholder="Address Line 2" aria-label="Usedrname" name="address2"
                                                    value="{{ $address2 }}">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="exampleInputUsername1">BUSINESS ADDRESS LINE 2 (ARABIC)</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    placeholder="BUSINESS ADDRESS LINE 2 (ARABIC)" aria-label="Ussername"
                                                    name="address1_ar" value="{{ $address1_ar }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputUsername1">BUSINESS ADDRESS LINE 2 (ARABIC)</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    placeholder="BUSINESS ADDRESS LINE 2 (ARABIC)" aria-label="Usedrname"
                                                    name="address2_ar" value="{{ $address2_ar }}">
                                            </div>




                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">COUNTRY</label>
                                                <select class="form-select form-select-sm" id="country"
                                                    name="country_id">
                                                    <option value="">Select Country</option>
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
                              <label for="exampleInputEmail1">State</label>
                              <select class="form-select form-select-sm" id="state" name="state_id">
                                <option>Select State</option>
                                @if ($state)
                                @foreach ($state as $states)
                                <option value="{{$states->state_id }}" {{$state_id==$states->state_id?'selected':''}}>{{$states->state_name}}</option>
                                @endforeach
                                @endif
                              </select>
                            </div> --}}
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">CITY</label>
                                                <select class="form-select form-select-sm" id="city" name="city_id">
                                                    <option value="">Select City</option>
                                                    @if ($city)
                                                        @foreach ($city as $cities)
                                                            <option value="{{ $cities->city_id }}"
                                                                {{ $city_id == $cities->city_id ? 'selected' : '' }}>
                                                                {{ $cities->city_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>


                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">LATITUDE</label>
                                                <input type="text" class="form-control form-control-sm" maxlength="12"
                                                    id="latitude" placeholder="22.7124976" name="latitude"
                                                    value="{{ $latitude }}"
                                                    pattern="^-?([1-8]?[0-9](\.\d+)?|90(\.0+)?)$"
                                                    title="Enter a valid latitude between -90 and 90">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">LONGITUDE</label>
                                                <input type="text" class="form-control form-control-sm" maxlength="12"
                                                    id="longitude" placeholder="75.8486807" name="longitude"
                                                    value="{{ $longitude }}"
                                                    pattern="^-?((1[0-7][0-9]|[1-9]?[0-9])(\.\d+)?|180(\.0+)?)$"
                                                    title="Enter a valid longitude between -180 and 180">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">GENDER</label>
                                                <select class="form-select form-select-sm" id="exampleFormControlSelect3"
                                                    name="gender">
                                                    <option value="1" {{ $gender == 1 ? 'selected' : '' }}>Male
                                                    </option>
                                                    <option value="2" {{ $gender == 2 ? 'selected' : '' }}>Female
                                                    </option>
                                                    <option value="3" {{ $gender == 3 ? 'selected' : '' }}>Other
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="exampleInputUsername1">ZIP CODE</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    placeholder="Zip Code" aria-label="Usersname" name="zip_code"
                                                    value="{{ $zip_code }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Profile Image</label>
                                                <input type="file" class="form-control form-control-sm"
                                                    id="profileImageInput" name="profile_image"
                                                    accept="image/png, image/gif, image/jpeg">
                                                <div>
                                                    <img id="profileImagePreview"
                                                        src="{{ !empty($profile_image) ? asset('public/uploads/profile_image/' . $profile_image) : asset('public/admin_assets/images/faces/face28.jpg') }}"
                                                        alt="Profile Preview"
                                                        style="max-width: 150px; border: 1px solid #ddd; padding: 5px;">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">BUSINESS BANNER</label>
                                                <input type="file" class="form-control form-control-sm"
                                                    id="shop_banner" name="shop_banner"
                                                    accept="image/png, image/gif, image/jpeg">
                                                <div>
                                                    <img id="shop_bannerPreview"
                                                        src="{{ !empty($shop_banner) ? asset('public/uploads/shop_image/' . $shop_banner) : asset('public/admin_assets/images/no_image.png') }}"
                                                        alt="shop Preview"
                                                        style="max-width: 150px; border: 1px solid #ddd; padding: 5px;">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">BUSINESS PHOTO</label>
                                                <input type="file" class="form-control form-control-sm" id="shop_logo"
                                                    name="shop_logo" accept="image/png, image/gif, image/jpeg">
                                                <div>
                                                    <img id="shop_logoPreview"
                                                        src="{{ !empty($shop_logo) ? asset('public/uploads/shop_image/' . $shop_logo) : asset('public/admin_assets/images/no_image.png') }}"
                                                        alt="shop Preview"
                                                        style="max-width: 150px; border: 1px solid #ddd; padding: 5px;">
                                                </div>
                                            </div>



                                            <div class="form-group col-md-6">
                                                <input type="hidden" name="user_id" value="{{ $user_id }}">
                                                <label for="exampleInputUsername1">ENTITY REPRESENTATIVE NAME</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    placeholder="Entity Representative Name" aria-label="Username"
                                                    name="first_name" value="{{ $first_name }}">
                                            </div>
                                            {{-- <div class="form-group col-md-6">
                              <label for="exampleInputUsername1">Last Name</label>
                              <input  type="text" class="form-control form-control-sm" placeholder="Last Name" aria-label="Username" name="last_name" value="{{$last_name}}">
                            </div> --}}
                                            <div class="form-group col-md-6">
                                                <input type="hidden" id="current_user_id" value="{{ $user_id }}">
                                                <label for="examspleInputEmail1">USER NAME</label>
                                                <input required type="text" class="form-control form-control-sm"
                                                    placeholder="User Name" id="user_name_input" name="user_name"
                                                    value="{{ $user_name }}" oninput="checkUsernameAvailability()">
                                                <small id="username_status"
                                                    style="font-size: 13px; display: none;"></small>
                                            </div>


                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">EMAIL ADDRESS</label>
                                                <input type="email" class="form-control form-control-sm"
                                                    id="exampleInputEmail1" placeholder="Email" name="email"
                                                    value="{{ $email }}" autocomplete="off" readonly
                                                    onfocus="this.removeAttribute('readonly')">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">PASSWORD</label>
                                                <input required type="password" class="form-control form-control-sm"
                                                    id="exampleInputEmail1" placeholder="Password" name="password"
                                                    value="" autocomplete="off" readonly
                                                    onfocus="this.removeAttribute('readonly')">
                                            </div>





                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Services</label>
                                                <ul>
                                                    @foreach ($services as $service)
                                                        <li>
                                                            <input type="checkbox" name="service_id[]"
                                                                value="{{ $service->id }}"
                                                                {{ in_array($service->id, $seller_service_ids) ? 'checked' : '' }}>
                                                            {{ $service->service_icon }} &nbsp;
                                                            {{ $service->service_name }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>



                                        <input type="hidden" name="user_time" value="" id="user_timezone">
                                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                                    </form>
                                </div>

                                <!--Coach Professional Profile-->
                                <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                                </div>
                                <div class="tab-pane" id="messages" role="tabpanel" aria-labelledby="messages-tab">
                                    Thired</div>
                                <div class="tab-pane" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                                    Fourth</div>
                            </div>
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
        document.querySelectorAll('#latitude, #longitude').forEach(input => {
            input.addEventListener('keypress', function(e) {
                const char = String.fromCharCode(e.which);
                const isValid = /[0-9.-]/.test(char);
                if (!isValid) {
                    e.preventDefault(); // block letters and symbols
                }
            });

            input.addEventListener('paste', function(e) {
                const pasteData = (e.clipboardData || window.clipboardData).getData('text');
                if (!/^[-]?\d*\.?\d*$/.test(pasteData)) {
                    e.preventDefault(); // block pasting invalid strings
                }
            });
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            document.getElementById("user_timezone").value = userTimezone;
        });
        $(document).ready(function() {
            $(document).on('change', '#country', function() {
                var cid = this.value; //let cid = $(this).val(); we cal also write this.
                $.ajax({
                    url: "{{ url('/admin/getcityByCountry') }}",
                    type: "POST",
                    datatype: "json",
                    data: {
                        country_id: cid,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        $('#city').html('<option value="">Select City</option>');
                        $.each(result.city, function(key, value) {
                            $('#city').append('<option value="' + value.city_id + '">' +
                                value.city_name + '</option>');
                        });
                    },
                    errror: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $('#state').change(function() {
                var sid = this.value;
                $.ajax({
                    url: "{{ url('/admin/getcity') }}",
                    type: "POST",
                    datatype: "json",
                    data: {
                        state_id: sid,
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
