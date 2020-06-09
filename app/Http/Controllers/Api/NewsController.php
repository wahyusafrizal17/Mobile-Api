<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $data = News::all();

        if($data->count() < 1)
        {
            $response['success'] = false;
            $response['message'] = "Data Kosong";
            $response['data']    = null;
        }else{
            $response['success'] = true;
            $response['message'] = "Data resource";
            $response['data']    = $data;
        }

        
        return response()->json($response, 200);
    }

    public function show($id)
    {
        $data = News::find($id);

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
        $validator = \Validator::make($request->all(), [
            'title' => 'required',
            'writer' => 'required',
            'description' => 'required',
            'view' => 'required',
            'image' => 'required',
        ]);

        if($validator->fails())
        {
            $response['success'] = false;
            $response['message'] = $validator->messages();
            $response['data']    = null;
        }else{
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $file->move('Buku',$fileName);

            $input = $request->all();
            $input['image'] = $fileName;
            
            $news = News::create($input);

            $response['success'] = true;
            $response['message'] = "Data berhasil di tambahkan";
            $response['data']    = $news;
        }
        
        return response()->json($response, 201);
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);
        $news->update($request->all());

        return response()->json($news);
    }

    public function destroy($id)
    {
        $news = News::find($id);

        if($news)
        {
            $response['success'] = true;
            $response['message'] = "Data berhasil di hapus";
            $response['data']    = $news;
            $news->delete();
        }else{
            $response['success'] = false;
            $response['message'] = "Maaf id $id tidak di temukan ";
            $response['data']    = null;
        }

        return response()->json($response);
    }

    public function Student(Request $request)
    {
        $student = Student::all();

        $response['success'] = true;
        $response['message'] = "Data resource";
        $response['data']    = $student;

        return response()->json($response);
    }

    public function Login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        $student = Student::where('email', '=', $email)->first();
        if(!Hash::check($password, $student->password)){
            $response['success'] = false;
            $response['message'] = "data Kosong";
            $response['data']    = null;

            return response()->json($response);
        }

            
            $apiToken= utf8_decode(str::random(42));

            $student->update([
                'api_token' => $apiToken
            ]);

            $response['success'] = true;
            $response['message'] = "data resource";
            $response['data']    = $student;
            $response['api-token'] = $apiToken;

            return response()->json($response);
        
    }

    public function Register(Request $request)
    {
        $user = new Student;

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->api_token = false;

        $user->save();

        $response['success'] = true;
        $response['message'] = "Data terdaftar";
        $response['data']    = $user;
        return response()->json($response);
    }

    public function viewUser($id)
    {
        $user = Student::find($id);

        if($user){
            $response['success'] = true;
            $response['message'] = "Data terdaftar";
            $response['data'] = $user;

            return response()->json($response, 200);
        }else{
            $response['success'] = false;
            $response['message'] = "Data terdaftar";
            $response['data'] = null;

            return response()->json($response, 404);
        }
    }
}
