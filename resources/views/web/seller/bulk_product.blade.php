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
                          <label for="exampleInputEmail1">Part Type</label>
                          <select class="form-select form-select-sm select-drop" id="category" name="category_id">
                            <option value="">Select Part Type</option>
                            @if($category)
                            @foreach($category as $categorys)
                            <option value="{{$categorys->id }}">{{$categorys->category_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>

                        <div class="form-group col-md-3">
                          <label for="exampleInputEmail1">Part</label>
                          <select class="form-select form-select-sm select-drop" id="subcategory" name="subcategory_id">
                            <option value="">Select Part</option>
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
                      <div class="row">
                        <div class="form-group col-md-3">
                          <label for="exampleInputEmail1">Parent Brand</label>
                          <select required class="form-select form-select-sm select-drop" id="parent" name="parent_id">
                            <option value="">Select Parent</option>
                            @if($mparents)
                            @foreach($mparents as $mparentss)
                              <option value="{{$mparentss->id }}">{{$mparentss->mparents_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="exampleInputEmail1">Brand</label>
                          <select required class="form-select form-select-sm select-drop" id="brand" name="brand_id">
                            <option value="">Select Brand</option>
                            @if($brand)
                            @foreach($brand as $brands)
                              <option value="{{$brands->id }}">{{$brands->brand_name}}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>

                        <div class="form-group col-md-3">
                          <label for="exampleInputEmail1">Model</label>
                          <select required class="form-select form-select-sm select-drop" id="model" name="model_id">
                            <option value="">Select Model</option>
                              @if($model)
                              @foreach($model as $models)
                                <option value="{{$models->id }}">{{$models->model_name}}</option>
                              @endforeach
                              @endif
                          </select>
                        </div>

                        <div class="form-group col-md-3">
                          <label for="exampleInputEmail1">Generation</label>
                          
                          <select class="form-select form-select-sm select-drop" id="generation" name="generation_id">
                            <option value="">Select Generation</option>
                          
                          </select>
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
                  
                    <h4 class="card-title"> Product Management</h4>
                    <p class="card-description"> Product List 
                    </p>
                    
                      <div class="table-responsive">
                        <table class="table table-striped" id="example">
                          <thead>
                            <tr>
                              <th><input type="checkbox" id="selectAll"></th>
                              <th> Sr no </th>
                              <th> Brand </th>
                              <th> Model </th>
                              <th> Generation </th>
                              <th> Part Type </th>
                              <th> Part </th>
                              <th> Variation </th>
                            </tr>
                          </thead>
                          <tbody>
                           
                          </tbody>
                        </table>
                      </div>
                      <button type="submit" class="btn btn-outline-danger mt-3" id="save-selection">Add Product</button>
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
          $(document).ready(function() {
            $('.select-drop').select2({
              placeholder: 'Select an option',
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
                      //checkAllSelects();
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
          // $(document).ready(function () {
          //     checkAllSelects();
          // });
          let selectedProducts = [];

          let table = $('#example').DataTable({
              processing: true,
              serverSide: true,
              ajax: {
                  url: "{{url('/seller/getProduct')}}",
                  
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
                  { data: 'checkbox', orderable: false, searchable: false }, // checkbox column
                  { data: 'DT_RowIndex', name: 'DT_RowIndex' },              // Sr no
                  { data: 'brand' },     // Make sure these match your server response keys
                  { data: 'model' },
                  { data: 'generation' },
                  { data: 'product' },
                  { data: 'subcategory' },
                  { data: 'variation' }
              ],
             // deferLoading: 0 
          });

          //diable the save button 
          function checkAllSelects() {
            let allFilled = true;

            $('#filters select').each(function () {
                if ($(this).val() === '') {
                    allFilled = false;
                    return false; // break loop
                }
            });

            $('#save-selection').prop('disabled', !allFilled);
          }
        // Only trigger reload when brand is selected
          $('#brand').on('change', function () {
              if ($(this).val()) {
                  table.ajax.reload();
                 // checkAllSelects();
              }
          });
          // Reload table when filters change
          $('#filters select').on('change', function () {
              table.ajax.reload();
             // checkAllSelects();
          });

          // $(document).on('change', '#part', function () {
          //     checkAllSelects();
          // });
          

          // Select All
          $('#selectAll').on('change', function () {
              $('.product-check').prop('checked', this.checked).trigger('change');
          });

          
          // Handle checkbox tracking
          $(document).on('change', '.product-check', function () {
              const productId = $(this).data('product-id');
              const partTypeId = $(this).data('part-type-id');
              if ($(this).is(':checked')) {
                  selectedProducts.push({ product_id: productId, part_type_id: partTypeId });
              } else {
                  selectedProducts = selectedProducts.filter(p => !(p.product_id === productId && p.part_type_id === partTypeId));
              }
          });

          // Save
          document.getElementById('uploadLoader').style.display = 'none';
          $('#save-selection').on('click', async function () {
              if (selectedProducts.length === 0) {
                  Swal.fire({
                      icon: 'warning',
                      title: 'No selection',
                      text: 'Please select at least one product to add.',
                  });
                  return;
              }

              // Split into chunks of 500
              const chunkSize = 500;
              const chunks = [];
              for (let i = 0; i < selectedProducts.length; i += chunkSize) {
                  chunks.push(selectedProducts.slice(i, i + chunkSize));
              }

              document.getElementById('uploadLoader').style.display = 'flex';

              for (let i = 0; i < chunks.length; i++) {
                  try {
                      await $.post("{{ url('/seller/save-selection') }}", {
                          _token: '{{ csrf_token() }}',
                          products: chunks[i],
                          
                      });
                  } catch (err) {
                      console.error("Chunk upload failed", err);
                      Swal.fire({
                          icon: 'error',
                          title: 'Upload Error',
                          text: `Failed to upload chunk ${i + 1}`,
                      });
                      break;
                  }
              }

              document.getElementById('uploadLoader').style.display = 'none';
              Swal.fire({
                  icon: 'success',
                  title: 'Saved',
                  text: 'All products saved successfully!',
              });

              selectedProducts = [];
              table.ajax.reload();
          });
          </script>
        @endpush