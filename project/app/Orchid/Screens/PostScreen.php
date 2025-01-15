<?php

namespace App\Orchid\Screens;

use App\Models\Post;
use App\Parsing\AbstractParser;
use App\Services\ConfigService;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
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
            $this->getParseButton(),

            Button::make('Parse random post')
                ->method('parseRandomPost'),

            Button::make('Create post')
                ->modal('postModal'),
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
        ];
    }

    public function approve(Post $post): void
    {
        $post->approve();
    }

    public function getParseButton(): Button
    {
        $config = app(ConfigService::class);

        return $config->isParsingEnabled()
            ? $this->getDisableParsingButton()
            : $this->getEnableParsingButton();
    }

    protected function getDisableParsingButton(): Button
    {
        return Button::make('Disable parsing')
            ->action(route('platform.post', ['method' => 'disableParsing']));
    }

    public function parseRandomPost(): void
    {
        app(AbstractParser::class)->createPost();
    }

    protected function getEnableParsingButton(): Button
    {
        return Button::make('Enable parsing')
            ->action(route('platform.post', ['method' => 'enableParsing']));
    }

    protected function disableParsing(): void
    {
        app(ConfigService::class)->disableParsing();
    }

    protected function enableParsing(): void
    {
        app(ConfigService::class)->enableParsing();
    }
}
