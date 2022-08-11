<?php


namespace App\Services;


use App\Models\Note;


class NotesService
{
    public function getAllNotes($user): \Illuminate\Database\Eloquent\Collection
    {
        return Note::query()->whereIn('category_id', $user->categories->pluck('id'))->get();
    }

    public function createNote($data): Note
    {
        $note = new Note($data);
        $note->category_id = $data['category_id'];
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
        $note->fill($data);
        $note->save();

        return $note;
    }
}
