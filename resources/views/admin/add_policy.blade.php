@extends('admin.layouts.layout')

@section('content')
<style>
  .ck-editor__editable {
    min-height: 300px !important; /* Or whatever height you want */
  }
</style>
  <?php

    $policy_id=$policy_content=$policy_type=$policy_name=$policy_content_ar=$policy_content_fr=$policy_content_ru=$policy_content_fa=$policy_content_ur='';
    if($policies)
    {
      $policy_id=$policies->id;
      $policy_content=$policies->policy_content;
      $policy_type=$policies->policy_type;
      $policy_name=$policies->policy_name;
      $policy_content_ar=$policies->policy_content_ar;
      $policy_content_fr=$policies->policy_content_fr;
      $policy_content_ru=$policies->policy_content_ru;
      $policy_content_fa=$policies->policy_content_fa;
      $policy_content_ur=$policies->policy_content_ur;
    }

  ?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.policyList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Policy List</a>
                    <h4 class="subscription-title">Information Management</h4>
                    <p class="subscription-description"> Add / Update Policy  </p>
                    <p></p>
                    <form class="forms-sample" method="post" action="{{route('admin.addPolicy')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">
                        <div class="form-group col-md-6">
                          <label for="exampleInputplan_name1">Policy Name</label>
                          <input type="hidden" name="policy_id" value="{{$policy_id}}">
                          <input type="text" required class="form-control form-control-sm" id="policyname" placeholder="Policy Name" name="policy_name" value="{{$policy_name}}">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputplan_name1">Policy Type</label>
                          <select required class="form-select form-select-sm" name="policy_type" id="exampleInputDurationUnit1">
                            <option value="7" {{$policy_type==7?'selected':''}}>Index</option>
                            <option value="8" {{$policy_type==8?'selected':''}}>Matrix</option>
                            <option value="9" {{$policy_type==9?'selected':''}}>Layer</option>
                            <option value="10" {{$policy_type==10?'selected':''}}>Reference</option>
                            <option value="1" {{$policy_type==1?'selected':''}}>Privacy Policy</option>
                            <option value="2" {{$policy_type==2?'selected':''}}>Terms & Conditions</option>
                            <option value="3" {{$policy_type==3?'selected':''}}>About Us</option>
                            <option value="4" {{$policy_type==4?'selected':''}}>FAQ</option>
                            <option value="5" {{$policy_type==5?'selected':''}}>Contact</option>
                            <option value="6" {{$policy_type==6?'selected':''}}>How It Works</option>
                          </select>
                        </div>
                        <div class="form-group col-md-12">
                          <label for="video-introduction">Policy Content English</label>
                          <textarea  class="form-control form-control-sm" id="video-introduction" name="policy_content" rows="1" placeholder="Enter policy content here..." >{{$policy_content}}</textarea>
                        </div>
                        <div class="form-group col-md-12">
                          <label for="video-introduction">Policy Content Arabic</label>
                          <textarea dir="rtl" class="form-control form-control-sm" id="policy_arabic" name="policy_content_ar" rows="1" placeholder="أدخل محتوى السياسة هنا..." >{{$policy_content_ar}}</textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="policy_french">Policy Content French </label>
                          <textarea  class="form-control form-control-sm" id="policy_french" name="policy_content_fr" rows="1" placeholder="Saisissez le contenu de la politique ici..." >{{$policy_content_fr}}</textarea>
                        </div>
                        <div class="form-group col-md-12">
                          <label for="policy_rusian">Policy Content Rusian</label>
                          <textarea  class="form-control form-control-sm" id="policy_rusian" name="policy_content_ru" rows="1" placeholder="Введите содержание политики здесь..." >{{$policy_content_ru}}</textarea>
                        </div>
                        <div class="form-group col-md-12">
                          <label for="policy_dari">Policy Content Dari</label>
                          <textarea dir="rtl" class="form-control form-control-sm" id="policy_dari" name="policy_content_fa" rows="1" placeholder="محتوای پالیسی را اینجا وارد کنید..." >{{$policy_content_fa}}</textarea>
                        </div>
                        <div class="form-group col-md-12">
                          <label for="policy_urdu">Policy Content Urdu</label>
                          <textarea dir="rtl" class="form-control form-control-sm" id="policy_urdu" name="policy_content_ur" rows="1" placeholder="پالیسی کا مواد یہاں درج کریں..." >{{$policy_content_ur}}</textarea>
                        </div>

                      </div>

                      <input type="hidden" name="user_time" value="" id="user_timezone">
                      <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </form>
                    <span style="font-size: 12px;color: #d82828;">* Note:- If Policy already exist then this will update the previous policy content .</span>
                  </div>

                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
        </div>
        <!-- main-panel ends -->
        @endsection
        @push('scripts')

          <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
          <script>
              ClassicEditor
                  .create(document.querySelector('#policy_rusian'))

                  .catch(error => {
                      console.error(error);
                  });
          </script>
           <script>
              ClassicEditor
                  .create(document.querySelector('#policy_french'))

                  .catch(error => {
                      console.error(error);
                  });
          </script>

          <script>
              ClassicEditor
                  .create(document.querySelector('#video-introduction'))
                  .catch(error => {
                      console.error(error);
                  });
          </script>

          <script>
            ClassicEditor
                .create(document.querySelector('#policy_dari'), {
                    language: 'fa-AF',
                })
                .then(editor => {
                    editor.editing.view.change(writer => {
                        writer.setAttribute('dir', 'rtl', editor.editing.view.document.getRoot());
                    });
                })
                .catch(console.error);
          </script>

                    <script>
            ClassicEditor
                .create(document.querySelector('#policy_urdu'), {
                    language: 'ur',
                })
                .then(editor => {
                    editor.editing.view.change(writer => {
                        writer.setAttribute('dir', 'rtl', editor.editing.view.document.getRoot());
                    });
                })
                .catch(console.error);
          </script>

                    <script>
            ClassicEditor
                .create(document.querySelector('#policy_arabic'), {
                    language: 'ar',
                })
                .then(editor => {
                    editor.editing.view.change(writer => {
                        writer.setAttribute('dir', 'rtl', editor.editing.view.document.getRoot());
                    });
                })
                .catch(console.error);
          </script>
        @endpush
