<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\TermTaxonomy;
use App\Models\User;

return [

    /*
    | The site's own model classes. The rest are kitloom/wp-schema models.
    */

    /*
    | Site sidebars — tabs on the widgets page and `widgets` keys in the payload.
    */

    'sidebars' => ['sidebar', 'footer'],

    'models' => [
        'post' => Post::class,
        'term_taxonomy' => TermTaxonomy::class,
        // The same `users` as the package model's: post authors and ACF
        // "user" field rules recognise the admin user.
        'user' => User::class,
    ],

];
