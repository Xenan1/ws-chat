<?php

namespace App\Http\Controllers;

use App\Cache\CacheKeyStorage;
use App\Cache\CacheService;
use App\DTO\ChatDTO;
use App\Http\Requests\CreateChatRequest;
use App\Http\Requests\CreateMessageRequest;
use App\Http\Requests\GetDialogRequest;
use App\Http\Resources\ChatPreviewResource;
use App\Http\Resources\ChatResource;
use App\Http\Resources\ChatsResource;
use App\Http\Resources\DialogResource;
use App\Http\Responses\CommonResponse;
use App\Services\ChatService;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ChatController extends Controller
{
    public function __construct(protected ChatService $chatService) {}

    public function createMessage(CreateMessageRequest $request): CommonResponse
    {
        $message = $this->chatService->createMessage($request->getMessageData());

        if ($request->getImage()) {
            $this->chatService->setMessageAttachment($message, $request->getImage());
        }

        $this->chatService->send($message);

        return new CommonResponse(true, 201);
    }

    public function getChatMessages(GetDialogRequest $request, CacheService $cache): DialogResource
    {
        $user = auth()->user();
        $chat = $this->chatService->getDialogWithUsers($user->getId(), $request->getChatPartnerId());
        $messages = $this->chatService->getChatMessages($chat->getId());
        $dialog = new ChatDTO($user, $chat, $messages);

        return $cache->remember(
            CacheKeyStorage::chat($chat->getId()),
            function () use ($dialog) {
                return new DialogResource($dialog);
            }
        );
    }

    public function getChats(): ChatsResource
    {
        return new ChatsResource(
            $this->chatService->getUserChats(auth()->user())
        );
    }

    public function createChat(CreateChatRequest $request): CommonResponse
    {
        $this->chatService->createChat($request->getName(), $request->getMembersIds());

        return new CommonResponse(true, 201);
    }

    public function getChat(int $id): ChatResource
    {
        $chat = $this->chatService->getChatById($id);
        $response = Gate::inspect('get', $chat);

        if ($response->allowed()) {
            $messages = $this->chatService->getChatMessages($chat->getId());
            $chatDTO = new ChatDTO(auth()->user(), $chat, $messages);
            return new ChatResource($chatDTO);
        } else {
            throw new NotFoundHttpException('Chat does not exist');
        }
    }
}
