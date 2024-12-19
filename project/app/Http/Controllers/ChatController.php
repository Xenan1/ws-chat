<?php

namespace App\Http\Controllers;

use App\DTO\DialogDTO;
use App\Http\Requests\CreateMessageRequest;
use App\Http\Requests\GetDialogRequest;
use App\Http\Resources\DialogResource;
use App\Jobs\SendMessageByWebSocket;
use App\Models\User;
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

    public function getDialog(GetDialogRequest $request): DialogResource
    {
        $user = auth()->user();
        $chatPartner = User::query()->find($request->getChatPartnerId());
        $messages = $this->chatService->getDialogMessages($user->id, $chatPartner->id);

        return new DialogResource(
            new DialogDTO($user, $chatPartner, $messages, $chatPartner->avatar)
        );
    }
}
