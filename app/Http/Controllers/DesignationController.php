<?php

namespace App\Http\Controllers;

use App\company;
use App\department;
use App\designation;
use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        $companies = company::select('id', 'company_name')->get();

        if (request()->ajax()) {
            return datatables()->of(designation::with('company', 'department')->get())
                ->setRowId(function ($designation) {
                    // dd($designation);
                    return $designation->id;
                })
                ->addColumn('company', function ($row) {
                    return $row->company->company_name ?? ' ';
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('d/m/Y H:II');
                })
                ->addColumn('department', function ($row) {
                    return empty($row->department->department_name) ? '' : $row->department->department_name;
                })
                ->addColumn('added_by',function ($row)
				{
					return auth()->user()->first_name.' '.auth()->user()->last_name;
				})
                ->addColumn('rate_per_shift',function ($row)
				{
					return $row->rate_type==1?$row->rate_per_shift:'-';
				})
                ->addColumn('rate_type',function ($row)
				{
					return $row->rate_type==1?'Paid Per Shift':'Peace Rate';
				})
                ->addColumn('overtime_rate',function ($row)
				{
					return $row->rate_type==1?$row->overtime_rate:'-';
				})
                ->addColumn('action', function ($data) {
                    $button = '';
                    if (auth()->user()->can('edit-designation')) {
                        $button = '<button type="button" name="edit" id="' . $data->id . '" class="edit btn btn-primary btn-sm"><i class="dripicons-pencil"></i></button>';
                        $button .= '&nbsp;&nbsp;';
                    }
                    if (auth()->user()->can('delete-designation')) {
                        $button .= '<button type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="dripicons-trash"></i></button>';
                    }
                    return $button;
                })
                ->rawColumns(['action','rate_type'])
                ->make(true);
        }
        return view('organization.designation.index', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->overtime_rate == "");
        $logged_user = auth()->user();

        if ($logged_user->can('store-designation')) {
            $validator = Validator::make(
                $request->only('designation_name', 'company_id', 'department_id', 'rate_type', 'rate_per_shift', 'overtime_rate'),
                [
                    'designation_name' => 'required|unique:designations,designation_name,NULL,id,department_id,' . $request->department_id,
                    'company_id' => 'required',
                    'department_id' => 'required',
                    'rate_type' => 'required',
                    'rate_per_shift' => 'nullable',
                    'overtime_rate' => 'nullable',
                ]
            );

            $validator->after(function ($validator) use ($request) {
                if ($request->rate_type == "2") {
                    if ($request->rate_per_shift != "") {
                        $validator->errors()->add('rate_per_shift', 'The rate per shift field must be Empty when rate type is Peace Rate.');
                    }
                    if ($request->overtime_rate != "") {
                        $validator->errors()->add('overtime_rate', 'The overtime rate/h field must be Empty when rate type is Peace Rate.');
                    }
                } elseif ($request->rate_type == "1") {
                    if ($request->rate_per_shift == "") {
                        $validator->errors()->add('rate_per_shift', 'The rate per shift field is required');
                    }
                    if ($request->overtime_rate == "") {
                        $validator->errors()->add('overtime_rate', 'The overtime rate/h field is required');
                    }
                }
            });

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            }
            // dd($request->all());


            $data = [];

            $data['designation_name'] = $request->designation_name;
            $data['company_id'] = $request->company_id;
            $data['department_id'] = $request->department_id;
            $data['rate_type'] = $request->rate_type;
            $data['rate_per_shift'] = $request->rate_per_shift;
            $data['overtime_rate'] = $request->overtime_rate;




            designation::create($data);

            return response()->json(['success' => __('Data Added successfully.')]);
        }

        return response()->json(['success' => __('You are not authorized')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (request()->ajax()) {
            $data = designation::findOrFail($id);

            $departments = Department::select('id', 'department_name')->where('company_id', $data->company_id)->get();


            return response()->json(['data' => $data, 'departments' => $departments]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $logged_user = auth()->user();

        if ($logged_user->can('edit-designation')) {
            $id = $request->hidden_id;

            // $data = $request->only('designation_name', 'company_id', 'department_id');



            $validator = Validator::make(
                $request->only('designation_name', 'company_id', 'department_id', 'rate_type', 'rate_per_shift', 'overtime_rate'),
                [
                    'designation_name' => 'required|unique:designations,designation_name,' . $id . ',id,department_id,' . $request->department_id,
                    'rate_type' => 'required',
                    'rate_per_shift' => 'nullable',
                    'overtime_rate' => 'nullable',
                ]
            );


            $validator->after(function ($validator) use ($request) {
                if ($request->rate_type == "2") {
                    if ($request->rate_per_shift != "") {
                        $validator->errors()->add('rate_per_shift', 'The rate per shift field must be Empty when rate type is Peace Rate.');
                    }
                    if ($request->overtime_rate != "") {
                        $validator->errors()->add('overtime_rate', 'The overtime rate/h field must be Empty when rate type is Peace Rate.');
                    }
                } elseif ($request->rate_type == "1") {
                    if ($request->rate_per_shift == "") {
                        $validator->errors()->add('rate_per_shift', 'The rate per shift field is required');
                    }
                    if ($request->overtime_rate == "") {
                        $validator->errors()->add('overtime_rate', 'The overtime rate/h field is required');
                    }
                }
            });

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            }


            $data = [];

            $data['designation_name'] = $request->designation_name;
            if ($request->company_id) {
                $data['company_id'] = $request->company_id;
            }
            if ($request->department_id) {
                $data['department_id'] = $request->department_id;
            }
            if ($request->rate_type) {
                $data['rate_type'] = $request->rate_type;
                $data['rate_per_shift'] = $request->rate_per_shift;
                $data['overtime_rate'] = $request->overtime_rate;
            }


            designation::whereId($id)->update($data);

            return response()->json(['success' => __('Data is successfully updated')]);
        } else {
            return response()->json(['success' => __('You are not authorized')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!env('USER_VERIFIED')) {
            return response()->json(['error' => 'This feature is disabled for demo!']);
        }
        $logged_user = auth()->user();

        if ($logged_user->can('delete-designation')) {
            designation::whereId($id)->delete();
            return response()->json(['success' => __('Data is successfully deleted')]);
        }
        return response()->json(['success' => __('You are not authorized')]);
    }

    public function delete_by_selection(Request $request)
    {
        if (!env('USER_VERIFIED')) {
            return response()->json(['error' => 'This feature is disabled for demo!']);
        }
        $logged_user = auth()->user();

        if ($logged_user->can('delete-designation')) {

            $designation_id = $request['designationIdArray'];
            $designation = designation::whereIntegerInRaw('id', $designation_id);
            if ($designation->delete()) {
                return response()->json(['success' => __('Multi Delete', ['key' => trans('file.Designation')])]);
            } else {
                return response()->json(['error' => 'Error selected designation can not be deleted']);
            }
        }
        return response()->json(['success' => __('You are not authorized')]);
    }
}
