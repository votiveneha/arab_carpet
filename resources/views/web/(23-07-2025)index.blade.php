@extends('web.common.layout') @section('content')
<style>
    .select2-results__option,
    .select2-selection__rendered {
        text-transform: uppercase;
    }

    #searchBtn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

</style>
<!-- Hero Section start-->
<section class="hero-section text-center">
    <h1 class="filetrs-text-add">{{ __('messages.head_note1') }}</br>{{ __('messages.head_note2') }}</h1>
    <form class="search-bar d-flex flex-wrap justify-content-center gap-2 mt-4" method="get" action="{{route('productList')}}">
        
        <div class="access-auto-parts">
            <div class="filter-box">
                <input type="hidden" name="user_latitude" id="user_latitude">
                <input type="hidden" name="user_longitude" id="user_longitude">

                <div class="dropdown-row one">
                    <select class="select2" id="year" name="year">
                        <option value="">{{ __('messages.YEAR') }}</option>
                        @for ($year = date('Y'); $year >= 1960; $year--)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endfor
                    </select>

                    <select class="select2" id="brand" name="brand_id">
                        <option value="">{{ __('messages.MAKE') }}</option>
                        @if($brand)
                            @foreach($brand as $brands)
                                <option value="{{ $brands->id }}">{{ $brands->brand_name }}</option>
                            @endforeach
                        @endif
                    </select>
                    <select class="select2" id="model" name="model_id">
                    <option value="">{{ __('messages.MODEL') }}</option>
                    
                    </select>
                </div>
                <div class="dropdown-row two">
                    <select class="select2" id="category" name="category_id">
                        <option value="">{{ __('messages.PART TYPE') }}</option>
                        @if($category)
                            @foreach($category as $categorys)
                                <option value="{{ $categorys->id }}">{{ $categorys->category_name }}</option>
                            @endforeach
                        @endif
                    </select>

                    <select class="select2" id="subcategory" name="subcategory_id">
                        <option value="">{{ __('messages.PART') }}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="location-filter-add">
            <!-- <select class="select2" id="country" name="country_id">
                <option value="">{{ __('messages.ALL Country') }}</option>
                @if($country)
                    @foreach($country as $countrys)
                        <option value="{{ $countrys->country_id }}">{{ $countrys->country_name }}</option>
                    @endforeach
                @endif
            </select> -->
            <select class="select2" id="city" name="city_id">
                <option value="">{{ __('messages.ALL Location') }}</option>
                 @if($city)
                    @foreach($city as $citys)
                        <option value="{{ $citys->city_id }}">{{ $citys->city_name }}</option>
                    @endforeach
                @endif
            </select>
            <i class="bi bi-geo-alt-fill" id="user_location"></i>
        </div>
    </div>


        <div class="search-btn-add">
            <button class="filter-search-btn" disabled id="searchBtn">{{ __('messages.search') }}</button>
        </div>
    </form>
</section>


@endsection @push('scripts')

<script>
    function checkFilters() {
        const year = $('#year').val();
        const brand = $('#brand').val();
        const model = $('#model').val();
        const category = $('#category').val();
        const subcategory = $('#subcategory').val();

        const allSelected = year && brand && model && category && subcategory;

        $('#searchBtn').prop('disabled', !allSelected);
    }

    $(document).ready(function () {
        checkFilters(); // initial check

        // Trigger check when any select2 dropdown changes
        $('#year, #brand, #model, #category, #subcategory').on('change', function () {
            checkFilters();
        });
    });
</script>
<script>
  $(document).ready(function() {
    $('.select2').select2();
  });
</script>

<script>
    $(document).ready(function () {
        $(document).on("change", "#brand", function () {
            var cid = this.value; //let cid = $(this).val(); we cal also write this.
            $.ajax({
                url: "{{url('/admin/getModel')}}",
                type: "POST",
                datatype: "json",
                data: {
                    brand_id: cid,
                    _token: "{{csrf_token()}}",
                },
                success: function (result) {
                    $("#model").html('<option value="">{{ __("messages.MODEL") }}</option>');
                    $.each(result.city, function (key, value) {
                        $("#model").append('<option value="' + value.id + '">' + value.model_name + "</option>");
                    });
                },
                errror: function (xhr) {
                    console.log(xhr.responseText);
                },
            });
        });

        $("#category").change(function () {
            var sid = this.value;
            $.ajax({
                url: "{{url('/admin/getSubcategory')}}",
                type: "POST",
                datatype: "json",
                data: {
                    category_id: sid,
                    _token: "{{csrf_token()}}",
                },
                success: function (result) {
                    console.log(result);
                    $("#subcategory").html('<option value="">{{ __("messages.PART") }}</option>');
                    $.each(result.subcat, function (key, value) {
                        $("#subcategory").append('<option value="' + value.id + '">' + value.subcat_name + "</option>");
                    });
                },
                errror: function (xhr) {
                    console.log(xhr.responseText);
                },
            });
        });

        $("#country").change(function () {
            var sid = this.value;
            $.ajax({
                url: "{{url('/admin/getcityByCountry')}}",
                type: "POST",
                datatype: "json",
                data: {
                    country_id: sid,
                    _token: "{{csrf_token()}}",
                },
                success: function (result) {
                    console.log(result);
                    $("#city").html('<option value="">{{ __("messages.ALL Location") }}</option>');
                    $.each(result.city, function (key, value) {
                        $("#city").append('<option value="' + value.city_id + '">' + value.city_name + "</option>");
                    });
                },
                errror: function (xhr) {
                    console.log(xhr.responseText);
                },
            });
        });
    });
    
</script>
<script>
    $('#user_location').click(function(){
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    console.log("Detected location:", lat, lng);

                    document.getElementById("user_latitude").value = lat;
                    document.getElementById("user_longitude").value = lng;
                },
                function (error) {
                    console.warn("Geolocation error:", error.message);

                    // Show user-friendly alert
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            alert("Please allow location access to use this feature.");
                            break;
                        case error.POSITION_UNAVAILABLE:
                            alert("Location information is unavailable.");
                            break;
                        case error.TIMEOUT:
                            alert("Location request timed out.");
                            break;
                        default:
                            alert("An unknown error occurred while getting your location.");
                    }
                }
            );
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    });

    // document.addEventListener("DOMContentLoaded", function () {
    //     if (navigator.geolocation) {
    //         navigator.geolocation.getCurrentPosition(
    //             function (position) {
    //                 const lat = position.coords.latitude;
    //                 const lng = position.coords.longitude;
    //                 console.log("Detected location:", lat, lng);

    //                 document.getElementById("user_latitude").value = lat;
    //                 document.getElementById("user_longitude").value = lng;
    //             },
    //             function (error) {
    //                 console.warn("Geolocation error:", error.message);
    //             }
    //         );
    //     } else {
    //         alert("Geolocation is not supported by this browser.");
    //     }
    // });
</script>

<!-- <script>
    document.addEventListener("DOMContentLoaded", function () {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    console.log("Detected location:", lat, lng);

                    document.getElementById("user_latitude").value = lat;
                    document.getElementById("user_longitude").value = lng;
                },
                function (error) {
                    console.warn("Geolocation error:", error.message);
                }
            );
        } else {
            console.warn("Geolocation is not supported by this browser.");
        }
    });
</script> -->


@endpush
