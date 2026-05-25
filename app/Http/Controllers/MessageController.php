<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SpringBootApiService;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    protected $api;

    public function __construct(SpringBootApiService $api)
    {
        $this->api = $api;
    }

    public function index()
    {
        try {
            $user = $this->api->getCurrentUser();
            $conversations = $this->api->getConversations();

            // Format conversations for the view
            $formattedConversations = [];
            if ($conversations && is_array($conversations)) {
                foreach ($conversations as $conv) {
                    $formattedConversations[] = [
                        'id' => $conv['id'] ?? null,
                        'other_user' => [
                            'id' => $conv['otherUserId'] ?? $conv['other_user_id'] ?? null,
                            'name' => $conv['otherUserName'] ?? $conv['other_user_name'] ?? 'User',
                        ],
                        'last_message' => $conv['lastMessage'] ?? $conv['last_message'] ?? 'No messages yet',
                        'last_message_time' => isset($conv['lastMessageTime']) || isset($conv['last_message_time'])
                            ? \Carbon\Carbon::parse($conv['lastMessageTime'] ?? $conv['last_message_time'])->diffForHumans()
                            : 'Just now',
                        'property' => [
                            'id' => $conv['propertyId'] ?? $conv['property_id'] ?? null,
                            'title' => $conv['propertyTitle'] ?? $conv['property_title'] ?? 'Property',
                        ]
                    ];
                }
            }

            return view('customer.inbox', [
                'conversations' => $formattedConversations,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Messages index error: ' . $e->getMessage());

            return view('customer.inbox', [
                'conversations' => [],
                'user' => session('user', ['name' => 'Guest'])
            ])->with('warning', 'Messages feature is temporarily unavailable. Please try again later.');
        }
    }

    public function conversation($userId)
    {
        try {
            $messages = $this->api->getConversationMessages($userId);
            $user = $this->api->getCurrentUser();

            return view('customer.conversation', [
                'messages' => $messages,
                'otherUserId' => $userId,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Conversation error: ' . $e->getMessage());
            return redirect()->route('customer.messages')->with('error', 'Could not load conversation');
        }
    }

    public function send(Request $request)
    {
        try {
            $validated = $request->validate([
                'to_user_id' => 'required',
                'message' => 'required|string|max:1000',
                'property_id' => 'nullable'
            ]);

            $result = $this->api->sendMessage($validated);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $result]);
            }

            return redirect()->back()->with('success', 'Message sent successfully');
        } catch (\Exception $e) {
            Log::error('Send message error: ' . $e->getMessage());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Failed to send message');
        }
    }

    public function getMessages($conversationId)
    {
        try {
            $messages = $this->api->getMessages($conversationId);
            $conversation = $this->api->getConversationDetails($conversationId);

            $currentUser = $this->api->getCurrentUser();
            $currentUserId = is_array($currentUser) ? ($currentUser['id'] ?? null) : (is_object($currentUser) ? $currentUser->id : 1);

            $formattedMessages = [];
            if ($messages && is_array($messages)) {
                foreach ($messages as $msg) {
                    $formattedMessages[] = [
                        'id' => $msg['id'] ?? null,
                        'message' => $msg['message'] ?? '',
                        'sender_id' => $msg['senderId'] ?? $msg['sender_id'] ?? $msg['from_user_id'] ?? null,
                        'sender_name' => $msg['senderName'] ?? $msg['sender_name'] ?? 'User',
                        'created_at' => $msg['createdAt'] ?? $msg['created_at'] ?? now(),
                    ];
                }
            }

            $otherUser = $conversation['otherUser'] ?? $conversation['other_user'] ?? ['name' => 'User'];
            $property = $conversation['property'] ?? ['title' => 'Property'];

            return response()->json([
                'messages' => $formattedMessages,
                'other_user' => $otherUser,
                'property' => $property,
                'current_user_id' => $currentUserId
            ]);
        } catch (\Exception $e) {
            Log::error('Get messages API error: ' . $e->getMessage());
            return response()->json([
                'messages' => [],
                'other_user' => ['name' => 'User'],
                'property' => ['title' => 'Property'],
                'error' => 'Could not load messages'
            ], 500);
        }
    }

    public function sendApi(Request $request)
    {
        try {
            $validated = $request->validate([
                'conversation_id' => 'required',
                'message' => 'required|string|max:1000'
            ]);

            $result = $this->api->sendMessageToConversation(
                $validated['conversation_id'],
                $validated['message']
            );

            return response()->json([
                'success' => true,
                'message' => $result
            ]);
        } catch (\Exception $e) {
            Log::error('Send API message error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to send message. Please try again.'
            ], 500);
        }
    }
}
