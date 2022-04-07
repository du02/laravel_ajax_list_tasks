<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Task extends Controller
{

    public function index()
    {
        return view('index');
    }

    public function getTasks()
    {
        $tasks = \App\Models\Task::orderBy('difficulty')->get();

        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'task' => 'required',
            'difficulty' => 'required',
        ]);

        if(!$validator->fails()){
            $task = new \App\Models\Task();
            $task->create($data);

            return response()->json(['success' => true, 'message' => 'Tarefa criada com Sucesso!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Não foi possível armazenar a tarefa']);
        }

    }

    public function destroy($id)
    {
        $task = \App\Models\Task::findOrfail($id);
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => '"' . $task->task . '" - Concluído(a) e removido(a)!'
        ]);
    }
}
