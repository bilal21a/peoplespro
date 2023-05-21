@extends('layout.main')
@section('content')
    <section>
        <div class="container-fluid">

            {{-- <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Holy guacamole!</strong> You should check in on some of those fields below.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div> --}}

            {{-- <div class="card">
                <div class="card-body">

                    <div class="card-title text-center">
                        <h3>{{ __('Daily Attendances') }}<span id="details_month_year"></span></h3>
                    </div>

                    <form method="post" id="filter_form" class="form-horizontal">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 offset-md-3 mb-2">
                                <label for="day_month_year">{{ __('Select Date') }}</label>
                                <div class="input-group">
                                    <input class="form-control month_year date" placeholder="{{ __('Select Date') }}"
                                        readonly="" id="day_month_year" name="day_month_year" type="text"
                                        value="{{ now()->format(env('date_format')) }}">
                                    <button type="submit" class="filtering btn btn-primary"><i class="fa fa-search"></i>
                                        {{ trans('file.Search') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div> --}}
        </div>
        <div class="container-fluid">
            <form method="post" id="filter_form" class="form-horizontal">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <select name="company_id" id="company_id" class="form-control selectpicker dynamic"
                            data-live-search="true" data-live-search-style="contains" data-first_name="first_name"
                            data-last_name="last_name" title='{{ __('Selecting', ['key' => trans('file.Company')]) }}...'>
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
                    <div class="col-md-6 ">
                        <input class="form-control month_year date" placeholder="{{ __('Select Date') }}"
                            id="day_month_year" name="day_month_year" type="text"
                            value="{{ now()->format(env('date_format')) }}">
                    </div>

                </div>
                <button type="submit" class="filtering btn btn-primary"><i class="fa fa-search"></i>
                    {{ trans('file.Search') }}
                </button>
            </form>
        </div>

        <div class="table-responsive">
            <table id="daily_attendance-table" class="table type1-table">
                <thead>
                    <tr>
                        <th>Employee Name</th>
                        <th>Staff Id</th>
                        <th>Place of Work</th>
                        <th>Time/date in</th>
                        <th>Time/date out</th>
                        <th>Rest Time</th>
                        <th>Working hrs</th>
                        <th>Overtime</th>
                        <th>Amount Paid</th>
                    </tr>
                </thead>
            </table>
        </div>
    </section>
@endsection

@push('scripts')
    <script type="text/javascript">
        (function($) {
            "use strict";

            $(document).ready(function() {

                let date = $('.date');
                date.datepicker({
                    format: '{{ env('Date_Format_JS') }}',
                    autoclose: true,
                    todayHighlight: true,
                    endDate: new Date()
                });


                // fill_datatable();

                function fill_datatable(filter_month_year = null, company_id = null, shift_id = null, dept_id =
                    null, designation_id = null) {
                    let table_table = $('.type1-table').DataTable({
                        initComplete: function() {
                            this.api().columns([2, 4]).every(function() {
                                var column = this;
                                var select = $(
                                        '<select><option value=""></option></select>')
                                    .appendTo($(column.footer()).empty())
                                    .on('change', function() {
                                        var val = $.fn.dataTable.util.escapeRegex(
                                            $(this).val()
                                        );

                                        column
                                            .search(val ? '^' + val + '$' : '',
                                                true,
                                                false)
                                            .draw();
                                    });

                                column.data().unique().sort().each(function(d, j) {
                                    select.append('<option value="' + d + '">' +
                                        d +
                                        '</option>');
                                    $('select').selectpicker('refresh');
                                });
                            });
                        },
                        responsive: true,
                        fixedHeader: {
                            header: true,
                            footer: true
                        },
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ route('attendances.index') }}",
                            data: {
                                filter_month_year: filter_month_year,
                                company_id: company_id,
                                shift_id: shift_id,
                                dept_id: dept_id,
                                designation_id: designation_id,
                                "_token": "{{ csrf_token() }}"
                            }
                        },

                        columns: [
                            {
                                data: 'employee_name',
                                name: 'employee_name'
                            },
                            {
                                data: 'staff_id',
                                name: 'staff_id'
                            },
                            {
                                data: 'plce_work',
                                name: 'plce_work',
                            },
                            {
                                data: 'clock_in',
                                name: 'clock_in',
                            },
                            {
                                data: 'clock_out',
                                name: 'clock_out',
                            },
                            {
                                data: 'total_rest',
                                name: 'total_rest',
                            },
                            {
                                data: 'total_work',
                                name: 'total_work',
                            },
                            {
                                data: 'overtime',
                                name: 'overtime',
                            },
                            {
                                data: 'amount_pad',
                                name: 'amount_pad'
                            }
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
                        dom: '<"row"lfB>rtip',
                        buttons: [{
                                extend: 'pdf',
                                text: '<i title="export to pdf" class="fa fa-file-pdf-o"></i>',
                                exportOptions: {
                                    columns: ':visible:Not(.not-exported)',
                                    rows: ':visible'
                                },
                            },
                            {
                                extend: 'csv',
                                text: '<i title="export to csv" class="fa fa-file-text-o"></i>',
                                exportOptions: {
                                    columns: ':visible:Not(.not-exported)',
                                    rows: ':visible'
                                },
                            },
                            {
                                extend: 'print',
                                text: '<i title="print" class="fa fa-print"></i>',
                                exportOptions: {
                                    columns: ':visible:Not(.not-exported)',
                                    rows: ':visible'
                                },
                            },
                            {
                                extend: 'colvis',
                                text: '<i title="column visibility" class="fa fa-eye"></i>',
                                columns: ':gt(0)'
                            },
                        ],
                    });
                    table_table.columns([8]).visible(false);

                    var des = $('#designation_id').val()
                    des = des == '' ? null : des
                    console.log('des: ', des);
                    if (des != null) {
                        // var dataTable = $('#daily_attendance-table').DataTable();


                        var des_url = "{{ route('get_designation', [':id']) }}";
                        des_url = des_url.replace(':id', des);
                        $.ajax({
                            url: des_url,
                            success: function(res) {
                                console.log('res: ', res);
                                var rate_type = res.rate_type != null ? res.rate_type : 1
                                if (rate_type == 1) {
                                    table_table.columns([5,6,7]).visible(true);
                                    table_table.columns([8]).visible(false);
                                } else {
                                    table_table.columns([5,6,7]).visible(false);
                                    table_table.columns([8]).visible(true);
                                }
                                table_table.draw();
                            }
                        })
                    } else {

                        // new $.fn.dataTable.FixedHeader($('.type1-table')
                        //             .DataTable());
                    }



                }


                $('#filter_form').on('submit', function(e) {
                    e.preventDefault();
                    var filter_month_year = $('#day_month_year').val();
                    var company_id = $('#company_id').val();
                    var shift_id = $('#shift_id').val();
                    var dept_id = $('#dept_id').val();
                    var designation_id = $('#designation_id').val();

                    if (filter_month_year !== '' && company_id !== '' && shift_id !== '' && dept_id !==
                        '' && designation_id !== '') {
                        $('#daily_attendance-table').DataTable().destroy();
                        fill_datatable(filter_month_year, company_id, shift_id, dept_id,
                            designation_id);
                    } else {
                        alert('Select filter options');
                    }
                });
            });
        })(jQuery);


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
        $('#designation_id').change(function() {
            if ($(this).val() !== '') {
                let value = $(this).val();
                let _token = $('input[name="_token"]').val();

            }
        });
    </script>
@endpush
