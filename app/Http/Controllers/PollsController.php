<?php

namespace App\Http\Controllers;

use App\Poll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PollsController extends Controller
{
    public function index()
    {
        $polls = Poll::paginate(10);
        return response()->json($polls, 200);
    }

    public function show($id)
    {
        $poll = Poll::with('questions')->findOrFail($id);
        $response['poll'] = $poll;
        $response['questions'] = $poll->questions;

        return response()->json($response, 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|max:255|bail',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $poll = Poll::create($request->all());

        return response()->json($poll, 201);
    }

    public function update(Request $request, Poll $poll)
    {
        $rules = [
            'title' => 'required|max:255|bail',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $poll->update($request->all());

        return response()->json($poll, 200);
    }

    public function delete(Poll $poll)
    {
        $poll->delete();
        return response()->json(null, 204);
    }

    public function errors()
    {
        return response()->json(['msg' => 'Payment is required.'], 501);
    }

    public function questions(Poll $poll)
    {
        $questions = $poll->questions;
        return response()->json($questions, 200);
    }
}
