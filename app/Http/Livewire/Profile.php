<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public User $user;
    public $upload;

    protected $rules = [
        'user.username' => 'max:24',
        'user.about' => 'max:140',
        'user.birthday' => 'sometimes',
        'upload' => 'nullable|image|max:2000'
    ];

    public function mount()
    {
        $this->user = auth()->user();
    }

    public function save()
    {
        $this->validate();

        $this->user->save();

        if($this->upload) {

            $this->user->update([
                'avatar' => $this->upload->store('/', 'avatars'),
            ]);
        }

        $this->emitSelf('notify-saved');
    }

    public function updatedNewAvatar()
    {
        $this->validate(['upload' => 'image|max:2000']);
    }

    public function render()
    {
        return view('livewire.profile');
    }
}
