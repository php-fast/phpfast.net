<ul class="inline-flex -space-x-px">
    <?php
    // Tạo query string cơ bản từ các tham số khác (ngoài page)
    $query_string = http_build_query($query_params);
    ?>

    <?php if ($current_page > 1): ?>
        <li><a href="<?= $base_url . '?' . $custom_names['page'] . '=' . ($current_page - 1) . '&' . $query_string; ?>" class="py-2 px-3 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700">Previous</a></li>
    <?php else: ?>
        <li><span class="py-2 px-3 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg cursor-not-allowed">Previous</span></li>
    <?php endif; ?>

    <?php for ($i = $start; $i <= $end; $i++): ?>
        <?php if ($i == $current_page): ?>
            <li><span class="py-2 px-3 leading-tight text-blue-600 bg-blue-50 border border-gray-300"><?= $i; ?></span></li>
        <?php else: ?>
            <li><a href="<?= $base_url . '?' . $custom_names['page'] . '=' . $i . '&' . $query_string; ?>" class="py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700"><?= $i; ?></a></li>
        <?php endif; ?>
    <?php endfor; ?>

    <?php if ($current_page < $total_pages): ?>
        <li><a href="<?= $base_url . '?' . $custom_names['page'] . '=' . ($current_page + 1) . '&' . $query_string; ?>" class="py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700">Next</a></li>
    <?php else: ?>
        <li><span class="py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg cursor-not-allowed">Next</span></li>
    <?php endif; ?>
</ul>