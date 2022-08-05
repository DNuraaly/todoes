<?php

namespace App\Http\ Controllers\Api\V1;

use App\Models\Note;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
        $user = $request->user();
//        $notes = Note::whereIn('category_id', $user->categories->pluck('id'))->get();

        if ($category_id = $request->input('category_id')) {
            if ($user->categories()->where('id', $category_id)) {
                $notes = $user->notes()->where('category_id', $category_id)->get();
            }
        } else
            $notes = $user->notes()->get();
//            dd($user->categories()->get()->toArray());
            $notes = Note::whereIn('category_id', $user->categories->pluck('id'))->get();

        if ($notes->isNotEmpty()) {
            return response()->json($notes);
        } else
            return response()->json(['message' => 'Your notes list is empty.']);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreNoteRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
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

//'exists:categories,id'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['messeges' => $validator->errors()]);
        }

        $note = new Note($validator->validate());
        $note->category_id = $request->category_id;
        $note->user_id = $user->id;
        $note->save();
        return response()->json($note);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Note $note
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id): JsonResponse
    {
        $note = Note::find($id);
        $user = $request->User();
//        $log = DB::enableQueryLog();
//        $user->categories->where('category_id', $note->category_id)->first();
        if (!$note || !$user->categories()->where('id', $note->category_id)->exists()) {
            return response()->json('Not found', 404);
        }
//        dd($log);
        return response()->json($note);
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
        $user = $request->User();
        $note = $user->notes()->where('id', $id)->first();
        if (!$note) {
            return response()->json(['message' => 'note not found.']);
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

        $validated = $validator->validate();
        $note->category_id = $request->category_id;
        $note->fill($validated);
        $note->save();
        return response()->json($note);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $user = $request->User();
        $note = $user->notes()->where('id', $id)->first();
        if (!$note) {
            return response()->json(['message' => 'note not found.']);
        }

        $note->delete();
        return response()->json(['message' => 'successfully, note deleted.']);
    }
}
