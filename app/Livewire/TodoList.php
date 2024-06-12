<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Attributes\Rule;
use Livewire\Component;

class TodoList extends Component
{
    #[Rule('required|min:5|max:50|string')]
    public $name;

    public function createTodo(){
        // validation
        $validated = $this->validateOnly('name');

        // store todo in db
        Todo::create($validated);

        // reset the form
        $this->reset();

        // send the the feedback
        session()->flash('message','Create '.$validated['name'].' todo');
    }

    public function render()
    {
        $todos = Todo::all();
        return view('livewire.todo-list',[
            'todos' => $todos
        ]);
    }
}
