@extends('web.common.layout') 
@section('content')
<style>
     .select2-results__option,
.select2-selection__rendered {
  text-transform: uppercase;
}
</style>
        <!-- Hero Section start-->
        <section class="hero-section text-center py-5">
            <h1 class="hero-title">Inventory. Access. Accountability.</h1>
            <p class="hero-subtext">
                The GCC’s structured auto parts directory — built for scrappies, trusted by agencies.
            </p>

            <form class="search-bar d-flex flex-wrap justify-content-center gap-2 mt-4" method="post" action="{{route('productList')}}">
                @csrf
                <div class="product-filter-add-label">
                    <label>Year</label>
                    <select class="form-select select2" id="year" name="year">
                        <option value="">Select Generation</option>
                        @if($make_year)
                        @foreach($make_year as $make_years)
                            <option value="{{$make_years->year_english}}">{{$make_years->year_english}}</option>
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
                            <option value="{{$brands->id}}">{{$brands->brand_name}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>

                <div class="product-filter-add-label">
                    <label>Model</label>
                    <select class="form-select select2" id="model" name="model_id">
                        <option value="">Select Model</option>
                    </select>
                </div>

                <div class="product-filter-add-label">
                    <label>Part Type</label>
                    <select class="form-select select2" id="category" name="category_id">
                        <option value="">Select Part Type</option>
                        @if($category)
                        @foreach($category as $categorys)
                            <option value="{{$categorys->id}}">{{$categorys->category_name}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>

                <div class="product-filter-add-label">
                    <label>Part</label>
                    <select class="form-select select2" id="subcategory" name="subcategory_id">
                        <option value="">Select Part</option>
                    </select>
                </div>

                <!-- <select class="form-select">
                    <option>Select Area</option>
                </select> -->
                <!-- <select class="form-select select2" name="sort" id="sort">
                    <option value="">Select Sort</option>
                    <option value="1">New</option>
                    <option value="2">Old</option>
                    <option value="3">Refurbished</option>
                </select> -->
                <!-- <input type="text" class="form-control postal-code" placeholder="Enter Postal Code" /> -->
                <button class="filter-search-btn"><i class="bi bi-search"></i></button>
            </form>
        </section>
        <!-- Hero Section end-->

        <!-- how-it-works-start -->
        <div class="how-it-works">
            <h1 class="text-center top-text">How It Works</h1>
            <p class="text-center it-text-work">Dolor sit amet, consectetur adipiscing elit. Sed sit amet rcus nun Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sit amet rcus nunc. Duis egestas ac</p>
            <div class="sellers-for-add">
                <div class="container">
                    <p class="text-center text-white"><i class="bi bi-car-front-fill"></i> PROCESS OVERVIEW</p>
                    <h1 class="text-center inner-text text-white">For Sellers</h1>
                    <div class="row how-it-inner">
                        <div class="col">
                            <div class="card">
                                <img src="{{ url('/public') }}/web_assets/images/file-three.png" class="card-img-top" alt="file-three" />
                                <div class="card-body">
                                    <h5 class="card-title text-center">Upload By Generation</h5>
                                    <p class="card-text text-center">
                                        Dolor sit amet, consectetur adipiscing elit. Sed sit amet rcus nun Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sit amet rcus nunc. Duis egestas ac
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card">
                                <img src="{{ url('/public') }}/web_assets/images/file-one.png" class="card-img-top" alt="file-one" />

                                <div class="card-body">
                                    <h5 class="card-title text-center">Track</h5>
                                    <p class="card-text text-center">
                                        Dolor sit amet, consectetur adipiscing elit. Sed sit amet rcus nun Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sit amet rcus nunc. Duis egestas ac
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card">
                                <img src="{{ url('/public') }}/web_assets/images/file-two.png" class="card-img-top" alt="file-two" />
                                <div class="card-body">
                                    <h5 class="card-title text-center">Show Inventory</h5>
                                    <p class="card-text text-center">Dolor sit amet, consectetur adipiscing elit. Sed sit amet rcus nun Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sit amet rcus nunc. Duis egestas ac</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sellers-for-add buyers-for">
                <div class="container">
                    <p class="text-center text-white"><i class="bi bi-car-front-fill"></i> PROCESS OVERVIEW</p>
                    <h1 class="text-center inner-text text-white">For Buyers</h1>
                    <div class="row how-it-inner">
                        <div class="col">
                            <div class="card">
                                <img src="{{ url('/public') }}/web_assets/images/buy-file-one.png" class="card-img-top" alt="file-three" />
                                <div class="card-body">
                                    <h5 class="card-title text-center">Search</h5>
                                    <p class="card-text text-center">
                                        Dolor sit amet, consectetur adipiscing elit. Sed sit amet rcus nun Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sit amet rcus nunc. Duis egestas ac
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card">
                                <img src="{{ url('/public') }}/web_assets/images/buy-file-two.png" class="card-img-top" alt="file-one" />

                                <div class="card-body">
                                    <h5 class="card-title text-center">Select Parts</h5>
                                    <p class="card-text text-center">
                                        Dolor sit amet, consectetur adipiscing elit. Sed sit amet rcus nun Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sit amet rcus nunc. Duis egestas ac
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card">
                                <img src="{{ url('/public') }}/web_assets/images/buy-file-three.png" class="card-img-top" alt="file-two" />
                                <div class="card-body">
                                    <h5 class="card-title text-center">Contact Directly</h5>
                                    <p class="card-text text-center">Dolor sit amet, consectetur adipiscing elit. Sed sit amet rcus nun Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sit amet rcus nunc. Duis egestas ac</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- how-it-works-end -->

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
              
            //   var cid = this.value;   //let cid = $(this).val(); we cal also write this.
            //   $.ajax({
            //     url: "{{url('/admin/getgeneration')}}",
            //     type: "POST",
            //     datatype: "json",
            //     data: {
            //       model_id: cid,
            //       '_token':'{{csrf_token()}}'
            //     },
            //     success: function(result) {
            //       $('#generation').html('<option value="">Select Generation</option>');
            //       $.each(result.subcat, function(key, value) {
            //         $('#generation').append('<option value="' +value.id+ '">' +value.start_year+'-'+value.end_year+ '</option>');
            //       });
            //     },
            //     errror: function(xhr) {
            //         console.log(xhr.responseText);
            //       }
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
        @endpush
        