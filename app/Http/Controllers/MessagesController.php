<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Validators\MessageValidators;
use Illuminate\Support\Facades\Auth;


class MessagesController extends Controller
{
    public function index($property_id)
    {
        // Retrieve all messages and their replies
        $messages = Message::with('replies')->where('property_id', $property_id)->whereNull('parent_id')->paginate(10);
//dd($messages);
        return view('admin.message.index', compact('messages'));
    }

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

        return redirect()->route('property.info', ['id' => $property_id])->with('success', 'Message sended successfully.');
    }

    public function respond($id)
    { //dd($id);
        $message = Message::findOrFail($id);
        
        return view('admin.message.respond', compact('message'));
    }

    public function reply(Request $request, $parentId)
    {
       // print_r($request->all());die();
        $validator = MessageValidators::validate('validateReply', $request->all());

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return redirect()->route('admin_panel.property.create')->withErrors($errors)->withInput();
        }
        
        // Find the parent message
        $parentMessage = Message::findOrFail($parentId);

        $property_id = $request->property_id;
        // Create a new reply
        $reply = new Message([
            'user_id' => 1,
            'property_id' => $property_id,
            'content' => $request->content,
        ]);

        // Associate the reply with the parent message
        $parentMessage->replies()->save($reply);

        return redirect()->route('admin_panel.messages.index', ['property_id' => $property_id])->with('success', 'Reply saved successfully.');
    }
}
