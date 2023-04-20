<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    private Todo $todo;

    public function __construct(Todo $todo)
    {
        $this->todo = $todo;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255']
        ]);
        $this->todo->fill($validated)->save();

        return ['message' => 'ok'];
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255']
        ]);
        $this->todo->findOrFail($id)->update($validated);
        return ['message' => 'ok'];
        // return redirect()->route('todo.index');
    }

    public function show($id)
    {
        $todo = Todo::findOrFail($id);

        if (!$todo) {
            return response()->json(['message' => 'Todo not found'], 404);
        }

        return response()->json($todo, 200);
    }

    public function delete($id)
    {
        $this->todo->findOrFail($id)->delete();
        return ['message' => 'ok'];
        // return redirect()->route('todo.index');
    }
}
