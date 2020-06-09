<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Todo;
use App\Models\Employee;

class TodoController extends Controller
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
            $response['data']    = $employee->todos;
        }

        return response()->json($response, 200);
    }

    public function show($id)
    {
        $data = Todo::find($id);

        if($data)
        {
            $response['success'] = true;
            $response['message'] = "Data Response";
            $response['data']    = $data;
        }else{
            $response['success'] = false;
            $response['message'] = "Data dengan id " .$id. " Tidak di temukan";
            $response['data']    = null;
        }

        return response()->json($response, 200);
    }

    public function store(Request $request)
    {
        $nik = \DB::select("SELECT nik FROM `employees` WHERE api_token='$request->token' ");

        $validator = \Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if($validator->fails()){

            $response['success'] = false;
            $response['message'] = $validator->messages();
            $response['data']    = null;
        }else{
            $todo = new Todo;
            $todo->nik = $nik[0]->nik;
            $todo->title = $request->title;
            $todo->status = "Pending";
            $todo->save();

            $response['success'] = true;
            $response['message'] = "Data Successfull Created!";
            $response['data']    = $todo;
        }

        return response()->json($response, 201);

    }

    public function update(Request $request, $id)
    {
        $todo = Todo::find($id);

        if(!$todo)
        {
            $response['success'] = false;
            $response['message'] = "Data Not Found";
            $response['data']    = null;
        }else{
            $todo->update($request->all()); 
        
            $response['success'] = true;
            $response['message'] = "Data Successfull Updated!";
            $response['data']    = $todo;
        }

        return response()->json($response, 201);
    }

    public function destroy($id)
    {
        $todo = Todo::find($id);

        if(!$todo)
        {
            $response['success'] = false;
            $response['message'] = "Data Not Found";
            $response['data']    = null;
        }else{
            $todo->delete();

            $response['success'] = true;
            $response['message'] = "Data Successfull Deleted!";
            $response['data']    = $todo;
        }
    
        return response()->json($response, 201);
    }
}
