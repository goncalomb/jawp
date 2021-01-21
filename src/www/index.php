<?php

require '../vendor/autoload.php';

define('PAGE_FILE', implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'data', 'page.md']));

$parser = new Mni\FrontYAML\Parser();

$config = [];
$content = '';

if (is_file(PAGE_FILE)) {
    $document = $parser->parse(file_get_contents(PAGE_FILE));
    $config = $document->getYAML();
    $content = $document->getContent();
}

$title = 'Just A Web Page';

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo htmlspecialchars($title); ?></title>
<style>
body {
    width: 750px;
    margin: 0 auto;
}
</style>
</head>
<body>
<?php echo $content; ?>
</body>
</html>
