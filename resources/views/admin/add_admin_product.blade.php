@extends('admin.layouts.layout')

@section('content')

<style>
  .ck-editor__editable {
    min-height: 300px !important; /* Or whatever height you want */
  }
.select2-results__option,
.select2-selection__rendered {
  text-transform: uppercase;
}
</style>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <?php
                
                  $product_id=$brand_id=$model_id=$category_id=$subcategory_id=$product_image=$generation_id="";
                  $product_note=$product_description=$product_milage='';
                  if($product_detail)
                  {
                    $product_id=$product_detail->id;
                    $brand_id=$product_detail->brand_id;
                    $model_id=$product_detail->make_model_id;
                    $category_id=$product_detail->category_id;
                    $subcategory_id=$product_detail->subcategory_id;
                    $generation_id=$product_detail->generation_id;
                    $product_note=$product_detail->product_note;
                    $product_description=$product_detail->product_description;
                    $product_milage=$product_detail->product_milage;
                  }
                  
                  $admin_product_img_id=$product_image="";
                  if($image)
                  {
                    $admin_product_img_id=$image->id;
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
                          <label for="exampleInputEmail1">Make</label>
                          <select required class="form-select form-select-sm select-drop" id="brand" name="brand_id">
                            <option value="">Select Make</option>  
                            @if($brand)
                            @foreach($brand as $brands)
                              <option value="{{$brands->id }}" {{$brand_id==$brands->id?'selected':''}}>{{$brands->brand_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Model</label>
                          <select required class="form-select form-select-sm select-drop" id="model" name="model_id">
                            <option>Select Model</option>
                            @if($model)
                            @foreach($model as $models)
                            <option value="{{$models->id }}" {{$model_id==$models->id?'selected':''}}>{{$models->model_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Generation</label>
                          <select required class="form-select form-select-sm select-drop" id="generation" name="generation_id">
                            <option>Select Generation</option>
                            @if($generation)
                            @foreach($generation as $generations)
                            <option value="{{$generations->id }}" {{$generation_id==$generations->id?'selected':''}}>{{$generations->start_year}} - {{$generations->end_year}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Part Type</label>
                          <select class="form-select form-select-sm select-drop" id="category" name="category_id">
                            <option value="">Select Part Type</option>
                            @if($category)
                            @foreach($category as $categorys)
                            <option value="{{$categorys->id }}" {{$category_id==$categorys->id?'selected':''}}>{{$categorys->category_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Part</label>
                          <select class="form-select form-select-sm select-drop" id="subcategory" name="subcategory_id">
                            <option value="">Select Part</option>
                            @if($subcategory)
                            @foreach($subcategory as $subcategorys)
                            <option value="{{$subcategorys->id }}" {{$subcategory_id==$subcategorys->id?'selected':''}}>{{$subcategorys->subcat_name}}</option>
                            @endforeach
                            @endif
                            
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Product Note</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Product Note" aria-label="Username" name="product_note" value="{{$product_note}}">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Product Description</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Product Description" aria-label="Username" name="product_description" value="{{$product_description}}">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Product Image</label>
                          <input type="hidden" name="product_image_id" value="{{$admin_product_img_id}}">
                          <input type="file" class="form-control form-control-sm" id="product_image" name="product_image" accept="image/png, image/gif, image/jpeg">
                           @if(!empty($product_image))
                            <div class="mt-1 uploaded-file">
                              <a href="{{ asset('/public/uploads/product_image/' . $product_image) }}" target="_blank">{{ $product_image }}</a>
                            </div>
                          @endif
                          <img id="image_preview" src="#" alt="Preview" style="display:none; max-height: 150px; margin-top: 10px;" />
                        </div>
                      </div>
                      <div id="part-type-wrapper">
                        <div class="row part-type-row mt-2">
                          <div class="form-group col-md-5">
                            <label for="part_type">Variant</label>
                            <input type="hidden" name="part_type_id[]" value="">
                            <input type="text" class="form-control form-control-sm" name="part_type[]">
                          </div>
                          <div class="form-group col-md-1" style="padding: 23px;">
                            <button type="button" class="btn btn-outline-success add-part-type">+</button>
                          </div>
                        </div>
                        @if($type)
                        @foreach($type as $types)
                           <div class="row part-type-row mt-2">
                            <div class="form-group col-md-5">
                              <input type="hidden" name="part_type_id[]" value="{{$types->id}}">
                              <input type="text" class="form-control form-control-sm" name="part_type[]" value="{{$types->part_type_label}}">
                            </div>
                            <div class="form-group col-md-1">
                              <button type="button" class="btn btn-outline-danger remove-part-type" pid="{{$types->id}}">-</button>
                            </div>
                          </div>
                        @endforeach
                        @endif
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
          $(document).ready(function() {
            $('.select-drop').select2({
              placeholder: 'Select an option',
              allowClear: true
            });
          });
        </script>
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
        
        @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: "Success!",
                    text: "{{ session('success') }}",
                    icon: "success",
                    confirmButtonText: "OK"
                });
            });
        </script>
        @endif
        
        @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "{{ session('error') }}",
                confirmButtonText: "OK"
              });
            });
        </script>
        @endif

        <script>
          $(document).on('click', '.add-part-type', function () {
            let newRow = `
              <div class="row part-type-row mt-2">
                <div class="form-group col-md-5">
                  <input type="text" class="form-control form-control-sm" name="part_type[]">
                </div>
                <div class="form-group col-md-1">
                  <button type="button" class="btn btn-outline-danger remove-part-type">-</button>
                </div>
              </div>`;
            $('#part-type-wrapper').append(newRow);
          });

          $(document).on('click', '.remove-part-type', function () {
            let partTypeId = $(this).attr('pid');
            if(partTypeId)
            {
              $.ajax({
                url: "{{url('/admin/deletePartType')}}/" + partTypeId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        button.closest('.part-type-row').remove();
                    } else {
                        alert('Delete failed');
                    }
                },
                error: function () {
                    alert('Error occurred');
                }
              }); 
            }
            $(this).closest('.part-type-row').remove();
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

            $(document).on('change', '#model', function () {
              var cid = this.value;   //let cid = $(this).val(); we cal also write this.
              $.ajax({
                url: "{{url('/admin/getgeneration')}}",
                type: "POST",
                datatype: "json",
                data: {
                  model_id: cid,
                  '_token':'{{csrf_token()}}'
                },
                success: function(result) {
                  $('#generation').html('<option value="">Select Generation</option>');
                  $.each(result.subcat, function(key, value) {
                    $('#generation').append('<option value="' +value.id+ '">' +value.start_year+' - '+value.end_year+ '</option>');
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
                  $('#subcategory').html('<option value="">Select Part</option>');
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
        <!--script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
          <script>
              ClassicEditor
                  .create(document.querySelector('#video-introduction'))
                  .catch(error => {
                      console.error(error);
                  });
          </script-->

        @endpush