<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendence;
use App\Models\Employee;

class AttendenceController extends Controller
{
    public function index(Request $request)
    {
        $employee = Employee::where('api_token',$request->token)->first();

        if($employee==null)
        {
            $response['success'] = false;
            $response['message'] = "Data Response";
            $response['data']    = null;
        }
        else
        {
            $response['success'] = true;
            $response['message'] = "list todos";
            $response['data']    = $employee->attendence;
        }

        
        return response()->json($response, 200);
    }

    public function show($id)
    {
        $data = Attendence::find($id);

        if($data)
        {
            $response['success'] = true;
            $response['message'] = "Data resource";
            $response['data']    = $data;
        }else{
            $response['success'] = false;
            $response['message'] = "Maaf data dengan id $id tidak berhasil di temukan";
            $response['data']    = null;
        }

        return response()->json($response, 200);

    }

    public function store(Request $request)
    {
        $nik = \DB::select("SELECT nik FROM `employees` WHERE api_token='$request->token' ");
        $validator = \Validator::make($request->all(), [
            'health_status' => 'required',
            'work_place' => 'required',
            'description' => 'required',
        ]);

        if($validator->fails())
        {
            $response['success'] = false;
            $response['message'] = $validator->messages();
            $response['data']    = null;
        }else{
            $attendence = new Attendence;
            $attendence->nik = $nik[0]->nik;
            $attendence->health_status = $request->health_status;
            $attendence->work_place = $request->work_place; 
            $attendence->description = $request->description;
            $attendence->save();


            $response['success'] = true;
            $response['message'] = "Data berhasil di tambahkan";
            $response['data']    = $attendence;
        }
        
        return response()->json($response, 201);
    }

    public function update(Request $request, $id)
    {
        $attendence = Attendence::findOrFail($id);
        $attendence->health_status = $request->health_status;
            $attendence->work_place = $request->work_place; 
            $attendence->description = $request->description;
            $attendence->save();

        return response()->json($attendence);
    }

    public function destroy($id)
    {
        $attendence = Attendence::find($id);

        if($attendence)
        {
            $response['success'] = true;
            $response['message'] = "Data berhasil di hapus";
            $response['data']    = $attendence;
            $attendence->delete();
        }else{
            $response['success'] = false;
            $response['message'] = "Maaf id $id tidak di temukan ";
            $response['data']    = null;
        }

        return response()->json($response);
    }
}
