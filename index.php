<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.3/dist/flowbite.min.css" />
</head>

<body class="h-screen p-8">
    <div class="space-y-4 p-4 ">
        <div class="p-6 max-w-sm bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
            <h1 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">
                <?php if (isset($_GET['error'])) : ?>
                    <span class="text-red-500"><?= $_GET['error'] ?>
                    </span>
                <?php endif; ?>
            </h1>

            <form action="" method="get">
                <div class="mb-6">
                    <label for="base-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Input your URL</label>
                    <input type="url" name="url" id="base-input" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                </div>
                <button name="submit" type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">Go</button>
            </form>
        </div>
    </div>


</body>

</html>
<?php
if (isset($_GET['submit'])) {
    header("Location:doc.php?url={$_GET['url']}");
}

?>