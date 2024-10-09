<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <?= !empty($assets_header) ? $assets_header : ''; ?>
</head>
<body>
    <?= $header; ?>

    
    <?php if (isset($view)) require $view; ?>
    
        
    <?= $footer; ?>
    <?= !empty($assets_footer) ? $assets_footer : ''; ?>
</body>
</html>
