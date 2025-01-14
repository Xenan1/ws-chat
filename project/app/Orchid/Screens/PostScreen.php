<?php

namespace App\Orchid\Screens;

use App\Models\Post;
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
        return [];
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
        ];
    }

    public function approve(Post $post): void
    {
        $post->approve();
    }
}
