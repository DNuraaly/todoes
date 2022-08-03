<?php

namespace App\Http\ Controllers\Api\V1;

use App\Models\Note;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;


class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request;
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): JsonResponse
    {
        if ($category_id = $request->input('category_id')) {
            $notes = Note::query()->where('category_id', $category_id)
                ->get();
            return response()->json($notes);
        } else
            return response()->json(Note::all());

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreNoteRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $rules = [
            'title' => ['required', 'string', 'max:30'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'numeric', 'exists:categories,id'],
            'due_date' => ['nullable', 'date_format:Y-m-d H:i:s']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return response()->json(['messeges' => $validator->errors()]);
        else {
            $note = new Note($validator->validate());
            $note->category_id = $request->category_id;
            $note->save();
            return response()->json($note);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Note $note
     * @return \Illuminate\Http\Response
     */
    public function show($id): JsonResponse
    {
        $note = Note::find($id);
        if ($note)
            return response()->json($note);
        else
            return response()->json(['message' => 'note not found']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\ $request
     * @param \App\Models\Note $note
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): JsonResponse
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json(['message' => 'note not found 404']);
        }

        $rules = [
            'title' => ['required', 'string', 'max:30'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'numeric', 'exists:categories,id'],
            'due_date' => ['nullable', 'date_format:Y-m-d H:i:s']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return response()->json(['messages' => $validator->errors()]);
        else {
            $validated = $validator->validate();
            $note = Note::find($id);
            $note->category_id = $request->category_id;
            $note->fill($validated);
            $note->save();
            return response()->json($note);
        }


        //
    }

    public function destroy($id): JsonResponse
    {
        $note = Note::find($id);
        if ($note) {
            $note->delete();
            return response()->json(['message' => 'success']);
        }
        return response()->json(['message' => 'object not found ']);
    }
}
