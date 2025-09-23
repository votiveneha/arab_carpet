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
.product_tbl tr td {
    font-size: 10px !important;
    padding: 5px !important;
    white-space: normal;
}
table.dataTable td {
    white-space: normal !important;
    word-break: break-word;
}
</style>
<style>
    [dir="rtl"] .add-product-btn {
        float: left !important;
    }

    [dir="ltr"] .add-product-btn {
        float: right !important;
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
                      </div>
                      <!--div class="row">
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Make Year</label>
                          
                          <select required class="js-example-basic-multiple w-100" multiple="multiple" id="year" name="make_year[]">
                            @if($make_year)
                            @foreach($make_year as $year)
                              <option value="{{$year->id}}">{{$year->year_english}}</option>
                            @endforeach
                            @endif
                            
                          </select>
                        </div>
                      </div-->
                      <button type="button" id="exportExcelBtn" class="btn btn-outline-success">{{__('messages.export_excel')}}</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>  
            <div class="row">
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                  <a href="{{route('seller.getAllProduct')}}" class="btn btn-outline-info btn-fw add-product-btn" style="float: right;">{{__('messages.add_product')}}</a>
                    <h4 class="card-title"> {{__('messages.product_management')}}</h4>
                    <p class="card-description"> {{__('messages.product_list')}} 
                    </p>
                    <form id="bulkDeleteForm" method="POST" action="{{ route('seller.bulkDeleteproduct') }}">
                      @csrf
                      <div class="table-responsive">
                        <table class="table table-striped product_tbl" id="example">
                          <thead>
                            <tr>
                              <th><input type="checkbox" id="selectAll"></th>
                              <th> {{__('messages.tbl_sr_no')}} </th>
                              <th> {{__('messages.stock')}} </th>
                              <th> {{__('messages.tbl_product')}} </th>
                              <th> {{__('messages.tbl_part')}} </th>
                              <th> {{__('messages.tbl_image')}}</th>
                              <th> {{__('messages.tbl_description')}} </th>
                              <th> {{__('messages.tbl_price')}} </th>
                              <th> {{__('messages.copy')}} </th>
                              <!-- <th> Quantity </th>
                              <th> Type </th> -->
                              <th> {{__('messages.tbl_available')}}</th>
                              <th> {{__('messages.action')}}</th>
                            </tr>
                          </thead>
                          <tbody>
                            
                          </tbody>
                        </table>
                      </div>
                      <button type="submit" class="btn btn-outline-danger mt-3" id="bulkDeleteBtn">{{__('messages.delete_selected')}}</button>
                    </form>
                    
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
    <script src="https://unpkg.com/compressorjs/dist/compressor.min.js"></script>
        <script>
          $(document).ready(function() {
            document.getElementById('uploadLoader').style.display = 'none';
            
            $('.select-drop').select2({
              placeholder: "{{__('messages.tbl_select_option')}}",
              allowClear: true
            });
          });

          $('#exportExcelBtn').on('click', function () {
              let formData = $('#filters').serialize();
              window.location.href = '{{ route("seller.export") }}?' + formData;
          });
        </script>

        <script>
          $(document).on('click', '.product-img', function () {
              $(this).closest('.image-upload').find('.product-img-input').trigger('click');
          });

          $(document).on('change', '.product-img-input', function () {
            
            document.getElementById('uploadLoader').style.display = 'flex';
              let input = this;
              let formData = new FormData();
              let productId = $(this).closest('.image-upload').data('id');

              if (input.files && input.files[0]) {
                  formData.append('product_image', input.files[0]);
                  formData.append('product_id', productId);
                  formData.append('_token', '{{ csrf_token() }}');

                  $.ajax({
                      url: '{{ route("seller.saveImage") }}', // replace with your route
                      type: 'POST',
                      data: formData,
                      processData: false,
                      contentType: false,
                      success: function(response) {
                          // Refresh DataTable or update image directly
                          //$('#example').DataTable().ajax.reload(null, false);
                          let imgBlock = $(input).closest('.image-upload').find('img');

                          // Update the image src using a cache-busting timestamp
                          const newUrl = response.image_url + '?t=' + new Date().getTime();
                          imgBlock.attr('src', newUrl);
                          document.getElementById('uploadLoader').style.display = 'none';
                      },
                      error: function(xhr) {
                          alert("Image upload failed.");
                          document.getElementById('uploadLoader').style.display = 'none';
                      }
                  });
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
          let table = $('#example').DataTable({
              processing: true,
              serverSide: true,
              language: {
                        url: "{{ app()->getLocale() == 'ar' ? asset('/public/js/datatable-ar.json') : '' }}"
                    },
              ajax: {
                  url: "{{url('/seller/getMyProduct')}}",
                  
                  type: 'POST',
                  data: function (d) {
                    // let brand_id = $('#brand').val();
                    //   if (!brand_id) {
                    //       d.prevent = true;  // prevent request if brand not selected
                    //   }
                      return $.extend({}, d, {
                          brand_id: $('#brand').val(),
                          model_id: $('#model').val(),
                          category_id: $('#category').val(),
                          subcategory_id: $('#subcategory').val(),
                          years: $('#year').val(),
                          _token: '{{ csrf_token() }}'
                      });
                  }
              },
              lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
              pageLength: 10,
              columns: [
                  { data: 'checkbox', orderable: false, searchable: false },
                  { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                  { data: 'stock', name: 'stock' },
                  { data: 'brand', name: 'brand' },
                  { data: 'subcategory', name: 'subcategory' },
                  { data: 'image', name: 'image' },
                  { data: 'description', name: 'description' },
                  { data: 'price', name: 'price' },
                  { data: 'copyp', name: 'copyp' },
                  // { data: 'quantity', name: 'quantity' },
                  // { data: 'type', name: 'type' },
                  { data: 'status', name: 'status', orderable: false },
                  { data: 'action', name: 'action', orderable: false, searchable: false }
              ],
             // deferLoading: 0 
          });

          // Only trigger reload when brand is selected
          // $('#brand').on('change', function () {
          //     if ($(this).val()) {
          //         table.ajax.reload();
          //     }
          // });
          // Reload table when filters change
          $('#filters select').on('change', function () {
              table.ajax.reload();
          });

          // Select All
          $('#selectAll').on('change', function () {
              $('.product-check').prop('checked', this.checked).trigger('change');
          });

          $(document).on('blur', '.update-field', function() {
            const productId = $(this).data('id');
            const field = $(this).data('field');
            const value = $(this).val();

            $.ajax({
                url: "{{ url('/seller/updateProductField') }}", // Create this route
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: productId,
                    field: field,
                    value: value
                },
                success: function(response) {
                    // Swal.fire({
                    //     icon: 'success',
                    //     title: 'Updated!',
                    //     text: response.message || 'Field updated successfully'
                    // });
                },
                error: function(xhr) {
                    // Swal.fire({
                    //     icon: 'error',
                    //     title: 'Error',
                    //     text: xhr.responseText
                    // });
                }
            });
        });

        </script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.body.addEventListener("click", function (e) {
        if (e.target.classList.contains("copy-product")) {
          
            e.preventDefault(); // 🚫 prevent href="#" from navigating

            const productId = e.target.dataset.id;

            fetch(`{{url('/seller/copyProduct')}}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert("Product copied successfully!");
                    location.reload(); // Optional: refresh DataTable
                } else {
                    alert("Failed to copy product.");
                }
            })
            .catch(err => {
                console.error(err);
                alert("An error occurred.");
            });
        }
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
          	
          $(document).ready(function () {

            $(document).on('change','.product_type',function(){
              var status=$(this).val();
              var user_id=$(this).attr('user');
              $.ajax({
                url: "{{url('/seller/updateProductType')}}",
                type: "POST",
                datatype: "json",
                data: {
                  product_type: status,
                  product_id:user_id,
                  '_token':'{{csrf_token()}}'
                },
                success: function(result) {
                  // Swal.fire({
                  //   title: "Success!",
                  //   text: "Product Type updated!",
                  //   icon: "success"
                  // });
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                  }
                });
            });

            $(document).on('change','.product_status',function(){
              //var status=$(this).val();
              var status= $(this).is(':checked') ? 1 : 0;
              var user_id=$(this).data('user');
              $.ajax({
                url: "{{url('/seller/updateProductStatus')}}",
                type: "POST",
                datatype: "json",
                data: {
                  status: status,
                  product_id:user_id,
                  '_token':'{{csrf_token()}}'
                },
                success: function(result) {
                  // Swal.fire({
                  //   title: "Success!",
                  //   text: "Status updated!",
                  //   icon: "success"
                  // });
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                  }
                });
            });

            $(document).on('click','.del_product',function(){
              const button = $(this);

              const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                  confirmButton: "btn btn-success",
                  cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
              });
              swalWithBootstrapButtons.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                reverseButtons: true
              }).then((result) => {
                if (result.isConfirmed) {

                  var user_id=$(this).attr('user_id');
                  $.ajax({
                    url: "{{url('/seller/deleteProduct')}}",
                    type: "POST",
                    datatype: "json",
                    data: {
                      product_id:user_id,
                      '_token':'{{csrf_token()}}'
                    },
                    success: function(result) {
                      
                      swalWithBootstrapButtons.fire({
                        title: "Deleted!",
                        text: "Product has been deleted.",
                        icon: "success"
                      });
                      button.closest('tr').remove();
                    },
                    errror: function(xhr) {
                        console.log(xhr.responseText);
                      }
                    });
                } else if (
                  /* Read more about handling dismissals below */
                  result.dismiss === Swal.DismissReason.cancel
                ) {
                  swalWithBootstrapButtons.fire({
                    title: "Cancelled",
                    text: "Your product is safe :)",
                    icon: "error"
                  });
                }
              });
            });
          });
          
        </script>
        <script>
          document.getElementById('selectAll').addEventListener('click', function (e) {
            let checkboxes = document.querySelectorAll('.selectBox');
            checkboxes.forEach(cb => cb.checked = e.target.checked);
          });
          
          document.getElementById('bulkDeleteBtn').addEventListener('click', function (e) {
            e.preventDefault(); // Stop normal form submit

            const form = document.getElementById('bulkDeleteForm');
            const checkboxes = document.querySelectorAll('.selectBox:checked');

            if (checkboxes.length === 0) {
              Swal.fire({
                icon: 'warning',
                title: 'No selection',
                text: 'Please select at least one user to delete.',
              });
              return;
            }

            Swal.fire({
              title: 'Are you sure?',
              text: "Selected user will be deleted.",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#d33',
              cancelButtonColor: '#3085d6',
              confirmButtonText: 'Yes, delete selected'
            }).then((result) => {
              if (result.isConfirmed) {
                form.submit(); // Submit the form only if confirmed
              }
            });
          });
        </script>

        
        @endpush