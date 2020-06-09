<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $data = \DB::select("SELECT * FROM `employees` WHERE api_token='$request->token' ");

        if($data==null)
        {
            $response['success'] = false;
            $response['message'] = "Data Response";
            $response['data']    = null;
        }
        else
        {
            $response['success'] = true;
            $response['message'] = "list todos";
            $response['data']    = $data;
        }


        
        return response()->json($response, 200);
    }

    public function show($id)
    {
        $data = Employee::find($id);

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

    public function Register(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'nik' => 'required|integer',
            'name' => 'required',
            'email' => 'required',
            'password'  => 'required',
            'photo' => 'required'
        ]);

        if($validator->fails()){

            $response['success'] = false;
            $response['message'] = $validator->messages();
            $response['data']    = null;
        }else{
            $file = $request->file('photo');
            $fileName = $file->getClientOriginalName();
            $file->move('Employee', $fileName);
            
            $employee = new Employee;
            $employee->nik = $request->nik;
            $employee->name = $request->name;
            $employee->email = $request->email;
            $employee->password = Hash::make($request->password);
            $employee->photo = $fileName;
            $employee->api_token = '0';
            $employee->save();

            $response['success'] = true;
            $response['message'] = "Data Successfull Created!";
            $response['data']    = $employee;
        }

        return response()->json($response, 201);

    }

    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);

        if(!$employee)
        {
            $response['success'] = false;
            $response['message'] = "Data Not Found";
            $response['data']    = null;
        }else{
            $employee->update($request->all()); 
        
            $response['success'] = true;
            $response['message'] = "Data Successfull Updated!";
            $response['data']    = $employee;
        }

        return response()->json($response, 201);
    }

    public function destroy($id)
    {
        $employee = Employee::find($id);

        if(!$employee)
        {
            $response['success'] = false;
            $response['message'] = "Data Not Found";
            $response['data']    = null;
        }else{
            $employee->delete();

            $response['success'] = true;
            $response['message'] = "Data Successfull Deleted!";
            $response['data']    = $employee;
        }
    
        return response()->json($response, 201);
    }

    public function Login(Request $request)
    {
        $nik = $request->nik;
        $password = $request->password;

        $employee = Employee::where('nik', $nik)->first();

        if($employee){
            if(!Hash::check($password,  $employee->password))
        {
            
            $response['message'] = "Maaf Nik " .$nik. " Not Found";
            return response()->json($response, 404);
            
        }else{
            $apiToken = utf8_decode(Str::random(40));

            $employee->update([
                'api_token' => $apiToken
            ]);

            $response['success'] = true;
            $response['message'] = "Welcome " . $employee->name;
            $response['data']    = $employee;
            $response['api_token'] = $apiToken;
            return response()->json($response, 200);
        }
        }else{
            $response['message'] = "Maaf Nik " .$nik. " Not Found";
            return response()->json($response, 404);
        }

        

    }
}
