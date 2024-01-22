<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Validators\MessageValidators;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{

    /**
     * The index function retrieves all messages and their replies for a specific property and returns
     * them to the admin message index view.
     * 
     * @param property_id The parameter `` is used to filter the messages based on a
     * specific property. It is passed to the `index` function as an argument. The function retrieves
     * all messages and their replies where the `property_id` matches the provided value. The
     * `with('replies')` method eager
     * 
     * @return a view called 'admin.message.index' and passing the variable 'messages' to the view.
     */
    public function index($property_id)
    {
        // Retrieve all messages and their replies
        $messages = Message::with('replies')->where('property_id', $property_id)->whereNull('parent_id')->paginate(10);

        return view('admin.message.index', compact('messages'));
    }

    
    /**
     * The function sends a message with user input data and redirects to the property info page with
     * success or error messages.
     * 
     * @param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request. It contains information about the request such as the request
     * method, URL, headers, and request data.
     * 
     * @return a redirect response. If the validation fails, it redirects to the 'property.info' route
     * with the property_id as a parameter, along with the validation errors and the user's input. If
     * the validation passes, it creates a new Message record in the database and redirects to the
     * 'property.info' route with the property_id as a parameter, along with a success message.
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
     * The function "respond" retrieves a message by its ID and returns a view for responding to the
     * message.
     * 
     * @param id The parameter "id" is used to identify the specific message that the function is
     * responding to. It is used to retrieve the message from the database using the "findOrFail"
     * method of the Message model.
     * 
     * @return a view called 'admin.message.respond' and passing the variable 'message' to the view.
     */
    public function respond($id)
    {  
        $message = Message::findOrFail($id);

        return view('admin.message.respond', compact('message'));
    }

    
    /**
     * The function takes a request and a parent ID, validates the request data, finds the parent
     * message, creates a new reply, associates the reply with the parent message, and redirects to the
     * messages index page with a success message.
     * 
     * @param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request. It contains information about the request such as the request
     * method, headers, and input data.
     * @param parentId The parentId parameter is the ID of the parent message to which the reply is
     * being made.
     * 
     * @return a redirect response.
     */
    public function reply(Request $request, $parentId)
    {
        $validator = MessageValidators::validate('validateReply', $request->all());

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return redirect()->route('message.respond',['id'=>$parentId])->withErrors($errors)->withInput();
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
