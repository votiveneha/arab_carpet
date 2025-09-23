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
</style>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <form class="forms-sample" id="groupForm" method="post" action="{{route('admin.addUniqueProduct')}}" enctype="multipart/form-data">
              {!! csrf_field() !!}
                <div class="row">
                  <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                      <div class="card-body">
                        <a href="{{route('admin.uniqueProductList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Unque Product List</a>
                        <h4 class="card-title">Unique Product</h4>
                        <p class="card-description"> Add / Update Unique Product  </p>
                          <div class="row">
                            <div class="form-group col-md-4">
                              <label for="exampleInputEmail1">Make</label>
                              <select required class="form-select form-select-sm" id="brand" name="brand_id">
                                <option value="">Select Make</option>  
                                @if($brand)
                                @foreach($brand as $brands)
                                  <option value="{{$brands->id }}" >{{$brands->brand_name}}</option>
                                @endforeach
                                @endif
                              </select>
                            </div>  
                            <div class="form-group col-md-4">
                              <label for="exampleInputEmail1">Model</label>
                              <select required class="form-select form-select-sm" id="model" name="model_id">
                                <option>Select Model</option>
                                
                              </select>
                            </div>
                            <div class="form-group col-md-4">
                              <label for="exampleInputEmail1">Generation</label>
                              <select required class="form-select form-select-sm" id="generation" name="generation_id">
                                <option>Select Model</option>
                                
                              </select>
                            </div>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                      <div class="card-body">
                          <div class="table-responsive">
                            <table class="table table-striped" id="example">
                              <thead>
                                <tr>
                                  <td><input type="checkbox" id="selectAll"></td>
                                  <th> Sr no </th>
                                  <th> Part Type name </th>                            
                                  <th> Part name</th>                          
                                </tr>
                              </thead>
                              <tbody>
                                @if($parts)
                                @php $i=1; @endphp 
                                @foreach($parts as $list)
                                <tr>
                                  <td><input type="checkbox"  name="ids[]" value="{{ $list->id }}" class="selectBox" data-category-id="{{ $list->category_id }}"></td>
                                  <td>{{$i}}</td>
                                  <td> {{$list->category_name}} </td>
                                  <td> {{$list->subcat_name}} </td>
                                </tr>
                                @php $i++; @endphp 
                                @endforeach
                                @endif
                              </tbody>
                            </table>
                          </div>
                          <button type="submit" class="btn btn-primary me-2">Submit</button>
                        
                        
                      </div>
                    </div>
                  </div>
                </div>
            </form>

          </div>
          <!-- content-wrapper ends -->
        </div>
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

       <script>
        $(document).ready( function () {
            var table = $('#example').DataTable( {
              "bPaginate": true,
              "bInfo": true,
              lengthMenu: [[10, 25, 50, 100,500, -1], [10, 25, 50, 100,500, "All"]],
              columnDefs: [
                              { orderable: false, targets: 0 } // Disable sorting on the first column (checkboxes)
                          ]
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

            $('#groupForm').on('submit', function (e) {
              var table = $('#example').DataTable();
              var allIds = [];
              var allcategoryIds = [];

              table.$('input.selectBox:checked').each(function () {
                  allIds.push($(this).val());
                  allcategoryIds.push($(this).data('category-id'));
              });

              // Remove existing hidden fields
              $('#groupForm').find('input[name="ids[]"], input[name="category_ids[]"]').remove();

              // Add new hidden fields
              $.each(allIds, function (index, id) {
                  $('<input>').attr({
                      type: 'hidden',
                      name: 'ids[]',
                      value: id
                  }).appendTo('#groupForm');

                  $('<input>').attr({
                      type: 'hidden',
                      name: 'category_ids[]',
                      value: allcategoryIds[index]
                  }).appendTo('#groupForm');
              });
            });

          } );
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
                text: 'Please select at least one Part Type to delete.',
              });
              return;
            }

            Swal.fire({
              title: 'Are you sure?',
              text: "Selected Part Type will be deleted.",
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