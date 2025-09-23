@extends('web.seller.layout.layout')

@section('content')
<style>
  i.mdi {
    font-size: 18px;
  }
select.form-select {
    padding: 5px 30px;
    border: 0;
    outline: 1px solid #CED4DA;
    color: #000000;
    padding-left: .5rem;
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
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <form class="forms-sample" id="filters">
                    {!! csrf_field() !!}
                      <div class="row">
                        <div class="form-group col-md-3">
                          <label for="exampleInputEmail1">{{__('messages.tbl_parent_brand')}}</label>
                          <select required class="form-select form-select-sm select-drop" id="parent" name="parent_id">
                            <option value="">{{__('messages.tbl_parent_brand')}}</option>
                            @if($mparents)
                            @foreach($mparents as $mparentss)
                              <option value="{{$mparentss->id }}">{{$mparentss->mparents_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="exampleInputEmail1">{{__('messages.MAKE')}}</label>
                          <select required class="form-select form-select-sm select-drop" id="brand" name="brand_id">
                            <option value="">{{__('messages.MAKE')}}</option>
                            @if($brand)
                            @foreach($brand as $brands)
                              <option value="{{$brands->id }}">{{$brands->brand_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>

                        <div class="form-group col-md-3">
                          <label for="exampleInputEmail1">{{__('messages.MODEL')}}</label>
                          <select required class="form-select form-select-sm select-drop" id="model" name="model_id">
                            <option value="">{{__('messages.MODEL')}}</option>
                              @if($model)
                              @foreach($model as $models)
                                <option value="{{$models->id }}">{{$models->model_name}}</option>
                              @endforeach
                              @endif
                          </select>
                        </div>

                        <div class="form-group col-md-3">
                          <label for="exampleInputEmail1">{{__('messages.GENERATION')}}</label>
                          
                          <select class="form-select form-select-sm select-drop" id="generation" name="generation_id">
                            <option value="">{{__('messages.GENERATION')}}</option>
                          
                          </select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-md-3">
                          <label for="exampleInputEmail1">{{__('messages.PART TYPE')}}</label>
                          <select class="form-select form-select-sm select-drop" id="category" name="category_id">
                            <option value="">{{__('messages.PART TYPE')}}</option>
                            @if($category)
                            @foreach($category as $categorys)
                            <option value="{{$categorys->id }}">{{$categorys->category_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>

                        <div class="form-group col-md-3">
                          <label for="exampleInputEmail1">{{__('messages.PART')}}</label>
                          <select class="form-select form-select-sm select-drop" id="subcategory" name="subcategory_id">
                            <option value="">{{__('messages.PART')}}</option>
                            @if($subcategory)
                              @foreach($subcategory as $subcategorys)
                                <option value="{{$subcategorys->id }}">{{$subcategorys->subcat_name}}</option>
                              @endforeach
                              @endif 
                          </select>
                        </div>

                        <div class="form-group col-md-3" id="part_type">
                        </div>

                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>  

            <div class="row">
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                  
                    <h4 class="card-title"> {{__('messages.product_management')}}</h4>
                    <p class="card-description"> {{__('messages.product_list')}} 
                    </p>
                    
                      <div class="table-responsive">
                        <table class="table table-striped" id="example">
                          <thead>
                            <tr>
                              <th>{{__('messages.tbl_sr_no')}}</th>
                              <th> {{__('messages.tbl_product')}} </th>
                              <th> {{__('messages.tbl_part')}} </th>
                              <th> {{__('messages.tbl_image')}} </th>
                              <th> {{__('messages.tbl_description')}} </th>
                              <th> {{__('messages.tbl_price')}} </th>
                              <th> {{__('messages.tbl_available')}} </th>
                            </tr>
                          </thead>
                          <tbody>
                           
                          </tbody>
                        </table>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
        </div>
        
        <!-- Loader -->
        <div id="uploadLoader" style="display: none; position: fixed; top: 0; left: 0; 
          width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); 
          z-index: 9999; display: flex; justify-content: center; align-items: center;">

          <div style="text-align: center;">
            <div class="spinner" style="
              border: 6px solid #f3f3f3;
              border-top: 6px solid #3498db;
              border-radius: 50%;
              width: 50px;
              height: 50px;
              animation: spin 1s linear infinite; margin-left: 63px;"></div>
            <p style="margin-top: 10px;">Uploading Products, please wait...</p>
          </div>
        </div>
<style>
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
</style>
        @endsection
        @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/browser-image-compression@2.0.2/dist/browser-image-compression.js"></script>

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
          document.getElementById('uploadLoader').style.display = 'none';
          $(document).ready(function() {
            $('.select-drop').select2({
              placeholder: "{{__('messages.tbl_select_option')}}",
              allowClear: true
            });
          });
        </script>
        <script>
          $(document).ready(function () {

            $(document).on('change', '#parent', function () {
              var cid = this.value;   //let cid = $(this).val(); we cal also write this.
              $.ajax({
                url: "{{url('/admin/getBrand')}}",
                type: "POST",
                datatype: "json",
                data: {
                  mparents_id: cid,
                  '_token':'{{csrf_token()}}'
                },
                success: function(result) {
                  $('#generation').html('<option value="">Select Generation</option>');
                  //$('#model').html('<option value="">Select model</option>');
                  //$('#part_type').html('');
                  $('#brand').html('<option value="">Select brand</option>');
                  $.each(result.subcat, function(key, value) {
                    $('#brand').append('<option value="' +value.id+ '">' +value.brand_name+ '</option>');
                  });
                },
                errror: function(xhr) {
                    console.log(xhr.responseText);
                  }
                });
            });

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
                  $('#generation').html('<option value="">Select Generation</option>');
                  //$('#subcategory').html('<option value="">Select Subcategory</option>');
                  //$('#part_type').html('');
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

                  $('#part_type').html('');

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
                  $('#part_type').html('');
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

            $('#subcategory, #generation').change(function(){
              var brand_id=$('#brand').val();
              var model_id=$('#model').val();
              var generation_id=$('#generation').val();
              var category_id=$('#category').val();
              var subcategory_id=$('#subcategory').val();

              $.ajax({
                url: "{{url('/admin/getPartType')}}",
                type: "POST",
                datatype: "json",
                data: {
                  brand_id: brand_id,model_id:model_id,generation_id:generation_id,category_id:category_id,subcategory_id:subcategory_id,
                  '_token':'{{csrf_token()}}'
                },
                success: function(result) {
                  console.log(result);
                  if (result.part_type && result.part_type.length > 0) {
                    let options='<label for="exampleInputEmail1">Varient</label>';
                      options += '<select class="form-select form-select-sm select-drop" id="part" name="part_type_id">';
                      options += '<option value="">Select Varient</option>';

                      $.each(result.part_type, function (key, value) {
                          options += '<option value="' + value.id + '">' + value.part_type_label + '</option>';
                      });

                      options += '</select>';

                      // Replace the HTML inside #part_type
                      $('#part_type').html(options);
                     
                      $('#part').select2({
                        placeholder: 'Select an option',
                        allowClear: true
                      });
                      // Rebind change event after part_type is dynamically inserted
                      $('#part').on('change', function () {
                          table.ajax.reload();
                      });
                    } else {
                        // Clear part_type if no results
                        $('#part_type').html('');
                        table.ajax.reload(); // Optional: refresh if part_type filter disappears
                    }
                  
                },
                errror: function(xhr) {
                    console.log(xhr.responseText);
                  }
              });
              
            });

            $('#generation').change(function(){
              $('#part_type').html('');
            });
          });
        </script>

        <script>
          let selectedProducts = [];

          let table = $('#example').DataTable({
              processing: true,
              serverSide: true,
              language: {
                        url: "{{ app()->getLocale() == 'ar' ? asset('/public/js/datatable-ar.json') : '' }}"
                    },
              ajax: {
                  url: "{{url('/seller/getCatalogueProduct')}}",
                  
                  type: 'POST',
                  data: function (d) {
                    let brand_id = $('#brand').val();
                      if (!brand_id) {
                         // return false; // prevent request if brand not selected
                      }
                      return $.extend({}, d, {
                          parent_id: $('#parent').val(),
                          brand_id: $('#brand').val(),
                          model_id: $('#model').val(),
                          category_id: $('#category').val(),
                          subcategory_id: $('#subcategory').val(),
                          generation: $('#generation').val(),
                          part: $('#part').val(),
                          _token: '{{ csrf_token() }}'
                      });
                  }
              },
              lengthMenu: [[10, 25, 50, 100,500, -1], [10, 25, 50, 100,500, "All"]],
              pageLength: 10,
              columns: [
                  { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                  { data: 'brand' },     // Make sure these match your server response keys
                  { data: 'subcategory' },
                  { data: 'image' },
                  { data: 'description' },
                  { data: 'price' },
                  { data: 'available' },
              ],
          });

          
        // Only trigger reload when brand is selected
          $('#brand').on('change', function () {
              if ($(this).val()) {
                  table.ajax.reload();
              }
          });
          // Reload table when filters change
          $('#filters select').on('change', function () {
              table.ajax.reload();
          });
          </script>

          <script>
            //new script here for add update product
            $(document).on('change', '.product-check', function () {
              document.getElementById('uploadLoader').style.display = 'flex';

              const checkbox = $(this);
              const productId = checkbox.data('product-id');
              const partTypeId = checkbox.data('part-type-id');
              const isChecked = checkbox.is(':checked');

              $.ajax({
                  url: isChecked ? "{{ url('/seller/addSellerProduct') }}" : "{{ url('/seller/removeSellerProduct') }}",
                  type: 'POST',
                  data: {
                      _token: '{{ csrf_token() }}',
                      product_id: productId,
                      part_type_id: partTypeId
                  },
                  success: function (res) {
                      const row = checkbox.closest('tr');

                      // Update checkbox with seller product id
                      checkbox.attr('data-seller-product-id', res.seller_product_id);

                      // Enable price input
                      row.find('.update-field').prop('disabled', false);
                      row.find('.update-field').attr('data-id', res.seller_product_id);

                      // Enable image upload
                      row.find('.image-upload').removeClass('disabled-image');
                      row.find('.image-upload').attr('data-id', res.seller_product_id);

                      document.getElementById('uploadLoader').style.display = 'none';
                  },
                  error: function () {
                      alert('Something went wrong');
                      checkbox.prop('checked', !isChecked);
                      document.getElementById('uploadLoader').style.display = 'none';
                  }
              });
          });

            $(document).on('change', '.product-check', function () {
                const row = $(this).closest('tr');
                const isChecked = $(this).is(':checked');
                row.find('.update-field, .product-img-input').prop('disabled', !isChecked);
            });

            $(document).on('blur', '.update-field', function () {
              const productId = $(this).data('id');
              const field = $(this).data('field');
              const value = $(this).val();

              $.ajax({
                  url: "{{ url('/seller/updateSellerProductField') }}",
                  type: "POST",
                  data: {
                      _token: "{{ csrf_token() }}",
                      product_id: productId,
                      field: field,
                      value: value
                  },
                  success: function(response) {
                      console.log('Updated:', response.message);
                  },
                  error: function(xhr) {
                      console.error('Update error:', xhr.responseText);
                  }
              });
          });

          $(document).on('click', '.product-img', function () {
              if ($(this).closest('.image-upload').hasClass('disabled-image')) {
                  alert('Please check the product first to enable image upload.');
                  return;
              }
              $(this).closest('.image-upload').find('.product-img-input').trigger('click');
          });
/*
          $(document).on('change', '.product-img-input', function () {
              document.getElementById('uploadLoader').style.display = 'flex';
              let input = this;
              let formData = new FormData();
              let productId = $(this).closest('.image-upload').data('id');

              formData.append('product_image', input.files[0]);
              formData.append('product_id', productId);
              formData.append('_token', '{{ csrf_token() }}');

              $.ajax({
                  url: "{{ url('/seller/savesPImage') }}",
                  type: 'POST',
                  data: formData,
                  processData: false,
                  contentType: false,
                  enctype: 'multipart/form-data',
                  success: function(response) {
                      let img = $(input).siblings('img');
                      img.attr('src', response.image_url + '?t=' + new Date().getTime());
                      document.getElementById('uploadLoader').style.display = 'none';
                  },
                  error: function(xhr) {
                          alert("Image upload failed.");
                          document.getElementById('uploadLoader').style.display = 'none';
                      }
              });
          });
*/
          $(document).on('change', '.product-img-input', async function () {
            document.getElementById('uploadLoader').style.display = 'flex';
            let input = this;
            let productId = $(this).closest('.image-upload').data('id');

            if (input.files && input.files[0]) {
                try {
                    // Compression settings
                    let options = {
                        maxSizeMB: 1, // Target maximum size in MB
                        maxWidthOrHeight: 1024, // Resize to fit within 1024px
                        useWebWorker: true, // Speed up compression
                        initialQuality: 0.8 // Starting quality
                    };

                    // Compress the image
                    let compressedFile = await imageCompression(input.files[0], options);

                    // Create FormData with compressed file
                    let formData = new FormData();
                    formData.append('product_image', compressedFile);
                    formData.append('product_id', productId);
                    formData.append('_token', '{{ csrf_token() }}');

                    $.ajax({
                        url: "{{ url('/seller/savesPImage') }}",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        enctype: 'multipart/form-data',
                        success: function(response) {
                            let img = $(input).siblings('img');
                            img.attr('src', response.image_url + '?t=' + new Date().getTime());
                            document.getElementById('uploadLoader').style.display = 'none';
                        },
                        error: function(xhr) {
                            alert("Image upload failed.");
                            document.getElementById('uploadLoader').style.display = 'none';
                        }
                    });
                } catch (error) {
                    console.error("Compression error:", error);
                    alert("Image compression failed.");
                    document.getElementById('uploadLoader').style.display = 'none';
                }
            }
        });
          </script>
          

        @endpush