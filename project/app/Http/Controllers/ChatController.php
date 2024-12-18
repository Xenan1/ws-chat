<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMessageRequest;
use App\Jobs\SendMessageByWebSocket;
use App\Services\ChatService;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    public function __construct(protected ChatService $chatService) {}

    public function createMessage(CreateMessageRequest $request): JsonResponse
    {
        $message = $this->chatService->createMessage($request->getMessageData());
        dispatch(new SendMessageByWebSocket($message));
        return response()->json(['message' => 'success'], 201);
    }
}
