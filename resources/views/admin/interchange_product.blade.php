@extends('admin.layouts.layout')

@section('content')

<style>
  i.mdi {
    font-size: 18px;
}
  .ck-editor__editable {
    min-height: 300px !important; /* Or whatever height you want */
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
                    <a href="{{route('admin.InterchangeProductList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Add Interchange List</a>
                    <h4 class="card-title">Add Interchangeable Product</h4>
                    
                      <div id="vehicle-wrapper">
                        <div class="row vehicle-row">
                          <div class="form-group col-md-3">
                            <label for="exampleInputEmail1">Brand</label>
                            <select required class="form-select form-select-sm brand-select select-drop" id="brand" name="brand_id[]">
                              <option value="">Select Brand</option>
                              @if($brand)
                              @foreach($brand as $brands)
                                <option value="{{$brands->id }}">{{$brands->brand_name}}</option>
                              @endforeach
                              @endif
                            </select>
                          </div>

                          <div class="form-group col-md-3">
                            <label for="exampleInputEmail1">Make Model</label>
                            <select required class="form-select form-select-sm model-select select-drop" id="model" name="model_id[]">
                              <option value="">Select Model</option>
                              
                            </select>
                          </div>
                          <div class="form-group col-md-3">
                            <label for="exampleInputEmail1">Generation</label>
                            <select required class="form-select form-select-sm generation-select select-drop" id="generation" name="generation_id[]">
                              <option value="">Select Generation</option>
                              
                            </select>
                          </div>
                          <div class="form-group col-md-3">
                            <button type="button" class="btn btn-outline-success add-more" style="margin-top: 23px;">Add More</button>
                          </div>
                        </div>
                      </div>
                    
                  </div>
                </div>
              </div>
            </div>

            <!-- Interchange Products-->
            <div class="row">
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Interchangeable Product</h4>
                      <div class="table-responsive">
                        <button type="button" class="btn btn-outline-danger mt-3 save-selection" >Apply Interchange</button>
                        <table class="table table-striped" id="example1">
                          <thead>
                            <tr>
                              <th><input type="checkbox" id="selectAll"></th>
                              <th> Sr no </th>
                              <th> Brand</th>
                              <th> Model</th>
                              <th> Part Type</th>
                              <th> Part</th>
                              <th> Variation</th>
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
        <!-- main-panel ends -->
        @endsection
        @push('scripts')
        <script>
          $(document).ready(function () {
    $('select.select-drop').each(function () {
        if (!$(this).hasClass("select2-hidden-accessible")) {
            $(this).select2({
                placeholder: 'Select an option',
                allowClear: true,
                width: '100%'
            });
        }
    });
});

        </script>

        <script>
          document.getElementById('uploadLoader').style.display = 'none';
          $(document).on('click', '.add-more', function () {
    let newRow = $(`
        <div class="row mt-2 vehicle-row">
          <div class="form-group col-md-3">
            <label>Brand</label>
            <select required class="form-select form-select-sm brand-select select-drop" name="brand_id[]">
              <option value="">Select Brand</option>
              @foreach($brand as $brands)
                <option value="{{ $brands->id }}">{{ $brands->brand_name }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group col-md-3">
            <label>Make Model</label>
            <select required class="form-select form-select-sm model-select select-drop" name="model_id[]">
              <option value="">Select Model</option>
            </select>
          </div>

          <div class="form-group col-md-3">
            <label>Generation</label>
            <select required class="form-select form-select-sm generation-select select-drop" name="generation_id[]">
              <option value="">Select Generation</option>
            </select>
          </div>

          <div class="form-group col-md-3">
            <button type="button" class="btn btn-outline-danger remove" style="margin-top: 23px;">Remove</button>
          </div>
        </div>`);

    $('#vehicle-wrapper').append(newRow);

    // ✅ Only initialize the select elements inside the new row
    newRow.find('select.select-drop').each(function () {
        if (!$(this).hasClass("select2-hidden-accessible")) {
            $(this).select2({
                placeholder: 'Select an option',
                allowClear: true,
                width: '100%' // Optional, ensures full width
            });
        }
    });
});


          $(document).on('click', '.remove', function () {
            $(this).closest('.vehicle-row').remove();
          });


          $(document).ready(function () {
            $(document).on('change', '.brand-select', function () {
             
              let brandId = $(this).val();
              let $modelSelect = $(this).closest('.vehicle-row').find('.model-select');
              let $generationSelect = $(this).closest('.vehicle-row').find('.generation-select');
             
              $.ajax({
                url: "{{url('/admin/getModel')}}",
                type: "POST",
                datatype: "json",
                data: {
                  brand_id: brandId,
                  '_token':'{{csrf_token()}}'
                },
                success: function(result) {
                  $modelSelect.html('<option value="">Select Model</option>');
                  $.each(result.city, function(key, value) {
                    $modelSelect.append('<option value="' +value.id+ '">' +value.model_name+ '</option>');
                  });
                  $generationSelect.html('<option value="">Select Generation</option>');
                },
                errror: function(xhr) {
                    console.log(xhr.responseText);
                  }
                });
            });

            $(document).on('change', '.model-select', function () {
              var cid = $(this).val();   
              let $generationSelect = $(this).closest('.vehicle-row').find('.generation-select');
              $.ajax({
                url: "{{url('/admin/getgeneration')}}",
                type: "POST",
                datatype: "json",
                data: {
                  model_id: cid,
                  '_token':'{{csrf_token()}}'
                },
                success: function(result) {
                   $generationSelect.html('<option value="">Select Generation</option>');
                  $.each(result.subcat, function(key, value) {
                    $generationSelect.append('<option value="' +value.id+ '">' +value.start_year+'-'+value.end_year+ '</option>');
                  });
                },
                errror: function(xhr) {
                    console.log(xhr.responseText);
                  }
                });
            });
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
      $(document).ready( function () 
      {
        var table = $('#example1').DataTable( {
          //ordering: false,
          columnDefs: [
                          { orderable: false, targets: 0 } // Disable sorting on the first column (checkboxes)
                      ],
          lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
          "bPaginate": true,
          "bInfo": true,
          });
      } );
      
      // Initial fetch when combination changes
      $(document).on('change', '.brand-select, .model-select, .generation-select', function() {
        collectCombinations();
        fetchAndUpdateProducts();
      });
      
      var combinations = [];

      function collectCombinations() {
        combinations = [];
        $('.vehicle-row').each(function() {
          var brand = $(this).find('.brand-select').val();
          var model = $(this).find('.model-select').val();
          var generation = $(this).find('.generation-select').val();

          if (brand || model || generation) {
            combinations.push({
              brand_id: brand || null,
              model_id: model || null,
              generation_id: generation || null
            });
          }
        });
      }

      function fetchAndUpdateProducts() 
      {
        $.ajax({
          url: "{{ route('admin.get.products.by.combinations') }}",
          type: "POST",
          data: {
            combinations: combinations,
            _token: "{{ csrf_token() }}"
          },
          success: function(response) {
            var table = $('#example1').DataTable();
            table.clear();

            response.products.forEach((product, index) => {
              table.row.add([
                `<input type="checkbox"
                    name="ids[]"
                    value="${product.sub_cat_id}"
                    data-variant="${product.variant_id ?? 0}"
                    data-brand="${product.brand_id ?? ''}"
                    data-model="${product.make_model_id ?? ''}"
                    data-generation="${product.generation_id ?? ''}"
                    class="product-check">`,
                index + 1,
                product.brand_name ?? '',       // Brand
                product.model_name ?? '',       // Model
                product.category_name ?? '',    // Part Type
                product.subcat_name ?? '',      // Part
                product.part_type_label ?? ''
              ]);
            });

            table.draw();
          }
        });
      }

      

      //select the product combination and send for interchange          
          
      let selectedProducts = new Set();

      // Handle select all
      $(document).ready(function () {
          $('#selectAll').on('change', function () {
              $('.product-check').prop('checked', this.checked).trigger('change');
          });
      });

      // Track selected products
      $(document).on('change', '.product-check', function () {
          const val = $(this).val();
          if ($(this).is(':checked')) {
              selectedProducts.add(val);
          } else {
              selectedProducts.delete(val);
          }
      });

      // Save selected products for interchange
      document.getElementById('uploadLoader').style.display = 'none';

      $('.save-selection').on('click', async function () {
          const selectedArray = [];

          $('#example1').DataTable().$('input.product-check:checked').each(function () {
              const productId = $(this).val();
              const variantId = $(this).data('variant') || 0;
              const brandId = $(this).data('brand');
              const modelId = $(this).data('model');
              const generationId = $(this).data('generation');

              selectedArray.push({
                  product_id: productId,
                  variant_id: variantId,
                  brand_id: brandId,
                  model_id: modelId,
                  generation_id: generationId
              });
          });

          if (selectedArray.length === 0) {
              Swal.fire({
                  icon: 'warning',
                  title: 'No selection',
                  text: 'Please select at least one product to Interchange',
              });
              return;
          }

          document.getElementById('uploadLoader').style.display = 'flex';

          try {
              await $.post("{{ url('/admin/addInterchangeProduct') }}", {
                  _token: '{{ csrf_token() }}',
                  products: selectedArray
              });
          } catch (err) {
              console.error("Upload failed", err);
              Swal.fire({
                  icon: 'error',
                  title: 'Upload Error',
                  text: `Failed to add Interchange product`,
              });
              document.getElementById('uploadLoader').style.display = 'none';
              return;
          }

          document.getElementById('uploadLoader').style.display = 'none';
          Swal.fire({
              icon: 'success',
              title: 'Saved',
              text: 'All products saved successfully!',
          }).then(() => {
              //location.reload();
          });

          selectedProducts.clear();
      });

           $(document).on('click','.del_inter',function(){
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
                    url: "{{url('/admin/delete_inter')}}",
                    type: "POST",
                    datatype: "json",
                    data: {
                      user:user_id,
                      '_token':'{{csrf_token()}}'
                    },
                    success: function(result) {
                      
                      swalWithBootstrapButtons.fire({
                        title: "Deleted!",
                        text: "Caoch has been deleted.",
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
                    text: "Your user is safe :)",
                    icon: "error"
                  });
                }
              });
            });
        </script>
        @endpush