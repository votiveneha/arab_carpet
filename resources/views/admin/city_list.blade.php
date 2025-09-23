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
                        <div class="form-group col-md-4">
                          <label for="exampleInputEmail1">Country</label>
                          <select required class="form-select form-select-sm select-drop" id="country_id" name="country_id">
                            <option value="">Select Country</option>
                            @if($country)
                            @foreach($country as $countrys)
                              <option value="{{$countrys->country_id }}">{{$countrys->country_name}}</option>
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
                  <a href="{{route('admin.addCity')}}" class="btn btn-outline-info btn-fw" style="float: right;">Add City</a>
                    <h4 class="card-title">Location Management</h4>
                    <p class="card-description"> City List 
                    </p>
                    <form id="bulkDeleteForm" method="POST" action="{{ route('admin.bulkUpdateCity') }}">
                      @csrf
                      <div class="table-responsive">
                        <table class="table table-striped" id="example">
                          <thead>
                            <tr>
                              <th><input type="checkbox" id="selectAll"></th>
                              <th> Sr no </th>
                              <th> Country </th>
                              <th> City </th>
                              <th> Status</th>
                              <th> Action</th>
                            </tr>
                          </thead>
                          <tbody>
                           
                          </tbody>
                        </table>
                      </div>
                      <button type="submit" class="btn btn-outline-danger mt-3" name="action" id="bulkDeleteBtn" value="inactive" >Inactive Selected</button>
                      <button type="submit" class="btn btn-outline-success mt-3" name="action" id="bulkDeleteBtn" value="active">Active Selected</button>
                    </form>
                    
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
            $(document).on('change','.product_status',function(){
              
              var status=$(this).val();
              var user_id=$(this).attr('user');
              $.ajax({
                url: "{{url('/admin/updateCityStatus')}}",
                type: "POST",
                datatype: "json",
                data: {
                  status: status,
                  product_id:user_id,
                  '_token':'{{csrf_token()}}'
                },
                success: function(result) {
                  Swal.fire({
                    title: "Success!",
                    text: "Status updated!",
                    icon: "success"
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
          let table = $('#example').DataTable({
              processing: true,
              serverSide: true,
              ajax: {
                  url: "{{url('/admin/getCityList')}}",
                  
                  type: 'POST',
                  data: function (d) {
                      return $.extend({}, d, {
                          country_id: $('#country_id').val(),
                          _token: '{{ csrf_token() }}'
                      });
                  }
              },
              lengthMenu: [[10, 25, 50, 100,500, -1], [10, 25, 50, 100,500, "All"]],
              pageLength: 10,
              columns: [
                      { data: 'checkbox', orderable: false, searchable: false },
                      { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                      { data: 'country', name: 'country' },
                      { data: 'city', name: 'city' },
                      { data: 'status', name: 'status', orderable: false, searchable: false },
                      { data: 'action', name: 'action', orderable: false, searchable: false }
                  ],
              //deferLoading: 0 
          });

          // Reload table when filters change
          $('#filters select').on('change', function () {
              table.ajax.reload();
          });
          
          
          // document.getElementById('selectAll').addEventListener('click', function (e) {
          //   let checkboxes = document.querySelectorAll('.selectBox');
          //   checkboxes.forEach(cb => cb.checked = e.target.checked);
          // });
        </script>
        <script>
          let selectedProductIds = new Set();

          // Track checkbox changes across pages
          $(document).on('change', '.selectBox', function () {
            const productId = $(this).val();
            if (this.checked) {
              selectedProductIds.add(productId);
            } else {
              selectedProductIds.delete(productId);
            }
          });

          // Handle "Select All"
          $('#selectAll').on('change', function () {
            const isChecked = this.checked;
            $('.selectBox').each(function () {
              $(this).prop('checked', isChecked).trigger('change');
            });
          });

          // Handle button click
          document.querySelectorAll('button[name="action"]').forEach(button => {
            button.addEventListener('click', function (e) {
              e.preventDefault();

              if (selectedProductIds.size === 0) {
                Swal.fire({
                  icon: 'warning',
                  title: 'No selection',
                  text: 'Please select at least one City.',
                });
                return;
              }

              Swal.fire({
                title: 'Are you sure?',
                text: `Selected City will be marked as ${this.value}.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: this.value === 'inactive' ? '#d33' : '#28a745',
                cancelButtonColor: '#3085d6',
                confirmButtonText: `Yes, ${this.value} selected`
              }).then((result) => {
                if (result.isConfirmed) {
                  // Inject hidden inputs for selected IDs
                  const form = document.getElementById('bulkDeleteForm');
                  form.innerHTML += [...selectedProductIds].map(id => 
                    `<input type="hidden" name="ids[]" value="${id}">`
                  ).join('');
                  
                  // Set the action input
                  const hiddenAction = document.createElement('input');
                  hiddenAction.type = 'hidden';
                  hiddenAction.name = 'action';
                  hiddenAction.value = this.value;
                  form.appendChild(hiddenAction);

                  form.submit();
                }
              });
            });
          });
        </script>

        @endpush