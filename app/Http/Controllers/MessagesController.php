<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Validators\MessageValidators;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    /**
     * Display a listing of messages for a specific property.
     *
     * @param  int  $property_id
     * @return \Illuminate\View\View
     */
    public function index($property_id)
    {
        // Retrieve all messages and their replies
        $messages = Message::with('replies')->where('property_id', $property_id)->whereNull('parent_id')->paginate(10);

        return view('admin.message.index', compact('messages'));
    }

    /**
     * Store a newly created message in the storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(Request $request)
    {
        $validator = MessageValidators::validate('validatesendMessage', $request->all());
        $property_id = $request->property_id;

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return redirect()->route('property.info', ['id' => $property_id])->withErrors($errors)->withInput();
        }

        Message::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'content' => $request->content,
            'property_id' => $property_id,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->route('property.info', ['id' => $property_id])->with('success', 'Message sent successfully.');
    }

    /**
     * Show the form for responding to a message.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function respond($id)
    {
        $message = Message::findOrFail($id);

        return view('admin.message.respond', compact('message'));
    }

    /**
     * Store a newly created reply in the storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $parentId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reply(Request $request, $parentId)
    {
        $validator = MessageValidators::validate('validateReply', $request->all());

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return redirect()->route('property.create')->withErrors($errors)->withInput();
        }

        // Find the parent message
        $parentMessage = Message::findOrFail($parentId);

        $property_id = $request->property_id;

        // Create a new reply
        $reply = new Message([
            'user_id' => Auth::user()->id,
            'property_id' => $property_id,
            'content' => $request->content,
        ]);

        // Associate the reply with the parent message
        $parentMessage->replies()->save($reply);

        return redirect()->route('messages.index', ['property_id' => $property_id])->with('success', 'Reply saved successfully.');
    }
}
