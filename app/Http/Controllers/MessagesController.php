<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Validators\MessageValidators;

class MessagesController extends Controller
{
    public function index($property_id)
    {
        // Retrieve all messages and their replies
        $messages = Message::with('replies')->where('property_id', $property_id)->whereNull('parent_id')->paginate(10);

        return view('messages.index', compact('messages'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'contact' => 'required',
            'content' => 'required',
        ]);

        // Create a new message
        $message = Message::create($validatedData);

        return redirect()->route('messages.index')->with('success', 'Message saved successfully.');
    }

    public function reply(Request $request, $parentId)
    {
        $validator = MessageValidators::validate('validateReply', $request->all());

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return redirect()->route('property.create')->withErrors($errors)->withInput();
        }
        
        // Find the parent message
        $parentMessage = Message::findOrFail($parentId);

        // Create a new reply
        $reply = new Message([
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'content' => $request->content,
        ]);

        // Associate the reply with the parent message
        $parentMessage->replies()->save($reply);

        return redirect()->route('messages.index')->with('success', 'Reply saved successfully.');
    }
}
