<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\ChatRoom;
use App\Contact;
use App\Events\NewGroup;

class ChatRoomController extends Controller
{
    public function creatGroup(Request $request)
    {
        $newGroupMember = $request->newgroup;
        $newChatRoom = new ChatRoom;
        $newChatRoom->userId = Auth::user()->id;
        $newChatRoom->invited = json_encode($newGroupMember);
        $newChatRoom->roomName = $request->groupName;
        $newChatRoom->save();

        $createdRoomId = $newChatRoom->id;
        
        //creat new contact for auth user
        $newContactRoom = new Contact;
        $newContactRoom->userId = Auth::user()->id;
        $newContactRoom->roomId = $createdRoomId;
        $newContactRoom->save();

        //creat new contact for invited user
        foreach ($newGroupMember as $key => $userId){
            $newContactRoom = new Contact;
            $newContactRoom->userId = $userId;
            $newContactRoom->roomId = $createdRoomId;
            $newContactRoom->save();

            // //broadcasting to invited users
            
            // $chatGroup = Contact::where([
            //     [ 'userId', '=', $userId],
            //     [ 'roomId', '=', $createdRoomId],
            // ])->with('roomId.user')->first();

            
        }

        $chatGroup = Contact::where([
            [ 'userId', '=', Auth::user()->id],
            [ 'roomId', '=', $createdRoomId],
        ])->with('roomId.user')->first();

        //broadcast Event
        broadcast(new NewGroup($chatGroup))->toOthers();

        return response()->json([
            'newGroup' => $chatGroup
        ], 201);
    }
}
