<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Post;
use App\Models\User;

class ChartTotalPostByUserId extends ChartWidget
{
    protected static ?string $heading = 'Total Posts by User';

    protected static ?int $sort = 2;
    protected function getData(): array
    {
        $postsData = Post::selectRaw('user_id, count(*) as total')
            ->groupBy('user_id')
            ->pluck('total', 'user_id')
            ->toArray();

        $userNames = User::whereIn('id', array_keys($postsData))
            ->pluck('name', 'id')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Posts',
                    'data' => array_values($postsData),
                ],
            ],
            'labels' => array_values($userNames),
        ];
    }

    protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'bar';
    }
}
