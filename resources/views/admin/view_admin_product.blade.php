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
</style>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.adminProductList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Admin Product List</a>
                    <h4 class="card-title">Admin Product Management</h4>
                    <p class="card-description"> View Admin Product  </p>
                    <div class="row">
                      <input type="hidden" name="product_id" id="product_id" value="{{$product_detail->id}}">
                      <div class="form-group col-md-6">
                        <label for="exampleInputEmail1">Brand : <strong>{{$product_detail->brand_name}}</strong></label>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="exampleInputEmail1">Make Model : <strong>{{$product_detail->model_name}}</strong></label>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="exampleInputEmail1">product : <strong>{{$product_detail->category_name}}</strong></label>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="exampleInputEmail1">Subcategory : <strong>{{$product_detail->subcategory_name}}</strong></label>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="exampleInputEmail1">Product Image</label>
                          @if(!empty($image->product_image))
                          <div class="mt-1 uploaded-file">
                            <a href="{{ asset('/public/uploads/product_image/' . $image->product_image) }}" target="_blank">
                            <img src="{{ asset('/public/uploads/product_image/' . $image->product_image) }}" style="width: 200px;">  
                            </a>
                          </div>
                        @endif
                      </div>
                      <div class="form-group col-md-6">
                        <label for="exampleInputEmail1">Generation : <strong>{{$product_detail->start_year}}-{{$product_detail->end_year}}</strong></label>
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
                        <table class="table " id="example1">
                          <thead>
                            <tr>
                              <th> Sr no </th>
                              <th> Group </th>
                              <th> Brand </th>
                              <th> Model</th>                            
                              <th> Product</th>
                              <th> Subcategory</th>
                              <th> Year</th>
                              <th> Variant</th>
                              <th> Action</th>
                            </tr>
                          </thead>
                          @php
                            $i = 1;
                            $lastGroup = null;
                            $groupColors = [];
                            $colorPalette = ['#f9f9f9', '#e6f7ff', '#fef9e7', '#e8f8f5', '#fbeee6']; // Add more if needed
                            $colorIndex = 0;
                          @endphp
                          <tbody>
                            @if($product)
                            @php $i=1; @endphp 
                            @foreach($product as $plist)
                            @php
                              $currentGroup = $plist->group_name;

                              // Assign color per unique group
                              if (!isset($groupColors[$currentGroup])) {
                                  $groupColors[$currentGroup] = $colorPalette[$colorIndex % count($colorPalette)];
                                  $colorIndex++;
                              }
                            @endphp
                            <tr>
                              <td style="background-color: {{ $groupColors[$currentGroup] }};">{{ $i }}</td>
                              <td style="background-color: {{ $groupColors[$currentGroup] }};">{{ $plist->group_name }}</td>
                              <td style="background-color: {{ $groupColors[$currentGroup] }};">{{ $plist->brand_name }}</td>
                              <td style="background-color: {{ $groupColors[$currentGroup] }};">{{ $plist->model_name }}</td>
                              <td style="background-color: {{ $groupColors[$currentGroup] }};">{{ $plist->category_name }}</td>
                              <td style="background-color: {{ $groupColors[$currentGroup] }};">{{ $plist->subcat_name }}</td>
                              <td style="background-color: {{ $groupColors[$currentGroup] }};">{{ $plist->start_year }}-{{ $plist->end_year }}</td>
                              <td style="background-color: {{ $groupColors[$currentGroup] }};">{{ $plist->part_type_label }}</td>
                              <td style="background-color: {{ $groupColors[$currentGroup] }};">
                                <a href="javascript:void(0)" class="del_inter" user_id="{{ $plist->id }}">
                                  <i class="mdi mdi-delete"></i>
                                </a>
                              </td>
                            </tr>
                            @php $i++; @endphp 
                            @endforeach
                            @endif
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
        

          <!-- Interchangeable product -->

          <script>
            $(document).ready( function () {
            var table = $('#example1').DataTable( {
              // "bPaginate": false,
              // "bInfo": false,
            });
          } );
          

          


          //Select the product for interchange
          $('#selectAll').on('change', function () {
              $('.product-check').prop('checked', this.checked).trigger('change');
          });
          let selectedProducts = [];
          //get id's of interchange product
          $(document).on('change', '.product-check', function () {
              let val = $(this).val();
              if ($(this).is(':checked')) {
                  if (!selectedProducts.includes(val)) selectedProducts.push(val);
              } else {
                  selectedProducts = selectedProducts.filter(id => id !== val);
              }
          });
          
          //save product to interchange
          document.getElementById('uploadLoader').style.display = 'none';
          

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
                        text: "product has been deleted.",
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
        </script>
        @endpush