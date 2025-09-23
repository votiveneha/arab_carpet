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
                  <a href="{{route('admin.createGroup')}}" class="btn btn-outline-info btn-fw" style="float: right;">Add Group</a>
                    <h4 class="card-title">Group Management</h4>
                    <p class="card-description"> Group List 
                    </p>
                    
                      <div class="table-responsive">
                        <table class="table table-striped" id="example">
                          <thead>
                            <tr>
                              
                              <th> Sr no </th>
                              <th> Group name </th>
                              <th> Group Note </th>                            
                              <th> Product</th>
                              <th> Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if($groups)
                            @php $i=1; @endphp 
                            @foreach($groups as $list)
                            <tr>
                              
                              <td>{{$i}}</td>
                              <td> {{$list->group_name}} </td>
                              <td> {{$list->group_note}} </td>
                              <!-- <td><select class="user_status form-select form-select-sm" user="{{$list->id}}">
                                  <option value="0" {{$list->is_active==0?'selected':''}}>Inactive</option>
                                  <option value="1" {{$list->is_active==1?'selected':''}}>Active</option>                                
                                </select>
                              </td> -->
                              <td><a href="{{route('admin.addUniversalProduct',['id'=>$list->id])}}">Universal Product</a></td>
                              <td>                              
                                <a href="{{route('admin.createGroup')}}/{{ $list->id }}"><i class="mdi mdi-lead-pencil"></i></a> | 
                                <a href="{{route('admin.viewGroupDetail',['id'=>$list->id])}}"><i class="mdi mdi-eye"></i></a>
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
              lengthMenu: [[10, 25, 50, 100,500, -1], [10, 25, 50, 100,500, "All"]]
            });
          } );


          $(document).ready(function () {
            $(document).on('change','.user_status',function(){
              var status=$(this).val();
              var user_id=$(this).attr('user');
              $.ajax({
                url: "{{url('/admin/updateGroupStatus')}}",
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
                    text: "Group Status updated!",
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

        
        @endpush