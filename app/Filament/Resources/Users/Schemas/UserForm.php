<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Schemas;

use App\Filament\Resources\Users\UserResource;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Kitloom\WpSchema\Filament\Forms\FormSections;
use Kitloom\WpSchema\Filament\Users\UserProfileFields;

/**
 * A user, laid out like the WordPress user screen: name, contact info,
 * biography, password. Below go module blocks — the Role, "Custom Fields"
 * from kitloom/custom-fields-filament.
 */
class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('Name'))->columns(2)->schema([UserProfileFields::username(), ...UserProfileFields::name()]),
                Section::make(__('Contact Info'))->columns(2)->schema([self::email(), ...UserProfileFields::contact()]),
                Section::make(__('About the user'))->schema(UserProfileFields::about()),
                Section::make(__('Account Management'))->schema([self::password()]),
                ...app(FormSections::class)->sectionsFor(UserResource::class),
            ]);
    }

    private static function email(): TextInput
    {
        return TextInput::make('email')
            ->label('Email address')
            ->email()
            ->required()
            ->maxLength(100)
            // WordPress's column: uniqueness is checked there.
            ->unique(column: 'user_email', ignoreRecord: true);
    }

    /**
     * Empty on edit — keep the current one.
     */
    private static function password(): TextInput
    {
        return TextInput::make('password')
            ->password()
            ->revealable()
            ->required(fn ($livewire): bool => $livewire instanceof CreateRecord)
            ->dehydrated(fn (?string $state): bool => filled($state))
            ->minLength(8);
    }
}
