@extends('admin.layouts.layout')

@section('content')

<style>
  .ck-editor__editable {
    min-height: 300px !important; /* Or whatever height you want */
  }
</style>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <?php
                
                  $product_id=$brand_id=$model_id=$category_id=$subcategory_id=$product_title=$product_image=$product_details=$product_price="";
                  
                  if($product_detail)
                  {
                    $product_id=$product_detail->id;
                    $brand_id=$product_detail->brand_id;
                    $model_id=$product_detail->model_id;
                    $category_id=$product_detail->category_id;
                    $subcategory_id=$product_detail->subcategory_id;
                    $product_title=$product_detail->product_title;
                    $product_details=$product_detail->product_detail;
                    $product_price=$product_detail->product_price;
                  }
                  
                  $admin_product_img_id=$admin_product_id=$product_image="";
                  if($image)
                  {
                    $admin_product_img_id=$image->id;
                    $admin_product_id=$image->admin_product_id;
                    $product_image=$image->product_image;
                  }
                  
                ?>
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.adminProductList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Admin Product List</a>
                    <h4 class="card-title">Admin Product Management</h4>
                    <p class="card-description"> Add / Update Admin Product  </p>
                    <form class="forms-sample" method="post" action="{{route('admin.addAdminProduct')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">
                        <input type="hidden" name="product_id" value="{{$product_id}}">
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Brand</label>
                          <select required class="form-select form-select-sm" id="brand" name="brand_id">
                            <option value="">Select Brand</option>  
                            @if($brand)
                            @foreach($brand as $brands)
                              <option value="{{$brands->id }}" {{$brand_id==$brands->id?'selected':''}}>{{$brands->brand_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Make Model</label>
                          <select required class="form-select form-select-sm" id="model" name="model_id">
                            <option>Select Model</option>
                            @if($model)
                            @foreach($model as $models)
                            <option value="{{$models->id }}" {{$model_id==$models->id?'selected':''}}>{{$models->model_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Category</label>
                          <select class="form-select form-select-sm" id="category" name="category_id">
                            <option value="">Select category</option>
                            @if($category)
                            @foreach($category as $categorys)
                            <option value="{{$categorys->id }}" {{$category_id==$categorys->id?'selected':''}}>{{$categorys->category_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Subcategory</label>
                          <select class="form-select form-select-sm" id="subcategory" name="subcategory_id">
                            <option value="">Select Subcategory</option>
                            @if($subcategory)
                            @foreach($subcategory as $subcategorys)
                            <option value="{{$subcategorys->id }}" {{$subcategory_id==$subcategorys->id?'selected':''}}>{{$subcategorys->subcat_name}}</option>
                            @endforeach
                            @endif
                            
                          </select>
                        </div>
                        
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Product Image</label>
                          <input type="hidden" name="product_image_id" value="{{$admin_product_img_id}}">
                          <input type="file" class="form-control form-control-sm" id="exampleInputEmail1" name="product_image" accept="image/png, image/gif, image/jpeg">
                           @if(!empty($product_image))
                            <div class="mt-1 uploaded-file">
                              <a href="{{ asset('/public/uploads/product_image/' . $product_image) }}" target="_blank">{{ $product_image }}</a>
                            </div>
                          @endif
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Make Year</label>
                          
                          <select required class="js-example-basic-multiple w-100" multiple="multiple" name="make_year[]">
                            @if($make_year)
                            @foreach($make_year as $year)
                              <option value="{{$year->id}}" {{ in_array($year->id, $selectedYearIds) ? 'selected' : '' }}>{{$year->year_english}}</option>
                            @endforeach
                            @endif
                            
                          </select>
                        </div>
                        <div class="form-group col-md-12">
                          <label for="exampleInputEmail1">Product Detail</label>
                          <textarea  class="form-control form-control-sm" id="video-introduction" name="product_detail" rows="1" placeholder="Enter Product Detail here..." >{{$product_details}}</textarea>
                        </div>
                      </div>
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
          document.addEventListener("DOMContentLoaded", function () {
              const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
              document.getElementById("user_timezone").value = userTimezone;
          });
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
        <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
          <script>
              ClassicEditor
                  .create(document.querySelector('#video-introduction'))
                  .catch(error => {
                      console.error(error);
                  });
          </script>
        @endpush