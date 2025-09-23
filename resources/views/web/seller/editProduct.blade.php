@extends('web.seller.layout.layout')

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
                
                  
                  $product_img_id=$product_image="";
                  if($image)
                  {
                    $product_img_id=$image->id;
                   // $product_id=$image->product_id;
                    $product_image=$image->product_image;
                  }
                  
                ?>
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('seller.productList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Product List</a>
                    <h4 class="card-title"> Product Management</h4>
                    <p class="card-description"> Add / Update Product  </p>
                    <form class="forms-sample" method="post" action="{{route('seller.addProduct')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">
                        <input type="hidden" name="product_id" value="{{$product_detail->id}}">
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Brand : <strong>{{$product_detail->brand_name}}</strong></label>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Make Model : <strong>{{$product_detail->model_name}}</strong></label>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Category : <strong>{{$product_detail->category_name}}</strong></label>
                          
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Subcategory : <strong>{{$product_detail->subcategory_name}}</strong></label>
                          
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Generation : <strong>{{$product_detail->start_year}} - {{$product_detail->end_year}}</strong></label>
                          
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Part Type : <strong>{{$product_detail->part_type_label}}</strong></label>
                          
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Product Quantity</label>
                          <input required type="text" class="form-control form-control-sm" placeholder="Product quantity" aria-label="Username" name="quantity" value="{{$product_detail->quantity}}">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Product Price</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text bg-primary text-white">$</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" placeholder="price($)" maxlength="5" name="product_price" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="{{$product_detail->product_price}}">
                          </div>
                        </div>
                        <div class="form-group col-md-12">
                          <label for="exampleInputUsername1">Product Note</label>
                          <input  type="text" class="form-control form-control-sm" placeholder="Product Note" aria-label="Username" name="product_note" value="{{$product_detail->product_note}}">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Product Description</label>
                          <input  type="text" class="form-control form-control-sm" placeholder="Product Description English" aria-label="Username" name="product_description" value="{{$product_detail->product_description}}" required>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Product Description Arabic</label>
                          <input  type="text" class="form-control form-control-sm" placeholder="Product Description  Arabic" aria-label="Username" name="product_description_ar" value="{{$product_detail->product_description_ar}}">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Product Description French</label>
                          <input  type="text" class="form-control form-control-sm" placeholder="Product Description French" aria-label="Username" name="product_description_fr" value="{{$product_detail->product_description_fr}}">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Product Description Russian</label>
                          <input  type="text" class="form-control form-control-sm" placeholder="Product Description Russian" aria-label="Username" name="product_description_ru" value="{{$product_detail->product_description_ru}}">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Product Description Dari</label>
                          <input  type="text" class="form-control form-control-sm" placeholder="Product Description Dari" aria-label="Username" name="product_description_fa" value="{{$product_detail->product_description_fa}}">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Product Description Urdu</label>
                          <input  type="text" class="form-control form-control-sm" placeholder="Product Description Urdu" aria-label="Username" name="product_description_ur" value="{{$product_detail->product_description_ur}}">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Product Milage</label>
                          <input  type="text" class="form-control form-control-sm" placeholder="Product Milage" aria-label="Username" name="product_milage" value="{{$product_detail->product_milage}}">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Product Type</label>
                          <select required class="form-select form-select-sm" name="product_type">
                            <option value='1' {{$product_detail->product_type==1?'selected':''}}>EXCELLENT</option>
                            <option value='2' {{$product_detail->product_type==2?'selected':''}}>GOOD</option>
                            <option value='3' {{$product_detail->product_type==3?'selected':''}}>NOT WORKING</option>
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Product Image</label>
                          <input type="hidden" name="product_image_id" value="{{$product_img_id}}">
                          <input type="file" class="form-control form-control-sm" id="product_image" name="product_image" accept="image/png, image/gif, image/jpeg" @if(empty($product_image)) required @endif>
                           @if(!empty($product_image))
                            <div class="mt-1 uploaded-file">
                              {{-- <a href="{{ asset('/public/uploads/product_image/' . $product_image) }}" target="_blank">{{ $product_image }}</a> --}}
                              <img id="image_preview" src="{{ asset('/public/uploads/product_image/' . $product_image) }}" alt="Preview" style=" max-height: 150px; margin-top: 10px;" />
                            </div>
                          @endif
                          <img id="image_preview" src="#" alt="Preview" style="display:none; max-height: 150px; margin-top: 10px;" />
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
          document.getElementById('product_image').addEventListener('change', function(event) {
            const input = event.target;
            const preview = document.getElementById('image_preview');

            if (input.files && input.files[0]) {
              const reader = new FileReader();

              reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
              };

              reader.readAsDataURL(input.files[0]);
            } else {
              preview.src = '#';
              preview.style.display = 'none';
            }
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