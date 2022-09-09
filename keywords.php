<?php include "base.php"; ?>
<div class="space-y-4 p-4 mt-8">
    <div class="p-6 mx-auto max-w-sm bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
        <h1 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">
            <?php if (isset($_GET['message'])) : ?>
                <span class="text-red-500"><?= $_GET['message'] ?>
                </span>
            <?php endif; ?>
        </h1>
        <form action="" method="post">
            <div class="mb-6">
                <label for="base-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Add one Keyword at a time</label>
                <input type="text" name="keyword" id="base-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
            </div>
            <button name="submit" type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">Add Keyword</button>
        </form>
    </div>

    <div class="p-6  bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
        <h1 class="mb-2 text-2xl font-bold tracking-tight flex space-x-2">
            <?php foreach ($s_keyWords as $keyWord) : ?>
                <?php $keyWord = json_decode($keyWord); ?>
                <span style="background-color:<?= $keyWord->color; ?>;" class="rounded-md p-1 font-semibold"><?= $keyWord->word; ?></span>
            <?php endforeach; ?>
        </h1>
    </div>
</div>

<?php

function getRandColor() {
    $rgbColor = [];
    foreach (['r', 'g', 'b'] as $color) {
        //Generate a random number between 0 and 255.
        $rgbColor[$color] = mt_rand(0, 255);
    }
    $colorCode = implode(",", $rgbColor);
    return "rgb($colorCode)";
}

if (isset($_POST['submit'])) {
    if (empty($_POST['keyword'])) {
        $message = urlencode("No keyword was provided!!");
        header("Location:keywords.php?message=$message");
    }
    $keyword = htmlspecialchars(trim($_POST['keyword']));

    //store the keywords
    $keywords = json_encode([
        'word' => $keyword,
        'color' => getRandColor()
    ]) . PHP_EOL;

    foreach ($s_keyWords as $s_keyWord) {
        if ($keywords === $s_keyWord) {
            $message = urlencode("Keyword is already present!");
            header("Location:keywords.php?message=$message");
        }
    }
    if (file_put_contents("keywords.txt", $keywords, FILE_APPEND | LOCK_EX)) {
        $message = urlencode("New was added!");
        header("Location:keywords.php?message=$message");
    }
}