<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\EditRecord;
use Kitloom\Permissions\Filament\Users\RoleColumn;
use Kitloom\WpSchema\Filament\Tables\IndexRecordActions;
use Kitloom\WpSchema\Filament\Users\DeleteUserAction;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    /**
     * Delete asks what happens to the user's content, as WordPress does;
     * modules add theirs ("Sign in as") through the tag.
     */
    protected function getHeaderActions(): array
    {
        return [
            ...app(IndexRecordActions::class)->for(RoleColumn::USERS),
            DeleteUserAction::make(),
        ];
    }
}
