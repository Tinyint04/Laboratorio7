<?php

namespace App\Http\Controllers;

use App\Models\etiqueta;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Gate as Authorize;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        $tasks = Task::with('user', 'etiquetas')->latest()->paginate(10);
        return view('tasks.index', ['tasks' => $tasks]);
    }

    public function create()
    {
        $users = User::all();
        $etiquetas = etiqueta::all();
        return view('tasks.create', compact('users', 'etiquetas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'priority' => 'required|in:baja,media,alta',
            'user_id' => 'nullable|exists:users,id',
            'etiquetas' => 'nullable|array',
            'etiquetas.*' => 'exists:etiquetas,id',

        ]);

        $task = Task::create([
            'name' => $request->name,
            'description' => $request->description,
            'priority' => $request->priority,
            'completed' => false,
            'user_id' => $request->user_id,
        ]);

        $task->etiquetas()->attach($request->etiquetas);
        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        return view('tasks.show', ['task' => $task]);
    }

    public function edit(Task $task)
    {
        $users = User::all(); // Obtener todos los usuarios
        $etiquetas = etiqueta::all(); // Obtener todas las etiquetas


        return view('tasks.edit', compact('task', 'etiquetas', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'priority' => 'required|in:baja,media,alta',
            'completed' => 'required',
            'user_id' => 'nullable|exists:users,id',
            'etiquetas' => 'nullable|array',
            'etiquetas.*' => 'exists:etiquetas,id',
        ]);

        $task->update([
            'name' => $request->name,
            'description' => $request->description,
            'priority' => $request->priority,
            'completed' => $request->completed,
            'user_id' => $request->user_id,
        ]);

        $task->etiquetas()->sync($request->etiquetas);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    public function complete(Task $task)
    {
        $task->update(['completed' => true]);

        return redirect()->route('tasks.index')->with('success', 'Task marked as completed.');
    }
}
