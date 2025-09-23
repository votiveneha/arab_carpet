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
    <h1 class="filetrs-text-add">STRUCTURED ACCESS TO <br> USED AUTO PARTS IN GCC</h1>
    <form class="search-bar d-flex flex-wrap justify-content-center gap-2 mt-4" method="post" action="{{route('productList')}}">
        @csrf
        <div class="access-auto-parts">
            <div class="filter-box">
                <input type="hidden" name="user_latitude" id="user_latitude">
                <input type="hidden" name="user_longitude" id="user_longitude">

                <div class="dropdown-row one">
                    <select class="select2" id="year" name="year">
                        <option value="">YEAR</option>
                        @if($make_year)
                            @foreach($make_year as $make_years)
                                <option value="{{ $make_years->year_english }}">{{ $make_years->year_english }}</option>
                            @endforeach
                        @endif
                    </select>

                    <select class="select2" id="brand" name="brand_id">
                        <option value="">MAKE</option>
                        @if($brand)
                            @foreach($brand as $brands)
                                <option value="{{ $brands->id }}">{{ $brands->brand_name }}</option>
                            @endforeach
                        @endif
                    </select>
                    <select class="select2" id="model" name="model_id">
                    <option value="">MODEL</option>
                    
                    </select>
                </div>
                <div class="dropdown-row two">
                    <select class="select2" id="category" name="category_id">
                        <option value="">PART TYPE</option>
                        @if($category)
                            @foreach($category as $categorys)
                                <option value="{{ $categorys->id }}">{{ $categorys->category_name }}</option>
                            @endforeach
                        @endif
                    </select>

                    <select class="select2" id="subcategory" name="subcategory_id">
                        <option value="">PART</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="location-filter-add">
            <select class="select2" id="country" name="country_id">
                <option value="">ALL Country</option>
                @if($country)
                    @foreach($country as $countrys)
                        <option value="{{ $countrys->country_id }}">{{ $countrys->country_name }}</option>
                    @endforeach
                @endif
            </select>
            <select class="select2" id="city" name="city_id">
                <option value="">ALL Location</option>
                
            </select>
            <i class="bi bi-geo-alt-fill" id="testBtn"></i>
        </div>
    </div>


        <div class="search-btn-add">
            <button class="filter-search-btn" disabled id="searchBtn">SEARCH</button>
        </div>
    </form>
</section>


<!-- Google Map Modal -->
<div class="modal fade" id="mapModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Map</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0">
        <div id="googleMap" style="width:100%; height:400px;"></div>
      </div>
    </div>
  </div>
</div>



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




<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCfbbmUkl56UuSeZ5nSOwsKTNxplmnheuU&libraries=marker&language=en"></script>
<script>
let googleMapInstance;
let googleMapMarker;

document.getElementById('testBtn').addEventListener('click', function () {
    const lat = parseFloat(document.getElementById("user_latitude").value);
    const lng = parseFloat(document.getElementById("user_longitude").value);

    if (isNaN(lat) || isNaN(lng)) {
        alert("Invalid coordinates");
        return;
    }

    const latlng = { lat, lng };

    const modal = new bootstrap.Modal(document.getElementById('mapModal'));
    modal.show();

    setTimeout(() => {
        if (!googleMapInstance) {
            googleMapInstance = new google.maps.Map(document.getElementById("googleMap"), {
                center: latlng,
                zoom: 14,
            });
        } else {
            googleMapInstance.setCenter(latlng);
        }

        if (googleMapMarker) {
            googleMapMarker.map = null;
        }

        googleMapMarker = new google.maps.marker.AdvancedMarkerElement({
            position: latlng,
            map: googleMapInstance,
            title: 'You are here'
        });
    }, 300);
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
                    $("#model").html('<option value="">Select Model</option>');
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
                    $("#subcategory").html('<option value="">Select Subcategory</option>');
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
                    $("#city").html('<option value="">ALL Location</option>');
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
</script>


@endpush
