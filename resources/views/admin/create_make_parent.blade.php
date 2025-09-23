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
            <form class="forms-sample" id="groupForm" method="post" action="{{route('admin.addMakeParent')}}" enctype="multipart/form-data">
              {!! csrf_field() !!}
                <div class="row">
                  <div class="col-md-12 grid-margin stretch-card">
                    <?php
                      $mparents_name=$mparents_id=$mparents_text="";  
                      if($parent_detail)
                      {
                        $mparents_id=$parent_detail->id;
                        $mparents_name=$parent_detail->mparents_name;                   
                        $mparents_text=$parent_detail->mparents_text;                   
                      }
                    ?>
                    <div class="card">
                      <div class="card-body">
                        <a href="{{route('admin.parentList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Parent List</a>
                        <h4 class="card-title">Parent Management</h4>
                        <p class="card-description"> Add / Update Parent  </p>
                        
                          <div class="row">
                            <div class="form-group col-md-6">
                              <input type="hidden" name="mparents_id" value="{{$mparents_id}}">
                              <label for="exampleInputUsername1">Parent Name</label>
                              <input required type="text" class="form-control form-control-sm" placeholder="Enter Parent Name" aria-label="Servicename" name="mparents_name" value="{{$mparents_name}}">
                            </div>                        
                            <div class="form-group col-md-6">
                              
                              <label for="exampleInputUsername1">Parent Note</label>
                              <input required type="text" class="form-control form-control-sm" placeholder="Enter Parent Note" aria-label="Servicename" name="mparents_text" value="{{$mparents_text}}">
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
                                  <th> Brand name </th>                            
                                </tr>
                              </thead>
                              <tbody>
                                @if($brand)
                                @php
                                    $i = 1;
                                @endphp
                                @foreach($brand as $list)
                                <tr>
                                  <td><input type="checkbox" {{ in_array($list->id, $parent_brand) ? 'checked' : '' }}  name="ids[]" value="{{ $list->id }}" class="selectBox" ></td>
                                  <td>{{$i}}</td>
                                  <td> {{$list->brand_name}} </td>
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
          } );
       </script>

       <script>
          document.getElementById('selectAll').addEventListener('click', function (e) {
            let checkboxes = document.querySelectorAll('.selectBox');
            checkboxes.forEach(cb => cb.checked = e.target.checked);
          });
        </script>
        @endpush