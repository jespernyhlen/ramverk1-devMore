<?php
/**
 * Supply the basis for the navbar as an array.
 */
return [
    // Use for styling the menu
    "id" => "rm-menu",
    "wrapper" => null,
    "class" => "rm-default rm-mobile",
 
    // Here comes the menu items
    "items" => [
        [
            "text" => "Home",
            "url" => "",
            "title" => "Homepage.",
        ],
        [
            "text" => "About",
            "url" => "about",
            "title" => "About devMore",
        ],
        [
            "text" => "Users",
            "url" => "user",
            "title" => "All users",
        ],
        [
            "text" => "Questions",
            "url" => "question",
            "title" => "All questions",
        ],
        [
            "text" => "Tags",
            "url" => "tag/search",
            "title" => "Search tags",
        ],
    ],
];
