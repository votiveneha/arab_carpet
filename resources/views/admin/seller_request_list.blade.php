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
            <div class="row">
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                  
                    <h4 class="card-title">Request Management</h4>
                    
                    
                      <div class="table-responsive">
                        <table class="table table-striped" id="example">
                          <thead>
                            <tr>
                              <th> Sr No </th>
                              <th> Request No </th>
                              <th> Seller </th>
                              <th> Make </th>                            
                              <th> Model</th>
                              <th> Generation</th>
                              <th> Part Type</th>
                              <th> Part</th>
                              <th> Type</th>                            
                              <th> Status</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if($sellreques)
                            @php $i=1; @endphp 
                            @foreach($sellreques as $list)
                            <tr>
                              <td>{{$i}}</td>
                              <td>#{{$list->request_id}}</td>
                              <td> {{$list->user_name}}</td>
                              <td> {{$list->make==''?'--':$list->make}} </td>
                              <td> {{$list->model==''?'--':$list->model}} </td>
                              <td> {{$list->generation==''?'--':$list->generation}} </td>
                              <td> {{$list->category==''?'--':$list->category}} </td>
                              <td> {{$list->subcategory==''?'--':$list->subcategory}} </td>
                              <td> {{$list->is_car==1?__('messages.tbl_product'):__('messages.tbl_part')}} </td>
                              <td><select class="request_status form-select form-select-sm" user="{{$list->id}}">
                                  <option value="0" {{$list->request_status==0?'selected':''}}>{{__('messages.pending')}}</option>
                                  <option value="1" {{$list->request_status==1?'selected':''}}>{{__('messages.accept')}}</option>                                
                                  <option value="2" {{$list->request_status==2?'selected':''}}>{{__('messages.reject')}}</option>                                
                                </select>
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
              // language: {
              //           url: "{{ app()->getLocale() == 'ar' ? asset('/public/js/datatable-ar.json') : '' }}"
              //       },
              lengthMenu: [[10, 25, 50, 100,500, -1], [10, 25, 50, 100,500, "All"]]
            });
          } );


          $(document).ready(function () {
            $(document).on('change','.request_status',function(){
              var status=$(this).val();
              var user_id=$(this).attr('user');
              $.ajax({
                url: "{{url('/admin/updateRequestStatus')}}",
                type: "POST",
                datatype: "json",
                data: {
                  status: status,
                  user:user_id,
                  '_token':'{{csrf_token()}}'
                },
                success: function(result) {
                  Swal.fire({
                    title: "Success!",
                    text: "Request Status updated!",
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