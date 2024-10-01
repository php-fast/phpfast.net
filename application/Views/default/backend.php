<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
    <?= !empty($assets_header) ? $assets_header : ''; ?>
</head>
<body>
    <?= $header; ?>

    <h1><?php echo $title; ?></h1>
    
    <main class="container mx-auto my-8">
        <?php if (isset($view)) require $view; ?>
    </main>
    <?= $footer; ?>
    <?= !empty($assets_footer) ? $assets_footer : ''; ?>
</body>
</html>
