<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class TodoList extends Component
{
    use WithPagination;

    #[Rule('required|min:5|max:50|string')]
    public $name;

    public $search;

    public $editingId;

    #[Rule('required|min:5|max:50|string')]
    public $editingName;

    public function createTodo(){
        // validation
        $validated = $this->validateOnly('name');

        // store todo in db
        Todo::create($validated);

        // reset the form
        $this->reset();

        // send the the feedback
        session()->flash('message','Create '.$validated['name'].' todo');

        // reseting a page
        $this->resetPage();
    }

    public function toggleCompleted($todoId){
       $todo = Todo::findOrFail($todoId);
       $todo->completed = !$todo->completed;
       $todo->save();
    }

    public function editTodo($todoId){
        $this->editingId = $todoId;
        $this->editingName = Todo::findOrFail($todoId)->name;
    }

    public function cancelEdit(){
        $this->reset('editingId', 'editingName');
    }


    /**
     * @return [type]
     */
    public function updateTodo(){
        $this->validateOnly('editingName');
        $todo = Todo::findOrFail($this->editingId);
        $todo->name = $this->editingName;
        $todo->save();
        $this->cancelEdit();
    }

    public function deleteTodo($todoId) {
        Todo::find($todoId)->delete();
    }

        public function render()
    {
        $todos = Todo::latest()->where('name', 'like', "%{$this->search}%")->paginate(5);
        return view('livewire.todo-list',[
            'todos' => $todos
        ]);
    }
}
