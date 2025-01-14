<?php

namespace App\Orchid\Screens;

use App\Models\Post;
use App\Parsing\AbstractParser;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class PostScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'posts' => Post::query()->where('approved', '=', false)->orderBy('created_at')->get(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Post moderating';
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Parse random post')
                ->method('parseRandomPost'),

            Button::make('Create post')
                ->modal('postModal'),

            ModalToggle::make('Parse post by id')
                ->modal('parsePostById')
                ->icon('plus')
                ->method('parsePostById'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::modal('postModal', Layout::rows([
                Input::make('post.text')
                    ->title('Text')
            ]))
                ->title('Create post')
                ->applyButton('Add post'),

            Layout::table('posts', [
                TD::make('author'),
                TD::make('text'),
                TD::make('Actions')
                    ->alignRight()
                    ->render(function (Post $post) {
                        return Button::make('Approve')
                            ->confirm('Approved post will appear in feed')
                            ->method('approve', ['post' => $post->id]);
                    }),
            ]),
            Layout::modal('parsePostById', Layout::rows([
                Input::make('orderId')
                    ->title('ID поста')
                    ->required(),
                ]),
            )->title('Парсинг поста')
                ->applyButton('Создать')
                ->closeButton('Отмена')
        ];
    }

    public function approve(Post $post): void
    {
        $post->approve();
    }

    public function parseRandomPost(): void
    {
        app(AbstractParser::class)->createPost();
    }

    public function parsePostById(Request $request): void
    {
        $postId = $request->input('orderId') ?? null;

        if ($postId) {
            app(AbstractParser::class)->createPostById($postId);
        }
    }
}
