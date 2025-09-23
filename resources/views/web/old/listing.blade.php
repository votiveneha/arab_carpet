@extends('web.common.layout') 
@section('content')	
<style>
     .select2-results__option,
.select2-selection__rendered {
  text-transform: uppercase;
}
</style>
    <section class="page-header listing_page hero-section">
        <div class="container">
            <div class="page-header_wrap">
                <div class="page-heading">
                    <h1>Search Results</h1>
                </div>
                <div class="filter-grid">
                    <form id="filterForm" class="search-bar d-flex flex-wrap justify-content-center gap-2 mt-4" method="GET">
                        <div class="product-filter-add-label">
                            <label>Year</label>
                            <select class="form-select select2" name="year" id="year">
                                <option value="">Select Year</option>
                                @if($make_year)
                                @foreach($make_year as $make_years)
                                    <option value="{{$make_years->year_english}}" {{ request('year') == $make_years->year_english ? 'selected' : '' }}>{{$make_years->year_english}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="product-filter-add-label">
                            <label>Make</label>
                            <select class="form-select select2" id="brand" name="brand_id">
                                <option value="">Select Make</option>
                                @if($brand)
                                @foreach($brand as $brands)
                                    <option value="{{$brands->id}}" {{ request('brand_id') == $brands->id ? 'selected' : '' }}>{{$brands->brand_name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="product-filter-add-label">
                            <label>Model</label>
                            <select class="form-select select2" id="model" name="model_id">
                                <option value="">Select Model</option>
                                @foreach($models as $model)
                                    <option value="{{ $model->id }}" {{ request('model_id') == $model->id ? 'selected' : '' }}>
                                        {{ $model->model_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- <select class="form-select select2" id="generation" name="generation_id">
                            <option value="">Select Generation</option>
                            @foreach($generation as $generations)
                                <option value="{{ $generations->id }}" {{ request('generation_id') == $generations->id ? 'selected' : '' }}>
                                    {{ $generations->start_year }} - {{ $generations->end_year }}
                                </option>
                            @endforeach
                        </select> -->
                        <div class="product-filter-add-label">
                            <label>Part Type</label>
                            <select class="form-select select2" id="category" name="category_id">
                                <option value="">Select Part Type</option>
                                @if($category)
                                @foreach($category as $categorys)
                                    <option value="{{$categorys->id}}" {{ request('category_id') == $categorys->id ? 'selected' : '' }}>{{$categorys->category_name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="product-filter-add-label">
                            <label>Part</label>
                            <select class="form-select select2" id="subcategory" name="subcategory_id">
                                <option value="">Select Part</option>
                                @foreach($subcategory as $subcategorys)
                                    <option value="{{ $subcategorys->id }}" {{ request('subcategory_id') == $subcategorys->id ? 'selected' : '' }}>
                                        {{ $subcategorys->subcat_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- <select class="form-select" name="area" id="area">
                            <option value="">Select Area</option>
                        </select> -->
                        <!-- <select class="form-select select2" name="sort" id="sort">
                            <option value="">Select Sort</option>
                            <option value="1" {{ request('sort') == 1 ? 'selected' : '' }}>New</option>
                            <option value="2" {{ request('sort') == 2 ? 'selected' : '' }}>Old</option>
                            <option value="3" {{ request('sort') == 3 ? 'selected' : '' }}>Refurbished</option>
                        </select> -->
                        <!-- <input type="text" name="postal_code" class="form-control postal-code" placeholder="Enter Postal Code"> -->
                        <button type="submit" class="filter-search-btn"><i class="bi bi-search"></i></button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Dark Overlay-->
        <div class="dark-overlay"></div>
    </section>

        
	<section class="listing-page">
        <div class="container">
            <div class="row">
                <div class="col-md-12	 col-md-push-3">
                    <div id="productList">
                        @include('web.partial_product_list', ['products' => $products])
                    </div>
                </div>      
            </div>
        </div>
    </section>

@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select an option",
            allowClear: true
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
            $('#model').html('<option value="">Select Model</option>');
            $.each(result.city, function(key, value) {
            $('#model').append('<option value="' +value.id+ '">' +value.model_name+ '</option>');
            });
        },
        errror: function(xhr) {
            console.log(xhr.responseText);
            }
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
            $('#subcategory').html('<option value="">Select Subcategory</option>');
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