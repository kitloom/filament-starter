<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kitloom\Permissions\AdminAccess;
use Kitloom\Permissions\Concerns\HasRoles;
use Kitloom\WpSchema\Contracts\HasCustomFields;
use Kitloom\WpSchema\Contracts\HasMetaTable;
use Kitloom\WpSchema\Models\Concerns\AuthenticatesAsWordPressUser;
use Kitloom\WpSchema\Models\Concerns\IsWordPressUser;

/**
 * A user is a row of WordPress's `users` table: WordPress and the admin share
 * the accounts, their passwords and their meta.
 */
class User extends Authenticatable implements FilamentUser, HasCustomFields, HasMetaTable
{
    /** @use HasFactory<UserFactory> */
    use AuthenticatesAsWordPressUser, HasFactory, HasRoles, IsWordPressUser, Notifiable;

    /**
     * The panel is open to whoever has something to do in it — a permission
     * beyond reading the site ({@see AdminAccess}). Customers and subscribers
     * stay out, as WooCommerce keeps them out of wp-admin, and use their
     * account on the site.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return app(AdminAccess::class)->allows($this);
    }
}
