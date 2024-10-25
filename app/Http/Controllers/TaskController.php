<?php

namespace App\Http\Controllers;
use App\Models\Task;
use App\Mail\TaskUpdated;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'assignedto' => 'required|exists:users,id',
            'due_date' => 'required|date',
        ]);
    
        $task = Task::create(array_merge($validatedData, [
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $request->assignedto,
            'due_date' => $request->due_date,
            'status' => 'Pending',
        ]));
        ActivityLog::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'action' => 'Task created',
        ]);
        $assignedUser = $task->user;
        Mail::to('jozeknjtaureg@gmail.com')->send(new TaskUpdated($task));
        return redirect()->route('dashboard')->with('success', 'Task created successfully.');
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'due_date' => 'nullable|date',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $task = Task::findOrFail($id);

        $task->update($request->only('title', 'description', 'status', 'due_date', 'user_id'));

        ActivityLog::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'action' => 'Task updated',
        ]);
        $assignedUser = $task->user;
        Mail::to('jozeknjtaureg@gmail.com')->send(new TaskUpdated($task));
        return redirect()->back()->with('success', 'Task updated successfully!');
    }
    
    public function destroy(Task $task)
    {
        $task->activityLogs()->delete();
    
        $task->delete();
    
        return redirect()->back()->with('success', 'Task deleted successfully.'); 
    }
    

    public function dashboard(Request $request)
    {

        if (auth()->user()->role === 'admin') {

            $query = Task::with('activityLogs.user'); 
    
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('due_date')) {
                $query->where('due_date', $request->due_date);
            }
        
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            }
            $tasks = $query->get();
            $users = User::all();
            $activityLogs = ActivityLog::all(); // Fetch all activity logs
            return view('dashboard', compact('tasks', 'users', 'activityLogs'));

        } else {
            $query = Task::where('user_id', auth()->id())->with('activityLogs.user');
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
        
            if ($request->filled('due_date')) {
                $query->where('due_date', $request->due_date);
            }
        
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            }
            $tasks = $query->get();
            $users = User::all();
            $activityLogs = ActivityLog::all(); // Fetch all activity logs
            return view('dashboard', compact('tasks', 'users', 'activityLogs')); 

        }
    }

    public function index_api()
    {
        $tasks = Task::all();
    return TaskResource::collection($tasks);
    }

    public function show_api(Task $task)
    {
        // Ensure the task belongs to the authenticated user
        $this->authorize('view', $task);
        return new TaskResource($task);
    }

    public function store_api(Request $request)
    {
        // dd(Auth::id());
        // \Log::info('User ID: ' . Auth::id());
        // \Log::info('Request Data: ' . json_encode($request->all()));
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:pending,completed',
            'due_date' => 'nullable|date',
        ]);

        $task = Task::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'due_date' => $request->due_date,
        ]);

        return response()->json(new TaskResource($task), 201);
    }

    public function update_api(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:pending,completed',
            'due_date' => 'nullable|date',
        ]);
    
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'due_date' => $request->due_date,
        ]);
    
        return response()->json(new TaskResource($task), 200);
    }
    
    public function destroy_api(Task $task)
    {
        // Delete related activity logs
        $task->activityLogs()->delete();
    
        // Delete the task
        $task->delete();
    
        return response()->json([
            'message' => 'Task and related logs deleted successfully'
        ], 200);
    }
    
    
}
