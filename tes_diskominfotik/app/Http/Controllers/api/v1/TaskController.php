<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::latest()->get();
        return response([
            'success' => true,
            'message' => 'This lis of Task',
            'data' => $tasks
        ], 200);
    }
    public function store(Request $request)
    {
        // validate data
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'description' => 'required',
                'status' => 'required',
                'deadline' => 'required',
                'labels' => 'required'
            ],
            [
                'title.required' => 'Title of Task',
                'description.required' => 'Description Of Task',
                'status.required' => 'Choose Status',
                'deadline.required' => 'Deadline Period',
                'labels.required' => 'Choose labels task',

            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan isi yang kosong',
                'data' => $validator->errors()
            ], 401);
        }

        $task = Task::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'deadline' => $request->input('deadline'),
            'status' => $request->input('status'),
            'labels' => $request->input('labels'),
        ]);

        if ($task) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil disimpan',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Tidak berhasil disimpan"
            ], 401);
        }
    }
    public function show($id)
    {
        $task = Task::find($id);

        if ($task) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil data get id',
                'data' => $task,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }
    }
    public function update(Request $request)
    {
        //validate data
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'description' => 'required',
                'status' => 'required',
                'deadline' => 'required',
                'labels' => 'required',
            ],
            [
                'title.required' => 'Title of Task',
                'description.required' => 'Description Of Task',
                'status.required' => 'Choose Status',
                'deadline.required' => 'Deadline Period',
                'labels.required' => 'Choose labels task',
            ]
        );

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Silahkan Isi Bidang Yang Kosong',
                'data'    => $validator->errors()
            ], 401);
        } else {

            $task = Task::whereId($request->input('id'))->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'deadline' => $request->input('deadline'),
                'status' => $request->input('status'),
                'labels' => $request->input('labels'),
            ]);

            if ($task) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item Berhasil Diupdate!',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Item Gagal Diupdate!',
                ], 401);
            }
        }
    }
    public function destroy($id)
    {
        $task = Task::where('id', $id)->delete();
        if ($task) {
            return response()->json([
                'success' => true,
                'message' => ' berhasil dihapus',
            ], 200);
        } else {
            return response()->json([

                'success' => false,
                'message' => 'tidak berhasil dihapus'
            ], 400);
        }
    }
}
