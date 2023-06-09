@extends('layout.main')
@section('content')
    <section>
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-md-6">
                    <select name="company_id" id="company_id" class="form-control selectpicker dynamic" data-live-search="true"
                        data-live-search-style="contains" data-first_name="first_name" data-last_name="last_name"
                        title='{{ __('Selecting', ['key' => trans('file.Company')]) }}...'>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <select name="shift_id" id="shift_id" class="selectpicker form-control" data-live-search="true"
                        data-live-search-style="contains" title='Select Shift...'>
                    </select>
                </div>
                <div class="col-md-6">
                    <select name="dept_id" id="dept_id" class="selectpicker form-control" data-live-search="true"
                        data-live-search-style="contains" title='Select Department...'>
                    </select>
                </div>
                <div class="col-md-6">
                    <select name="designation_id" id="designation_id" class="selectpicker form-control"
                        data-live-search="true" data-live-search-style="contains" title='Select Designation...'>
                    </select>
                </div>

                <div class="col-md-6">
                    <select name="employee_id" id="employee_id" class="selectpicker form-control" multiple
                        data-live-search="true" data-live-search-style="contains"
                        title='{{ __('Selecting', ['key' => trans('file.Employee')]) }}...'>
                    </select>
                </div>
                <div class="col-md-6">
                    <input class="form-control" placeholder="Place of Work" id="place_of_work" name="place_of_work"
                        type="text">
                </div>
                <div class="col-md-6 ">
                    <input class="form-control" placeholder="Amount Paid" id="amount_paid" name="amount_paid"
                        type="text">
                </div>
            </div>
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"> {{ __('Add Attendance') }} </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-info" id="add_attendance_btn" data-toggle="modal"
                                data-target=".add-modal-data">
                                <span class="fa fa-plus"></span> {{ __('Add New') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"> {{ __('Update Attendance') }} </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form autocomplete="off" name="update_attendance_from" id="update_attendance_from"
                                method="get" accept-charset="utf-8">

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input class="form-control date" placeholder="Start Date" readonly
                                                id="attendance_date1" name="attendance_date1" type="text">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input class="form-control date" placeholder="End Date" readonly
                                                id="attendance_date2" name="attendance_date2" type="text">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions box-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-check-square-o"></i> {{ trans('file.Get') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table id="update_attendance-table" class="table type1-table">
                <thead>
                    <tr>
                        <th>Employee Name</th>
                        <th>Staff Id</th>
                        <th>Place of Work</th>
                        <th>Time/date in</th>
                        <th>Time/date out</th>
                        <th>Amount paid</th>
                        <th>Rest Time</th>
                        <th>Working hrs</th>
                        <th>Overtime</th>
                        <th>Added by</th>
                        <th class="not-exported">{{ trans('file.action') }}</th>
                    </tr>
                </thead>
            </table>
        </div>

        <div id="editModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title">{{ trans('file.Update') }}</h5>
                        <button type="button" data-dismiss="modal" id="close" aria-label="Close"
                            class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <span id="form_result"></span>
                        <form autocomplete="off" method="post" id="edit_form" class="form-horizontal">
                            @csrf
                            <div class="row">
                                <div id="att_date_edit_show_hide" class="col-md-6 form-group">
                                    <label for="attendance_date_edit"><strong>{{ __('Attendance Date') }}
                                            *</strong></label>
                                    <input type="text" name="attendance_date" id="attendance_date_edit" required
                                        readonly class="form-control date" placeholder="{{ __('Attendance Date') }}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="clock_in_edit"><strong>{{ __('Clock In') }}</strong></label>
                                    <input type="text" name="clock_in" id="clock_in_edit" class="form-control time"
                                        value="" required>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="clock_out_edit"><strong>{{ __('Clock Out') }}</strong></label>
                                    <input type="text" name="clock_out" id="clock_out_edit" class="form-control time"
                                        value="" required>
                                </div>
                                <div class="col-md-6 form-group edit_show_form">
                                    <label for="place_of_work"><strong>Place of Work</strong></label>
                                    <input type="text" name="place_of_work" id="place_of_work_edit" class="form-control">
                                </div>
                                <div class="col-md-6 form-group edit_show_form amount_paid_edit_block">
                                    <label for="amount_paid"><strong>Amount Paid</strong></label>
                                    <input type="text" name="amount_paid" id="amount_paid_edit" class="form-control">
                                </div>
                                <div class="container">
                                    <div class="form-group" align="center">
                                        <input type="hidden" name="action" id="action" />
                                        <input type="hidden" name="hidden_id" id="hidden_id" />
                                        <input type="hidden" name="employee_id" id="hidden_employee_id" />
                                        <input type="hidden" name="edit_employee_id" id="edit_employee_id" />
                                        <input type="hidden" name="hidden_place_of_work" id="hidden_place_of_work" />
                                        <input type="hidden" name="hidden_amount_paid" id="hidden_amount_paid" />
                                        <input type="submit" name="action_button" id="action_button"
                                            class="btn btn-warning" value={{ trans('file.Add') }} />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="confirmModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">{{ trans('file.Confirmation') }}</h2>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <h4 align="center">{{ __('Are you sure you want to remove this data?') }}</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" name="ok_button" id="ok_button"
                            class="btn btn-danger">{{ trans('file.OK') }}'</button>
                        <button type="button" class="close btn-default"
                            data-dismiss="modal">{{ trans('file.Cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@push('scripts')
    <script type="text/javascript">
        (function($) {
            "use strict";
            $(document).ready(function() {
                $('.date').datepicker({
                    format: '{{ env('Date_Format_JS') }}',
                    autoclose: true,
                    todayHighlight: true,
                    endDate: new Date()
                }).datepicker("setDate", new Date());
            });


            fill_datatable();

            function fill_datatable(attendance_date1 = '', attendance_date2 = '', company_id = '', employee_id = '') {
                var des = $('#designation_id').val()
                console.log('des: ', des);
                if (des != null) {
                    var des_url = "{{ route('get_designation', [':id']) }}";
                    des_url = des_url.replace(':id', des);
                    $.ajax({
                        url: des_url,
                        success: function(res) {
                            console.log('res: ', res);
                            var rate_type = res.rate_type != null ? res.rate_type : 1


                            let table_table = $(`.type1-table`).DataTable({
                                responsive: true,
                                fixedHeader: {
                                    header: true,
                                    footer: true
                                },
                                processing: true,
                                serverSide: true,
                                ajax: {
                                    url: "{{ route('update_attendances.index') }}",
                                    data: {
                                        attendance_date1: attendance_date1,
                                        attendance_date2: attendance_date2,
                                        company_id: company_id,
                                        employee_id: employee_id,
                                        "_token": "{{ csrf_token() }}",
                                    }
                                },


                                columns: [{
                                        data: 'employee',
                                        name: 'employee'
                                    },
                                    {
                                        data: 'staff_id',
                                        name: 'staff_id'
                                    },
                                    {
                                        data: 'place_of_work',
                                        name: 'place_of_work'
                                    },
                                    {
                                        data: 'clock_in',
                                        name: 'clock_in'
                                    },
                                    {
                                        data: 'clock_out',
                                        name: 'clock_out'
                                    },
                                    {
                                        data: 'amount_paid',
                                        name: 'amount_paid'
                                    },
                                    {
                                        data: 'total_rest',
                                        name: 'total_rest'
                                    },
                                    {
                                        data: 'total_work',
                                        name: 'total_work'
                                    },
                                    {
                                        data: 'overtime',
                                        name: 'overtime'
                                    },
                                    {
                                        data: 'added_by',
                                        name: 'added_by'
                                    },
                                    {
                                        data: 'action',
                                        name: 'action',
                                        orderable: false
                                    },
                                ],


                                "order": [],
                                'language': {
                                    'lengthMenu': '_MENU_ {{ __('records per page') }}',
                                    "info": '{{ trans('file.Showing') }} _START_ - _END_ (_TOTAL_)',
                                    "search": '{{ trans('file.Search') }}',
                                    'paginate': {
                                        'previous': '{{ trans('file.Previous') }}',
                                        'next': '{{ trans('file.Next') }}'
                                    }
                                },


                                'select': {
                                    style: 'multi',
                                    selector: 'td:first-child'
                                },
                                'lengthMenu': [
                                    [10, 25, 50, -1],
                                    [10, 25, 50, "All"]
                                ],

                            });
                            // new $.fn.dataTable.FixedHeader(table_table);


                            if (rate_type == 1) {
                                table_table.column(5).visible(false);
                                table_table.column(6).visible(true);
                                table_table.column(7).visible(true);
                                table_table.column(8).visible(true);
                            } else {
                                table_table.column(5).visible(true);
                                table_table.column(6).visible(false);
                                table_table.column(7).visible(false);
                                table_table.column(8).visible(false);
                            }

                        }
                    })
                }



            }

            $('#update_attendance_from').on('submit', function(e) {
                e.preventDefault();
                let attendance_date1 = $('#attendance_date1').val();
                let attendance_date2 = $('#attendance_date2').val();
                let company_id = $('#company_id').val();
                let employee_id = $('#employee_id').val();
                let designation_id = $('#designation_id').val();
                if (attendance_date1 !== '' && attendance_date2 !== '' && company_id !== '' && employee_id !==
                    '' && designation_id!='') {
                    $('#update_attendance-table').DataTable().destroy();
                    fill_datatable(attendance_date1, attendance_date2, company_id, employee_id);
                    $('#hidden_employee_id').val($('#employee_id').val());
                } else {
                    // let data_name = '';
                    // if (company_id == '') {
                    //     data_name += '{{ __('Company') }}';
                    // }
                    // if (employee_id == '') {
                    //     if (data_name != '') {
                    //         data_name += ', ';
                    //     }
                    //     data_name += '{{ __('Employee') }}';
                    // }
                    // if (attendance_date1 == '') {
                    //     if (data_name != '') {
                    //         data_name += ', ';
                    //     }
                    //     data_name += '{{ __('Start Date') }}';
                    // }
                    // if (attendance_date2 == '') {
                    //     if (data_name != '') {
                    //         data_name += ', ';
                    //     }
                    //     data_name += '{{ __('End Date') }}';
                    // }
                    alert('{{ __('Select') }} Company, Employee, Designation, Start Date, End Date');
                }

            });


            $('#add_attendance_btn').on('click', function() {
                $('#att_date_edit_show_hide').show();
                let company_id = $('#company_id').val();
                let employee_id = $('#employee_id').val();
                var dept_id = $('#dept_id').val();
                var designation_id = $('#designation_id').val();
                let place_of_work = $('#place_of_work').val();
                let amount_paid = $('#amount_paid').val();
                if (company_id !== '' && employee_id !== '' && employee_id !== ''&& dept_id !== ''&& designation_id !== ''&& place_of_work !== '') {
                    $('#hidden_employee_id').val($('#employee_id').val());
                    $('#hidden_place_of_work').val($('#place_of_work').val());
                    $('#hidden_amount_paid').val($('#amount_paid').val());
                    $('.modal-title').text('{{ __('Add Attendance') }}');
                    $('#action_button').val('{{ trans('file.Add') }}');
                    $('#action').val('{{ trans('file.Add') }}');
                    $('.edit_show_form').hide()

                    $('#editModal').modal('show');
                } else {
                    // let data_name = '';
                    // if (company_id == '') {
                    //     data_name += '{{ __('Company') }}';
                    // }
                    // if (employee_id == '') {
                    //     if (data_name != '') {
                    //         data_name += ', ';
                    //     }
                    //     data_name += '{{ __('Employee') }}';
                    // }
                    alert('{{ __('Select') }} all feilds');
                }
            });

            $(document).on('click', '.edit', function() {
                let id = $(this).attr('id');
                console.log('id: ', id);
                $('.edit_show_form').show()
                $('#edit_employee_id').val(id);

                let target = "{{ route('update_attendances.index') }}/" + id + '/get';
                $.ajax({
                    url: target,
                    dataType: "json",
                    success: function(html) {
                        $('#attendance_date_edit').val(html.data.attendance_date);
                        $('#att_date_edit_show_hide').hide();
                        $('#clock_in_edit').val(html.data.clock_in);
                        $('#clock_out_edit').val(html.data.clock_out);
                        $('#place_of_work_edit').val(html.data.place_of_work);
                        if (html.data.designation_type!=1) {
                            $('#amount_paid_edit').val(html.data.amount_paid);
                            $('.amount_paid_edit_block').show()
                        }else{
                            $('#amount_paid_edit').val(null);
                            $('.amount_paid_edit_block').hide()
                        }

                        $('#hidden_id').val(html.data.id);
                        $('.modal-title').text(html.data.attendance_date);
                        $('#action').val('{{ trans('file.Edit') }}');
                        $('#action_button').val('{{ trans('file.Edit') }}');
                        $('#editModal').modal('show');
                    }
                })
            });

            $('#edit_form').on('submit', function(event) {
                event.preventDefault();
                if ($('#action').val() == '{{ trans('file.Add') }}') {
                    $.ajax({
                        url: "{{ route('update_attendances.store') }}",
                        method: "POST",
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: "json",
                        success: function(data) {
                            console.log(data);
                            var html = '';
                            if (data.errors) {
                                html = '<div class="alert alert-danger">';
                                for (var count = 0; count < data.errors.length; count++) {
                                    html += '<p>' + data.errors[count] + '</p>';
                                }
                                html += '</div>';
                            }
                            if (data.success) {
                                html = '<div class="alert alert-success">' + data.success +
                                    '</div>';
                                $('#edit_form')[0].reset();
                                fill_datatable();
                                // $('#update_attendance-table').DataTable().ajax.reload();
                            }
                            $('#form_result').html(html).slideDown(300).delay(5000).slideUp(300);
                        }
                    })
                }

                if ($('#action').val() == '{{ trans('file.Edit') }}') {

                    $.ajax({
                        url: "{{ route('update_attendances.update') }}",
                        method: "POST",
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: "json",
                        success: function(data) {
                            var html = '';
                            if (data.errors) {
                                html = '<div class="alert alert-danger">';
                                for (var count = 0; count < data.errors.length; count++) {
                                    html += '<p>' + data.errors[count] + '</p>';
                                }
                                html += '</div>';
                            }
                            if (data.success) {
                                html = '<div class="alert alert-success">' + data.success +
                                    '</div>';
                                setTimeout(function() {
                                    $('#editModal').modal('hide');
                                    $('#update_attendance-table').DataTable().ajax.reload();
                                    $('#edit_form')[0].reset();

                                }, 2000);

                            }
                            $('#form_result').html(html).slideDown(300).delay(5000).slideUp(300);
                        }
                    });
                }
            });

            let delete_id;
            $(document).on('click', '.delete', function() {
                delete_id = $(this).attr('id');

                $('#confirmModal').modal('show');
                $('.modal-title').text('{{ __('DELETE Record') }}');
                $('#ok_button').text('{{ trans('file.OK') }}');

            });


            $('#ok_button').on('click', function() {
                let target = "{{ route('update_attendances.index') }}/" + delete_id + '/delete';
                $.ajax({
                    url: target,
                    beforeSend: function() {
                        $('#ok_button').text('{{ trans('file.Deleting...') }}');
                    },
                    success: function(data) {
                        let html = '';
                        if (data.error) {
                            html = '<div class="alert alert-danger">' + data.error + '</div>';
                        }
                        if (data.success) {
                            html = '<div class="alert alert-success">' + data.success + '</div>';
                        }
                        setTimeout(function() {
                            $('#confirmModal').modal('hide');
                            $('#update_attendance-table').DataTable().ajax.reload();
                        }, 2000);
                    }
                })
            });

            // $('.dynamic').change(function () {
            //     if ($(this).val() !== '') {
            //         let value = $(this).val();
            //         let first_name = $(this).data('first_name');
            //         let last_name = $(this).data('last_name');
            //         let _token = $('input[name="_token"]').val();
            //         $.ajax({
            //             url: "{{ route('dynamic_employee') }}",
            //             method: "POST",
            //             data: {value: value, _token: _token, first_name: first_name, last_name: last_name},
            //             success: function (result) {
            //                 $('select').selectpicker("destroy");
            //                 $('#employee_id').html(result);
            //                 $('select').selectpicker();

            //             }
            //         });
            //     }
            // });
            $('.dynamic').change(function() {
                if ($(this).val() !== '') {
                    let value = $(this).val();
                    let _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('dynamic_office_shifts') }}",
                        method: "POST",
                        data: {
                            value: value,
                            _token: _token,
                            dependent: 'shift_name'
                        },
                        success: function(result) {
                            $('select').selectpicker("destroy");
                            $('#shift_id').html(result);
                            $('select').selectpicker();

                            $.ajax({
                                url: "{{ route('dynamic_department') }}",
                                method: "POST",
                                data: {
                                    value: value,
                                    _token: _token,
                                    dependent: 'department_name'
                                },
                                success: function(result) {
                                    $('select').selectpicker("destroy");
                                    $('#dept_id').html(result);
                                    $('select').selectpicker();
                                }
                            });
                        }
                    });

                    let first_name = $(this).data('first_name');
                    let last_name = $(this).data('last_name');
                    $.ajax({
                        url: "{{ route('dynamic_employee') }}",
                        method: "POST",
                        data: {value: value, _token: _token, first_name: first_name, last_name: last_name},
                        success: function (result) {
                            $('select').selectpicker("destroy");
                            $('#employee_id').html(result);
                            $('select').selectpicker();

                        }
                    });

                }
            });
            $('#dept_id').change(function() {
                if ($(this).val() !== '') {
                    let value = $(this).val();
                    let _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ route('dynamic_designation_department') }}",
                        method: "POST",
                        data: {
                            value: value,
                            _token: _token,
                            designation_name: 'designation_name'
                        },
                        success: function(result) {
                            $('select').selectpicker("destroy");
                            $('#designation_id').html(result);
                            $('select').selectpicker();
                        }
                    });
                }
            });
            $('#shift_id').change(function() {
                $('#designation_id').selectpicker('val', '');
            //     console.log("shift change");
            //     if ($(this).val() !== '') {
            //         let shift_id= $('#shift_id').val();
            //         let first_name = $(this).data('first_name');
            //         let last_name = $(this).data('last_name');
            //         let _token = $('input[name="_token"]').val();
            //         $.ajax({
            //             url: "{{ route('get_shift_based_employee') }}",
            //             method: "POST",
            //             data: {shift_id: shift_id, _token: _token, first_name: first_name, last_name: last_name},
            //             success: function (result) {
            //                 $('select').selectpicker("destroy");
            //                 $('#employee_id').html(result);
            //                 $('select').selectpicker();

            //             }
            //         });
            //     }
            });
            $('#designation_id').change(function() {
                if ($(this).val() !== '') {
                    let shift_id= $('#shift_id').val();
                    let value = $(this).val();
                    let first_name = 'first_name';
                    let last_name = 'last_name';
                    let _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ route('dynamic_designationEmployee') }}",
                        method: "POST",
                        data: {
                            value: value,
                            _token: _token,
                            first_name: first_name,
                            last_name: last_name,
                            shift_id: shift_id
                        },
                        success: function(result) {
                            $('select').selectpicker("destroy");
                            $('#employee_id').html(result);
                            $('select').selectpicker();

                            var des_url = "{{ route('get_designation', [':id']) }}";
                            des_url = des_url.replace(':id', value);
                            $.ajax({
                                url: des_url,
                                success: function(res) {
                                    var rate_type = res.rate_type != null ? res.rate_type : 1;
                                    if (rate_type == 1) {
                                        $(`#amount_paid`).hide()
                                    } else {
                                        $(`#amount_paid`).show()

                                    }

                                }
                            })
                        }
                    });
                }
            });


            $('#close').on('click', function() {
                $('#edit_form')[0].reset();
                $('#update_attendance-table').DataTable().ajax.reload();

            });
        })(jQuery);
    </script>
@endpush
