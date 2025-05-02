<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::where('user_id', Auth::id())->orderBy('is_done', 'desc')->get();

        $todosCompleted = Todo::where('user_id',Auth::id())
                ->where('is_done', true) 
                ->count();

        return view('todo.index', compact('todos', 'todosCompleted'));
    }

    public function create()
    {
        return view('todo.create');
    }

    public function edit(Todo $todo)
    {
        if(Auth::id() == $todo->user_id){
            return view('todo.edit', compact('todo'));
        }else{
            return redirect()->route('todo.view')->with('danger', 'You are not authorized to edit this todo!');
        }
    }

    public function update(Request $request, Todo $todo){
        $request->validate([
            'title' => 'required|max:255' 
        ]);

        $todo->update([
            'title' => ucfirst($request->title),
        ]);
        return redirect()->route('todo.view')->with('success', 'Todo updated successfully!');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $todo = Todo::create([
            'title' => ucfirst($request->title),
            'user_id' => Auth::id(),
            'is_done' => false,
        ]);
        return redirect()->route('todo.view')->with('success', 'Todo Created Successfully');
    }

    public function complete(Todo $todo){
        if(Auth::id() == $todo->user_id){
            $todo->update(['is_done' => true]);
            return redirect()->route('todo.view')->with('success', 'Todo completed successfully');
        }else{
            return redirect()->route('todo.view')->with('error', 'You are not authorized to complete this todo');
        }
    }
    
    public function uncomplete(Todo $todo){
        if(Auth::id() == $todo->user_id){
            $todo->update(['is_done' => false]);
            return redirect()->route('todo.view')->with('success', 'Todo Uncompleted successfully');
        }else{
            return redirect()->route('todo.view')->with('error', 'You are not authorized to complete this todo');
        }
    }

    public function destroy(Todo $todo)
    {
        if (Auth::id() === $todo->user_id) {
            $todo->delete();
            return redirect()->route('todo.view')->with('success', 'Todo deleted successfully!');
        }
        return redirect()->route('todo.view')->with('danger', 'You are not authorized to delete this todo!');
    }

    public function destroyCompleted()
    {
        $todosCompleted = Todo::where('user_id', Auth::id())
                            ->where('is_done', true)
                            ->get();

        $todosCompleted->each(function ($todo) {
            $todo->delete();
        });

        return redirect()->route('todo.view')->with('success', 'All completed todos deleted successfully!');
    }
}