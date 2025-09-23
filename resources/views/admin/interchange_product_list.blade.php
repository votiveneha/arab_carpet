@extends('admin.layouts.layout')

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
                          <label for="exampleInputEmail1">Make</label>
                          <select required class="form-select form-select-sm select-drop" id="brand" name="brand_id">
                            <option value="">Select Make</option>
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
                    </form>
                  </div>
                </div>
              </div>
            </div>  

            <div class="row">
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                  <a href="{{route('admin.InterchangeProduct')}}" class="btn btn-outline-info btn-fw" style="float: right;">Add Interchange Product</a>
                    <h4 class="card-title">Unique Product</h4>
                    <p class="card-description"> Unique Product List 
                    </p>
                    
                      <div class="table-responsive">
                        <table class="table" id="example">
                          <thead>
                            <tr>
                              <th> Sr no </th>
                              <th> Group </th>
                              <th> Make </th>
                              <th> Model </th>
                              <th> Generation </th>
                              <th> Part Type </th>
                              <th> Part </th>
                              <th> Variant </th>
                              <th> Action</th>
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
          let table = $('#example').DataTable({
              processing: true,
              serverSide: true,
              ajax: {
                  url: "{{url('/admin/getInterchangeList')}}",
                  
                  type: 'POST',
                  data: function (d) {
                    let brand_id = $('#brand').val();
                      if (!brand_id) {
                          //return false; // prevent request if brand not selected
                      }
                      return $.extend({}, d, {
                          brand_id: $('#brand').val(),
                          model_id: $('#model').val(),
                          category_id: $('#category').val(),
                          subcategory_id: $('#subcategory').val(),
                          _token: '{{ csrf_token() }}'
                      });
                  }
              },
              lengthMenu: [[10, 25, 50, 100,500, -1], [10, 25, 50, 100,500, "All"]],
              pageLength: 10,
              columns: [
                      { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                      { data: 'group', name: 'group' },
                      { data: 'brand', name: 'brand' },
                      { data: 'model', name: 'model' },
                      { data: 'generation', name: 'generation' },
                      { data: 'category', name: 'category' },
                      { data: 'subcategory', name: 'subcategory' },
                      { data: 'variant', name: 'variant' },
                      { data: 'action', name: 'action', orderable: false, searchable: false }
                  ],
              //deferLoading: 0 
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
        @endpush