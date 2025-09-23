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
    <?php
      $group_name=$group_id=$group_note="";  
      if($group_detail)
      {
        $group_id=$group_detail->id;
        $group_name=$group_detail->group_name;                   
        $group_note=$group_detail->group_note;                   
      }
    ?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <form class="forms-sample" id="groupForm" method="post" action="{{route('admin.addUniversalProduct',['id'=>$group_id])}}" enctype="multipart/form-data">
              {!! csrf_field() !!}
                <div class="row">
                  <div class="col-md-12 grid-margin stretch-card">
                    
                    <div class="card">
                      <div class="card-body">
                        <a href="{{route('admin.groupList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Group List</a>
                        <h4 class="card-title">Group Product Management</h4>
                        <p class="card-description"> Group Detail </p>
                        
                          <div class="row">
                            <div class="form-group col-md-6">
                              <input type="hidden" name="group_id" value="{{$group_id}}">
                              <label for="exampleInputUsername1">Group Name : <strong>{{$group_name}}</strong></label>
                            </div>                        
                            <div class="form-group col-md-6">
                              <label for="exampleInputUsername1">Group Note : <strong>{{$group_note}}</strong></label>
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
                        <h4 class="card-title">Group Model</h4>
                          <div class="table-responsive">
                            <table class="table table-striped" id="example">
                              <thead>
                                <tr>
                                  <th> Sr no </th>
                                  <th> Brand name </th>                            
                                  <th> Model name</th>                            
                                  <th> Generation</th>                            
                                </tr>
                              </thead>
                              <tbody>
                                @if($make_model)
                                @php $i=1; @endphp 
                                @foreach($make_model as $list)
                                <tr>
                                  <td>{{$i}}</td>
                                  <td> {{$list->brand_name}} </td>
                                  <td> {{$list->model_name}} </td>
                                  <td> {{$list->start_year}} - {{$list->end_year}} </td>
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

                <div class="row">
                  <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                      <div class="card-body">
                        <h4 class="card-title">Universal Part</h4>
                        <div class="table-responsive">
                          <table class="table table-striped" id="example1">
                            <thead>
                              <tr>
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
            var table = $('#example , #example1').DataTable( {
              "bPaginate": true,
              "bInfo": true,
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