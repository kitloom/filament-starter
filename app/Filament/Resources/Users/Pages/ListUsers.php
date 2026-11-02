<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Kitloom\Permissions\Filament\Users\RoleTabs;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    /**
     * «All | Administrator | Editor …», as above the WordPress user list.
     */
    public function getTabs(): array
    {
        return RoleTabs::make();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
