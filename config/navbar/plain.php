<?php
/**
 * Supply the basis for the navbar as an array.
 */
return [
    // Use for styling the menu
    "class" => "my-navbar",
 
    // Here comes the menu items/structure
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