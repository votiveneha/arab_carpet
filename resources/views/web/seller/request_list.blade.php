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
</style>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                  <a href="{{route('seller.addSellerRequest')}}" class="btn btn-outline-info btn-fw" style="float: right;">{{__('messages.add_request')}}</a>
                    <h4 class="card-title">{{__('messages.request_mngt')}}</h4>
                    
                    
                      <div class="table-responsive">
                        <table class="table table-striped" id="example">
                          <thead>
                            <tr>
                              <th> {{__('messages.tbl_sr_no')}} </th>
                              <th> {{__('messages.request_no')}} </th>
                              <th> {{__('messages.MAKE')}} </th>                            
                              <th> {{__('messages.MODEL')}}</th>
                              <th> {{__('messages.GENERATION')}}</th>
                              <th> {{__('messages.PART TYPE')}}</th>
                              <th> {{__('messages.PART')}}</th>
                              <th> {{__('messages.tbl_variant')}}</th>                            
                              <th> {{__('messages.request_status')}}</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if($sellreques)
                            @php $i=1; @endphp 
                            @foreach($sellreques as $list)
                            <tr>
                              <td>{{$i}}</td>
                              <td>#{{$list->request_id}}</td>
                              <td> {{$list->make==''?'--':$list->make}} </td>
                              <td> {{$list->model==''?'--':$list->model}} </td>
                              <td> {{$list->generation==''?'--':$list->generation}} </td>
                              <td> {{$list->category==''?'--':$list->category}} </td>
                              <td> {{$list->subcategory==''?'--':$list->subcategory}} </td>
                              <td> {{$list->is_car==1?__('messages.tbl_product'):__('messages.tbl_part')}} </td>
                              <td>
                                {{$list->request_status==0?__('messages.pending'):($list->request_status==1?__('messages.accept'):__('messages.reject'))}}
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
              language: {
                        url: "{{ app()->getLocale() == 'ar' ? asset('/public/js/datatable-ar.json') : '' }}"
                    },
              lengthMenu: [[10, 25, 50, 100,500, -1], [10, 25, 50, 100,500, "All"]]
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