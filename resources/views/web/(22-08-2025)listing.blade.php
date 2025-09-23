@extends('web.common.layout') 
@section('content') 
<style>
.select2-results__option,
.select2-selection__rendered {
  text-transform: uppercase;
}
</style>

    <div class="breadcrumb">
        <div class="container">
            <ul class="breadcrumb-list">
                <li><a href="#">{{$header['year']}} {{$header['brand']}} {{$header['model']}}</a> &gt; </li>
                <li><a href="#">{{$header['category']}}</a> &gt; </li>
                <li><a href="#">{{$header['subcatagory']}}</a></li>
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
                        <h6 class="text-uppercase font-weight-bold">{{__('messages.filter_head1')}}</h6>
                        
                        <input type="hidden" name="user_latitude" id="user_latitude">
                        <input type="hidden" name="user_longitude" id="user_longitude">
                        
                        <select class="form-control mb-2 custom-select select2" name="year" id="year">
                        <option value="">{{__('messages.YEAR')}}</option>
                            @if($make_year)
                            @foreach($make_year as $make_years)
                                <option value="{{$make_years->year_english}}" {{ request('year') == $make_years->year_english ? 'selected' : '' }}>{{$make_years->year_english}}</option>
                            @endforeach
                            @endif
                        </select>

                        <select class="form-control mb-2 custom-select select2" id="brand" name="brand_id">
                        <option value="">{{__('messages.MAKE')}}</option>
                            @if($brand)
                            @foreach($brand as $brands)
                                <option value="{{$brands->id}}" {{ request('brand_id') == $brands->id ? 'selected' : '' }}>{{$brands->brand_name}}</option>
                            @endforeach
                            @endif
                        </select>

                        <select class="form-control mb-2 custom-select select2" id="model" name="model_id">
                        <option value="">{{__('messages.MODEL')}}</option>
                            @foreach($models as $model)
                                <option value="{{ $model->id }}" {{ request('model_id') == $model->id ? 'selected' : '' }}>
                                    {{ $model->model_name }}
                                </option>
                            @endforeach
                        </select>

                        <select class="form-control mb-2 custom-select select2"  id="category" name="category_id">
                            <option value="">{{__('messages.PART TYPE')}}</option>
                            @if($category)
                            @foreach($category as $categorys)
                                <option value="{{$categorys->id}}" {{ request('category_id') == $categorys->id ? 'selected' : '' }}>{{$categorys->category_name}}</option>
                            @endforeach
                            @endif
                        </select>

                        <select class="form-control mb-2 custom-select select2"  id="subcategory" name="subcategory_id">
                            <option value="">{{__('messages.PART')}}</option>
                            @foreach($subcategory as $subcategorys)
                                <option value="{{ $subcategorys->id }}" {{ request('subcategory_id') == $subcategorys->id ? 'selected' : '' }}>
                                    {{ $subcategorys->subcat_name }}
                                </option>
                            @endforeach
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
                            @if($cityall)
                                @foreach($cityall as $citys)
                                    <option value="{{ $citys->city_id }}" {{ request('city_id') == $citys->city_id ? 'selected' : '' }} >
                                        {{ $citys->city_name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <button class="btn btn-dark btn-block shop-search-btn">{{__('messages.search')}}</button>
                        <!-- <button class="btn btn-outline-secondary btn-block mt-2 shop-reset-btn">RESET</button> -->
                    </form>
                </div>

            <!-- Main Content -->
            <div class="col-md-9 shop-center-detail shop-product-list-right">
                <div class="product-listing">
                    @if($products)
                    @foreach($products as $product)
                    @php
                        $service = DB::table('seller_service')->where('seller_id', $product->seller_id)
                                        ->join('services','services.id','=','seller_service.service_id')->get();
                    @endphp
                    <!-- <a href="{{route('sellerMiniPage', $product->seller_id)}}"> -->
                    <div class="listing-item" onclick="goToMiniPage('{{ route('sellerMiniPage', $product->seller_id) }}')" style="cursor: pointer;">
                        <div class="new-ven-add">
                            <div class="vendor-info">
                                <div class="vendor-name">{{$product->shop_name}}</div>
                                <div class="vendor-icons">
                                    
                                    <div>
                                        @if($service)
                                        @foreach($service as $services)
                                            @if($services->service_id==1)
                                                <i class="bi bi-truck" data-bs-toggle="tooltip" title="{{$services->service_name}}"></i>
                                            @elseif($services->service_id==2)
                                                <i class="bi bi-globe" data-bs-toggle="tooltip" title="{{$services->service_name}}"></i>
                                            @elseif($services->service_id==3)
                                                <i class="bi bi-wrench" data-bs-toggle="tooltip" title="{{$services->service_name}}"></i>
                                            @else
                                                <i class="bi bi-award" data-bs-toggle="tooltip" title="{{$services->service_name}}"></i>
                                            @endif
                                        @endforeach
                                        @endif    
                                    </div>
                                    <div class="vendor-city">{{$product->city_name}} 
                                        @if($product->product_image!='')
                                        <!-- <a href="{{ asset('/public/uploads/product_image/' . $product->product_image) }}" target="_blank"> -->
                                            <i class="bi bi-camera-fill"></i>
                                        <!-- </a> -->
                                        @endif
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                        <!-- <a href="{{route('productDetail', $product->id)}}" class="track-product-click" data-product-id="{{ $product->id }}" data-seller-id="{{ $product->seller_id }}" data-admin-product-id="{{ $product->admin_product_id }}"> -->
                            <div class="product-details">
                                <p>{{$product->product_note}}</p>
                            </div>
                        <!-- </a> -->

                        <!-- <a href="{{route('productDetail', $product->id)}}" class="track-product-click" data-product-id="{{ $product->id }}" data-seller-id="{{ $product->seller_id }}" data-admin-product-id="{{ $product->admin_product_id }}"> -->
                            <div class="product-status">
                                {{$product->product_type==1?'EXCELLENT':($product->product_type==2?'GOOD':'NOT WORKING')}}
                                <p>{{ round($product->distance, 2) }} km</p>
                            </div>
                        <!-- </a> -->
                        
                        <div class="contact-options">
                            <div class="product-price">@if($product->product_price >0){{$product->product_price}} SAR @endif</div>
                            <div class="icons-part-add">

                                <!-- <a href="https://wa.me/{{ $product->mobile }}?text={{ urlencode('Hi, I am interested in your product on your website.') }}" target="_blank"> -->
                                    
                                    <button onclick="openWhatsApp(
                                    '{{ $product->mobile }}',
                                    '{{ $header['year'] }}',
                                    '{{ $product->brand_name}}',
                                    '{{ $product->model_name}}',
                                    '{{ $product->category_name}}',
                                    '{{ $product->subcategory_name}}',
                                    '{{ $product->stock_number}}',event)">
<i class="bi bi-whatsapp"></i></button>
                                <!-- </a> -->
                                 <a href="tel:{{ $product->mobile }}">
                                    <button><i class="bi bi-telephone"></i></button>
                                </a>
                                <button onclick="callNow('{{ $product->mobile }}', event)">
                                    <i class="bi bi-geo-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- </a> -->
                    @endforeach
                    @endif
                </div>
                <div class="pagination">
                    {{ $products->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            // placeholder: "Select an option",
            // allowClear: true
        });
    });
</script>
<script>
    function goToMiniPage(url) {
        window.location.href = url;
    }

    function openWhatsApp(mobile, year, brand, model, category, subcategory, stockNumber, event) {
            event.stopPropagation();
            event.preventDefault();

            const message = 
        `Hello, I found your part on ARABCARPART:
        ${year} > ${brand} > ${model} > ${category} > ${subcategory}
        Stock #${stockNumber}
        Is it still available?`;

            const url = `https://wa.me/${mobile}?text=${encodeURIComponent(message)}`;
            window.open(url, '_blank');
        }


    // function callNow(mobile, event) {
    //     event.stopPropagation(); // Prevent parent <a> click
    //     event.preventDefault();  // Prevent any default action

    //     window.location.href = `tel:${mobile}`; // Initiate call
    // }
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
    document.addEventListener("DOMContentLoaded", function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
<script>
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();

        fetch("{{ route('productList') }}?" + params, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById('productList').innerHTML = data;
        })
        .catch(err => console.error('Filter error:', err));
    });
</script>
<script>
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

    // $(document).on('change', '#model', function () {
              
    //     var cid = this.value;   //let cid = $(this).val(); we cal also write this.
    //     $.ajax({
    //     url: "{{url('/admin/getgeneration')}}",
    //     type: "POST",
    //     datatype: "json",
    //     data: {
    //         model_id: cid,
    //         '_token':'{{csrf_token()}}'
    //     },
    //     success: function(result) {
    //         $('#generation').html('<option value="">Select Generation</option>');
    //         $.each(result.subcat, function(key, value) {
    //         $('#generation').append('<option value="' +value.id+ '">' +value.start_year+'-'+value.end_year+ '</option>');
    //         });
    //     },
    //     errror: function(xhr) {
    //         console.log(xhr.responseText);
    //         }
    //     });
    // });

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

<script>
$(document).on('click', '.track-product-click', function (e) {
    e.preventDefault(); // prevent link from navigating before AJAX

    const $this = $(this);
    const productUrl = $this.attr('href');
    const productId = $this.data('product-id');
    const sellerId = $this.data('seller-id');
    const adminProductId = $this.data('admin-product-id');

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                .then(res => res.json())
                .then(data => {
                    const address = data.address || {};

                    $.ajax({
                        url: "{{ route('product.track.click') }}",
                        method: "POST",
                        data: {
                            _token: '{{ csrf_token() }}',
                            product_id: productId,
                            admin_product_id:adminProductId,
                            seller_id: sellerId,
                            latitude: lat,
                            longitude: lng,
                            country: address.country || '',
                            state: address.state || '',
                            city: address.city || address.town || address.village || ''
                        },
                        complete: function () {
                            // after tracking, continue navigation
                            window.location.href = productUrl;
                        }
                    });
                })
                .catch(() => {
                    // fallback redirect if API fails
                    window.location.href = productUrl;
                });
        }, () => {
            // fallback if geolocation blocked or failed
            window.location.href = productUrl;
        });
    } else {
        window.location.href = productUrl;
    }
});
</script>


@endpush