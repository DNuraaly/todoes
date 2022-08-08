<?php

namespace App\Http\ Controllers\Api\V1;

use App\Models\Note;
use App\Http\Controllers\Controller;
use App\Services\NoteServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\JsonResponse;


class NoteController extends Controller
{
    /**
     * Display a listing of the resource..
     * @param Request $request
     * @param NoteServices $note
     * @return JsonResponse
     */
    public function index(Request $request, NoteServices $note): JsonResponse
    {
        return response()->json($note->getAllNotes($request));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param NoteServices $note
     * @return JsonResponse
     */
    public function store(Request $request, NoteServices $note): JsonResponse
    {
        $user = $request->User();
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

        $note = new Note($validator->validate());
        $note->category_id = $request->category_id;
        $note->user_id = $user->id;
        $note->save();

        return response()->json($note);
    }

    /**
     * Display the specified resource.
     * @param Request $request
     * @param NoteServices $note
     * @param $id
     * @return JsonResponse
     */
    public function show(Request $request, NoteServices $note,  $id): JsonResponse
    {
        return response()->json($note->getNote($request, $id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param NoteServices $note
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, NoteServices $note, $id): JsonResponse
    {
        return response()->json($note->updateNote($request, $id));
    }

    /**
     * @param Request $request
     * @param NoteServices $note
     * @param $id
     * @return JsonResponse
     */
    public function destroy(Request $request, NoteServices $note, $id): JsonResponse
    {
        return response()->json($note->deleteNote($request, $id));
    }
}
