<?php


namespace App\Services;


use App\Models\Note;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use phpDocumentor\Reflection\Types\Mixed_;

class NotesService
{
    public function getAllNotes($user): \Illuminate\Database\Eloquent\Collection
    {
        return Note::query()->whereIn('category_id', $user->categories->pluck('id'))->get();
    }

    public function createNote($data, $user): Note
    {
        $note = new Note($data);
        $note->category_id = $data['category_id'];
        $note->user()->associate($user);
        $note->save();

        return $note;
    }

    public function getNote($id, $user)
    {
        $note = Note::query()->find($id);

        if (!$note || !$user->categories()->where('id', $note->category_id)->exists())
        {
            return null;
        }

        return $note;
    }

    public function updateNote($note, $data): Note
    {
        $note->category_id = $data['category_id'];
        $note->update($data);

        return $note;
    }
}
