<?php


namespace App\Services;


use App\Models\Category;
use App\Models\Note;


class NotesService
{
    public function getUserNotes($user): \Illuminate\Database\Eloquent\Collection
    {
        return Note::query()->whereIn('category_id', $user->categories->pluck('id'))->orderBy('id')->get();
//        return $user->notes()->orderBy('id', 'asc')->get();
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
        $categoriesTable = Category::TABLE;
        $notesTable = Note::TABLE;
        return Note::query()
            ->join(Category::TABLE, "$notesTable.category_id", '=', "$categoriesTable.id")
            ->where("$categoriesTable.user_id", $user->id)
            ->where("$notesTable.id", $id)
            ->first();
    }

    public function updateNote($note, $data): Note
    {
        $note->category_id = $data['category_id'];
        $note->fill($data);
        $note->save();

        return $note;
    }
}
