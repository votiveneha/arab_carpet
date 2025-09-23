@extends('web.common.layout') @section('content')
<style>
     .select2-results__option,
.select2-selection__rendered {
  text-transform: uppercase;
}
</style>
<div class="breadcrumb">
        <div class="container">
            <ul class="breadcrumb-list">
                <li><a href="#"> {{$product->brand_name}} {{$product->model_name}}</a> &gt; </li>
                <li><a href="#">{{$product->category_name}}</a> &gt; </li>
                <li><a href="#">{{$product->subcategory_name}}</a></li>
            </ul>
        </div>
    </div>

<div class="shop-detail-add shop-product-detail shop-product-list-add">
    <div class="container mt-4">
        <div class="row shop-inner-detail-content shop-product-inner">
            <!-- Sidebar Filters -->
            <div class="col-md-3 filter-box shop-filters-left-side custom-select-wrapper">
                <form method="get" action="{{route('productList')}}">
                    @csrf
                    <input type="hidden" name="user_latitude" id="user_latitude">
                    <input type="hidden" name="user_longitude" id="user_longitude">

                    <h6 class="text-uppercase font-weight-bold">{{__('messages.filter_head1')}}</h6>
                    <select class="form-control mb-2 custom-select select2" name="year" id="year">
                        <option value="">{{__('messages.YEAR')}}</option>
                        @for ($year = date('Y'); $year >= 1960; $year--)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                    <select class="form-control mb-2 custom-select select2" id="brand" name="brand_id">
                        <option value="">{{__('messages.MAKE')}}</option>
                            @if($brand)
                            @foreach($brand as $brands)
                                <option value="{{$brands->id}}" {{ $product->brand_id == $brands->id ? 'selected' : '' }}>{{$brands->brand_name}}</option>
                            @endforeach
                            @endif
                    </select>
                    <select class="form-control mb-2 custom-select select2" id="model" name="model_id">
                        <option value="">{{__('messages.MODEL')}}</option>
                            @if($model)
                            @foreach($model as $models)
                                <option value="{{$models->id}}" {{ $product->model_id == $models->id ? 'selected' : '' }}>{{$models->model_name}}</option>
                            @endforeach
                            @endif
                    </select>
                    <select class="form-control mb-2 custom-select select2"  id="category" name="category_id">
                        <option value="">{{__('messages.PART TYPE')}}</option>
                        @if($category)
                        @foreach($category as $categorys)
                            <option value="{{$categorys->id}}" {{ $product->category_id == $categorys->id ? 'selected' : '' }}>{{$categorys->category_name}}</option>
                        @endforeach
                        @endif
                    </select>
                    <select class="form-control mb-2 custom-select select2"  id="subcategory" name="subcategory_id">
                        <option value="">{{__('messages.PART')}}</option>
                        @if($subcategory)
                        @foreach($subcategory as $subcategorys)
                            <option value="{{$subcategorys->id}}" {{ $product->subcategory_id == $subcategorys->id ? 'selected' : '' }}>{{$subcategorys->subcat_name}}</option>
                        @endforeach
                        @endif
                    </select>
                    <!-- <select class="form-control mb-2 custom-select select2" id="country" name="country_id">
                        <option value="">ALL Country</option>
                                @if($country)
                                    @foreach($country as $countrys)
                                        <option value="{{ $countrys->country_id }}">{{ $countrys->country_name }}</option>
                                    @endforeach
                                @endif
                    </select> -->
                    <select class="form-control mb-2 custom-select select2"  id="city" name="city_id">
                        <option value="">{{__('messages.sector')}}</option>
                            @if($city)
                                @foreach($city as $citys)
                                    <option value="{{ $citys->city_id }}" {{ $user->city_id == $citys->city_id ? 'selected' : '' }}>{{ $citys->city_name }}</option>
                                @endforeach
                            @endif
                    </select>
                    <button class="btn btn-dark btn-block shop-search-btn">{{__('messages.search')}}</button>
                    <!-- <button class="btn btn-outline-secondary btn-block mt-2 shop-reset-btn">RESET</button> -->
                    </form>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 shop-center-detail">
                <div class="product-card">
                    <div class="inner-price-set">
                        <div class="card-header">
                            <span class="status not-working">{{$product->product_type==1?'EXCELLENT':($product->product_type==2?'GOOD':'NOT WORKING')}}</span>
                            <div class="price">@if($product->product_price >0){{$product->product_price}} <span>SAR</span> @endif</div>
                        </div>

                        <div class="seller-info">
                            <span><i class="bi bi-shop"></i>{{$shop->shop_name}} <span class="loc-add-text">{{$user->city_name}}</span></span>
                            <div class="icons">
                                <a href="https://wa.me/{{ $user->mobile }}?text={{ urlencode('Hello, I found your part on ARABCARPART: ' . $product->brand_name . '> ' . $product->model_name . '> ' . $product->category_name.'> ' .$product->subcategory_name. ' Stock #'. $product->stock_number .' Is it still available?') }}" target="_blank">
                                    <i class="bi bi-whatsapp"></i></a>
                                <a href="tel:+{{$user->mobile}}"><i class="bi bi-telephone-fill"></i></a>
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                        </div>
                    </div>

                    <div class="product-img-part">
                        <div class="description">
                            <h4 class="title">{{$product->product_note}}</h4>

                            <p><strong>{{ __('messages.Description') }} </strong></p>
                            <p>
                                {{$product->product_description}}
                            </p>
                        </div>

                        <div class="product-body">
                            <img src="{{ asset('/public/uploads/product_image/' . $product->product_image) }}" alt="product" />
                        </div>
                    </div>
                    <div class="features">
                        @if($service)
                        @foreach($service as $services)
                            @if($services->service_id==1)
                                <div class="feature">
                                    <span>{{$services->service_name}}</span>
                                    <i class="bi bi-truck"></i>
                                </div>
                            @elseif($services->service_id==2)
                                <div class="feature">
                                    <span>{{$services->service_name}}</span>
                                    <i class="bi bi-globe"></i>
                                </div>
                            @elseif($services->service_id==3)
                                <div class="feature">
                                    <span>{{$services->service_name}}</span>
                                    <i class="bi bi-wrench"></i>
                                </div>
                            @else
                                <div class="feature">
                                    <span>{{$services->service_name}}</span>
                                    <i class="bi bi-award"></i>
                                </div>
                            @endif
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection @push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            // placeholder: "Select an option",
            // allowClear: true
        });
    });
</script>
<script>
    /*
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
    */
    $(document).ready(function () {
    $(document).on('change', '#brand', function () {

        var cid = this.value;   //let cid = $(this).val(); we cal also write this.
        $.ajax({
        url: "{{url('/admin/getModel')}}",
        type: "POST",
        datatype: "json",
        data: {
            brand_id: cid,
            '_token':'{{csrf_token()}}'
        },
        success: function(result) {
            $('#model').html('<option value="">{{ __("messages.MODEL") }}</option>');
            $.each(result.city, function(key, value) {
            $('#model').append('<option value="' +value.id+ '">' +value.model_name+ '</option>');
            });
        },
        errror: function(xhr) {
            console.log(xhr.responseText);
            }
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

        $('#category').change(function () {
            var sid = this.value;
            $.ajax({
            url: "{{url('/admin/getSubcategory')}}",
            type: "POST",
            datatype: "json",
            data: {
                category_id: sid,
                '_token':'{{csrf_token()}}'
            },
            success: function(result) {
                console.log(result);
                $('#subcategory').html('<option value="">{{ __("messages.PART") }}</option>');
                $.each(result.subcat, function(key, value) {
                $('#subcategory').append('<option value="' +value.id+ '">' +value.subcat_name+ '</option>')
                });
            },
            errror: function(xhr) {
                console.log(xhr.responseText);
                }
            });
        });
    });
</script>
@endpush
