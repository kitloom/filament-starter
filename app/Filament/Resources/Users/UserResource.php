<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?int $navigationSort = 1;

    /**
     * Users, like the WordPress admin menu: All Users, Add New User, Profile,
     * then Roles.
     */
    public static function getNavigationGroup(): ?string
    {
        return __('Users');
    }

    public static function getNavigationLabel(): string
    {
        return __('All Users');
    }

    /**
     * "Add New User" right under All Users, as WordPress has it: the panel
     * lists it with its own items.
     */
    public static function createNavigationItem(): NavigationItem
    {
        return NavigationItem::make('add-new-user')
            ->label(fn (): string => __('Add New User'))
            ->group(fn (): string => __('Users'))
            ->icon(Heroicon::OutlinedUserPlus)
            ->sort(2)
            ->url(fn (): string => static::getUrl('create'))
            ->isActiveWhen(fn (): bool => request()->routeIs(static::getRouteBaseName().'.create'))
            ->visible(fn (): bool => static::canCreate());
    }

    /**
     * All Users is highlighted on the list and on a user; the create page
     * highlights its own item.
     *
     * @return list<string>
     */
    public static function getNavigationItemActiveRoutePattern(): array
    {
        return [
            static::getRouteBaseName().'.index',
            static::getRouteBaseName().'.edit',
        ];
    }

    /**
     * Users as in WordPress: the list for whoever may list users, a user for
     * whoever may edit them — anyone may edit themselves.
     */
    public static function canViewAny(): bool
    {
        return Gate::allows('list_users');
    }

    public static function canCreate(): bool
    {
        return Gate::allows('create_users');
    }

    public static function canEdit(Model $record): bool
    {
        return Gate::allows('edit_user', $record);
    }

    public static function canDelete(Model $record): bool
    {
        return Gate::allows('delete_user', $record) && ! $record->is(auth()->user());
    }

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
