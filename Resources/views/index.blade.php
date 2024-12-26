@extends('backend.master')
@push('styles')
    <link rel="stylesheet" href="{{asset('public/backend/css/student_list.css')}}"/>
@endpush
@section('mainContent')

    {!! generateBreadcrumb() !!}


    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row justify-content-center">

                <div class="col-lg-12">
                    <!-- tab-content  -->
                    <div class="tab-content " id="myTabContent">
                        <!-- General -->
                        <div class="tab-pane fade white-box show active" id="Activation"
                             role="tabpanel" aria-labelledby="Activation-tab">
                            <div class="main-title mb-25">


                                <form action=""
                                      method="GET"
                                      enctype="multipart/form-data">

                                    <div class="row row-gap-24 align-items-end">

                                        <div class="col-xl-4">
                                            <div class="primary_input">
                                                <label class="primary_input_label"
                                                       for="roles">{{__('common.Select')}} {{__('common.Role')}} </label>
                                                <select class="multypol_check_select active" name="roles[]" id="roles"

                                                        multiple>

                                                    @foreach($roles as $key=>$role)
                                                        <option
                                                            value="{{$key}}" {{in_array($key,request('roles')??[])?'selected':''}}>{{$role}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-4">
                                            <div class="search_course_btn">
                                                <label class="primary_input_label "
                                                       for="roles"> </label>
                                                <button type="submit"
                                                        class="primary-btn radius_30px fix-gr-bg">{{__('frontend.Filter')}} </button>
                                            </div>
                                        </div>
                                    </div>


                                </form>
                            </div>
                        </div>

                    </div>


                </div>


            </div>

        </div>
    </section>


    <section class="admin-visitor-area up_st_admin_visitor mt-4">
        <div class="container-fluid p-0">
            <div class="white-box">
                <div class="row justify-content-center">

                    <div class="col-lg-12 pt-4">
                        <div class="QA_section QA_section_heading_custom check_box_table">
                            <div class="QA_table ">
                                <!-- table-responsive -->
                                <div class="">
                                    <table id="lms_table" class="table Crm_table_active3">
                                        <thead>
                                        <tr>
                                            <th scope="col">{{__('common.SL')}}</th>
                                            <th scope="col">{{__('common.Name')}}</th>
                                            @foreach($roles as $key=>$role)
                                                <th scope="col">{{$role}}</th>
                                            @endforeach
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

        </div>
    </section>
    @if(isModuleActive('Org'))
        <div class="modal fade admin-query" id="branchModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">


                    <div class="modal-header">
                        <h4 class="modal-title">{{__('common.Select')}} {{__('blog.Org Branch')}} </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"><i
                                class="ti-close "></i></button>
                    </div>

                    <div class="modal-body">
                        <div class="text-center">

                            <div class="white_boxx ">
                                <div class="org_table ">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="primary_input mb-35">
                                                <input type="hidden" name="user_id" id="user_id">
                                                <select class="primary_select" name="org_branch_code"
                                                        id="org_branch_code">
                                                    <option
                                                        data-display="{{__('common.Select')}} {{__('org.Branch')}}"
                                                        value="">{{__('common.Select')}} {{__('org.Branch')}} </option>
                                                    @foreach($branches as $branch)
                                                        @include('org::branch._single_select_option',['branch'=>$branch,'level'=>1])
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-40 d-flex justify-content-center">
                            <button class="primary-btn float-end fix-gr-bg" id="branchModalClose"
                                    type="button">{{__('assignment.Assign')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    @php
        $parem ='?';
    if (!empty(request('roles'))){
        foreach (request('roles') as $key=>$role){
                  $parem.=     'roles[]='.$role.'&';
        }
    }
            $url = route('usertype.list-data').$parem;
    @endphp
    <script>
        dataTableOptions.serverSide = true
        dataTableOptions.processing = true
        dataTableOptions.ajax = '{!! $url !!}';
        dataTableOptions.columns = [
            {data: 'DT_RowIndex', name: 'id'},
            {data: 'name', name: 'name'},

                @foreach ($roles as $key => $role)
            {
                data: 'role_{{$key}}', name: 'role_{{$key}}'
            },
            @endforeach


        ]
        let table = $('#lms_table').DataTable(dataTableOptions);

        $(document).on("click", ".branchModal", function () {
            $('#user_id').val($(this).data('user'));
            $('#org_branch_code option[value="' + $(this).data('branch') + '"]').prop('selected', true);
            $("#org_branch_code").niceSelect('update');
            $('#branchModal').modal('show');
        });
        $(document).on("click", "#branchModalClose", function () {

            let user = $('#user_id').val();
            let org = $("#org_branch_code option:selected").val();

            var formData = {
                user: user,
                org: org,
            };
            $.ajax({
                type: "POST",
                url: "{{route('usertype.assignOrg')}}",
                data: formData,
                success: function (data) {

                    table.ajax.url('{!! $url !!}').load(null, false);

                    toastr.success("{{__('common.Operation successful')}}", "{{__('common.Success')}}");
                }, error: function (request, status, error) {
                    toastr.error('{{__('common.Something Went Wrong')}}', '{{__('common.Error')}}')
                }
            });

            $('#user_id').val('');
            $("#org_branch_code").prop('selectedIndex', 0);
            $('#branchModal').modal('hide');
        });
        $("body").on('change', '.userSelect', function () {
            let user = $(this).data('user')
            let role = $(this).data('role')

            var formData = {
                user: user,
                role: role,
                status: $(this).is(":checked"),
            };
            $.ajax({
                type: "POST",
                url: "{{route('usertype.setting')}}",
                data: formData,
                success: function (data) {

                    table.ajax.url('{!! $url !!}').load(null, false);

                    toastr.success("{{__('common.Operation successful')}}", "{{__('common.Success')}}");
                }, error: function (request, status, error) {
                    toastr.error('{{__('common.Something Went Wrong')}}', '{{__('common.Error')}}')
                }
            });
        });
    </script>
@endpush
