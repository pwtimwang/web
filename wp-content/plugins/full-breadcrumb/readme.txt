=== Full Breadcrumb ===
Contributors: Pedro Elsner
Requires at least: 2.8
Tested up to: 3.4.1
Stable tag: trunk
Tags: breadcrumb, breadcrumb for custom posts, breadcrumb support taxonomy, taxonomy hierarquical

Show breadcrumb for taxonomies, custom posts and all another pages.

== Description ==

* Support Hierarquical Taxonomies

Show breadcrumb in pages, posts, custom posts, categories, taxonomies, tags, authors, attachments and archives.

= Basic Usage =

Put this code `<?php if (function_exists('show_full_breadcrumb')) show_full_breadcrumb(); ?>` in your theme and enjoy!

Or, for to get the breadcrumb: `<?php if (function_exists('get_full_breadcrumb')) $var = get_full_breadcrumb(); ?>`

= Basic Customization =

`<?php
show_full_breadcrumb(
    array(
        'separator' => array(
            'content' => '&raquo;'
        ),
        'home' => array(
            'showLink' => false
        )
    )
);
?>`

= Advanced Customization =

`<?php
if (function_exists('show_full_breadcrumb')) show_full_breadcrumb(
    array(
        'labels' => array(
            'local'  => __('You are here:'), // set FALSE to hide
            'home'   => __('Home'),
            'page'   => __('Page'),
            'tag'    => __('Tag'),
            'search' => __('Searching for'),
            'author' => __('Published by'),
            '404'    => __('Error 404 &rsaquo; Page not found')
        ),
        'separator' => array(
            'element' => 'span',
            'class'   => 'separator',
            'content' => '&rsaquo;'
        ),
        'local' => array(
            'element' => 'span',
            'class'   => 'local'
        ),
        'home' => array(
            'showLink'       => false,
            'showBreadcrumb' => true
        )
    )
);
?>`

= Settings for Portuguese-BR =

`<?php
if (function_exists('show_full_breadcrumb')) show_full_breadcrumb(
    array(
        'labels' => array(
            'local'  => __('Voc&ecirc; est&aacute; aqui:'), // set FALSE to hide
            'home'   => __('In&iacute;cio'),
            'page'   => __('P&aacute;gina'),
            'tag'    => __('Etiqueta'),
            'search' => __('Buscando'),
            'author' => __('Publicado por'),
            '404'    => __('Error 404 &rsaquo; P&aacute;gina n&atilde;o encontrada')
        ),
        'separator' => array(
            'element' => 'span',
            'class'   => 'separator',
            'content' => '&rsaquo;'
        ),
        'home' => array(
            'showLink' => true
        )
    )
);
?>`

== Installation ==

1. Go to your admin area and select Plugins -> Add new from the menu.
2. Search for "Full Breadcrumb".
3. Click install.
4. Click activate.
5. Put this code `<?php if (function_exists('show_full_breadcrumb')) show_full_breadcrumb(); ?>` in your theme and enjoy!

See the [description tab](http://wordpress.org/extend/plugins/full-breadcrumb/screenshots/) to know how customize. the breadcrumb,

== Screenshots ==

1. The Full Breadcrumb in my website =)

== Changelog ==

= 1.0 =
* First revision

== Upgrade Notice ==