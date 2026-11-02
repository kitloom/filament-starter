<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Kitloom\Permissions\Filament\Users\RoleColumn;
use Kitloom\WpSchema\Filament\Tables\IndexColumns;
use Kitloom\WpSchema\Filament\Tables\IndexRecordActions;
use Kitloom\WpSchema\Filament\Users\DeleteUserAction;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // WordPress's columns: they are what is searched and sorted.
                TextColumn::make('ID')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('user_login')
                    ->label('Username')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('display_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user_email')
                    ->label('Email address')
                    ->searchable()
                    ->sortable(),
                ...app(IndexColumns::class)->for(RoleColumn::USERS),
                TextColumn::make('user_registered')
                    ->label('Registered')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            // Delete asks what happens to the user's content, as WordPress does.
            ->recordActions([
                EditAction::make(),
                ...app(IndexRecordActions::class)->for(RoleColumn::USERS),
                DeleteUserAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteUserAction::bulk(),
                ]),
            ]);
    }
}
