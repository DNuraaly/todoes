<?php

namespace App\Http\ Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Services\NotesService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class NoteController extends Controller
{
    private const MESSAGES = [
        'notFound' => 'Note not found.',
        'deleted'  => 'Successfully, note deleted.'
    ];
    /**
     * Display a listing of the resource..
     * @param Request $request
     * @param NotesService $notesService
     * @return JsonResponse
     */
    public function index(Request $request, NotesService $notesService): JsonResponse
    {
        $user = $request->user();
        return response()->json($notesService->getAllNotes($user));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreNoteRequest $request
     * @param NotesService $notesService
     * @return JsonResponse
     */
    public function store(StoreNoteRequest $request, NotesService $notesService): JsonResponse
    {
        $user = $request->user();
        $validator = $request->getValidator();

        if ($validator->fails())
        {
            return response()->json(['messages' => $validator->errors()],422);
        }

        $newNote = $notesService->createNote($validator->validated(), $user);

        return response()->json($newNote, 201);
    }

    /**
     * Display the specified resource.
     * @param Request $request
     * @param NotesService $notesService
     * @param $id
     * @return JsonResponse
     */
    public function show(Request $request, NotesService $notesService, $id): JsonResponse
    {
        $user = $request->user();
        $note = $notesService->getNote($id, $user);

        if (!$note)
        {
            return response()->json(['message' => self::MESSAGES['notFound']],404);
        }

        return response()->json($note, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateNoteRequest $request
     * @param NotesService $notesService
     * @param $id
     * @return JsonResponse
     */
    public function update(UpdateNoteRequest $request, NotesService $notesService, $id): JsonResponse
    {
        $user = $request->user();
        $note = $notesService->getNote($id, $user);

        if (!$note)
        {
            return response()->json(['message' => self::MESSAGES['notFound']],404);
        }

        $validator = $request->getValidator();

        if($validator->fails())
        {
            return response()->json(['messages' => $validator->errors()], 422);
        }

        $updated_note = $notesService->updateNote($note, $validator->validated());

        return response()->json($updated_note);
    }

    /**
     * @param Request $request
     * @param NotesService $notesService
     * @param $id
     * @return JsonResponse
     */
    public function destroy(Request $request, NotesService $notesService, $id): JsonResponse
    {
        $user = $request->user();
        $note = $notesService->getNote($id, $user);

        if (!$note)
        {
            return response()->json(['message' => self::MESSAGES['notFound']], 404);
        }

        $note->delete();

        return response()->json(['message' => self::MESSAGES['deleted']]);
    }
}
