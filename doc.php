<?php

if (empty($_GET['url'])) {
    header("Location:index.php?error=no%20url%20was%20provided");
}

require 'vendor/autoload.php';


$web = new \spekulatius\phpscraper();

// Navigate to the test page. It contains 6 lorem ipsum paragraphs

try {
    $web->go($_GET['url']);
} catch (\Exception $e) {
    echo  $e->getMessage();
    exit;
}


function highlightWords($text, $word) {
    $text = preg_replace('#' . preg_quote($word['word']) . ' #i ', '<span class="bg-' . $word['color'] . '-500">\\0</span>', $text);
    return "<p class='font-light text-gray-500 dark:text-gray-400'>$text</p>";
}

$keyWords = [
    [
        "word" => "crypto",
        "color" => "red"
    ],
    [
        "word" => "fintech",
        "color" => "green"
    ],
    [
        "word" => "mifid",
        "color" => "yellow"
    ],
    [
        "word" => "the",
        "color" => "purple"
    ],
    [
        "word" => "in",
        "color" => "blue"
    ]
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Docs</title>
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.3/dist/flowbite.min.css" />
</head>

<body class="p-8">
    <p class="mb-2"><?= "This page contains " . count($web->paragraphs) . " paragraphs.<br/><br/>"; ?></p>
    <?php foreach ($keyWords as $keyWord) : ?>
        <span class="bg-<?= $keyWord['color'] ?>-500"><?= $keyWord['word'] ?></span>
    <?php endforeach; ?>
    <div class="border m-4 p-2 rounded-md">
        <?php foreach ($web->paragraphs as $paragraph) : ?>
            <?php foreach ($keyWords as $keyWord) : ?>
                <?php $paragraph =  highlightWords($paragraph, $keyWord); ?>
            <?php endforeach; ?>
            <?= $paragraph; ?>
        <?php endforeach; ?>
    </div>
</body>