<?php


namespace App\Services;


use App\Models\Note;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class NoteServices
{
    public function getAllNotes($request)
    {
        $user = $request->user();

        if ($category_id = $request->input('category_id')) // for filter ? category_id
        {
            if ($user->categories->where('id', $category_id)->isEmpty()) {
                return ['message' => 'Notes not found.'];
            }

            return Note::query()->where('category_id', $category_id)->get();
        }

        return Note::query()->whereIn('category_id', $user->categories->pluck('id'))->get();
    }

    public function createNote($request)
    {
        $user = $request->user();
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => [
                'required',
                'numeric',
                Rule::exists('categories', 'id')
                    ->where('user_id', $user->id),
            ],
            'due_date' => ['nullable', 'date_format:Y-m-d H:i:s']
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['messages' => $validator->errors()]);
        }

        $note = new Note($validator->validated());
        $note->category_id = $request->category_id;
        $note->user()->associate($user);
        $note->save();

        return $note;
    }

    public function getNote($request,$id)
    {
        $user = $request->user();
        $note = Note::query()->find($id);

        if (!$note || !$user->categories()->where('id', $note->category_id)->exists())
        {
            return ['message' => 'Note not found'];
        }

        return $note;
    }

    public function updateNote($request, $id)
    {
        $user = $request->user();
        $note = Note::query()->find($id);

        if (!$note || !$user->categories()->where('id',$note->category_id)->exists())
        {
            return ['message' => 'Note not found'];
        }

        $rules = [
            'title' => ['required', 'string', 'max:30'],
            'description' => ['nullable', 'string'],
            'category_id' => [
                'required',
                'numeric',
                Rule::exists('categories', 'id')
                    ->where('user_id', $user->id)
            ],
            'due_date' => ['nullable', 'date_format:Y-m-d H:i:s']
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['messages' => $validator->errors()]);
        }

        $validated = $validator->validated();
        $note->category_id = $request->category_id;
        $note->fill($validated);
        $note->save();

        return $note;
    }

    public function deleteNote($request, $id)
    {
        $user = $request->user();
        $note = Note::query()->find($id);

        if (!$note || !$user->categories()->where('id',$note->category_id)->exists())
        {
            return ['message' => 'Note not found'];
        }

        $note->delete();

        return ['message' => 'successfully, note deleted.'];
    }
}
