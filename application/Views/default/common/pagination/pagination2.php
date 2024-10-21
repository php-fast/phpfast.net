<ul class="inline-flex -space-x-px">
    <!-- Nút Previous -->
    <?php if ($current_page > 1): ?>
        <li>
            <a href="<?= $current_page == 2 ? $base_url . (!empty($query_params) ? '?' . $query_params : '') : $prev_page_url; ?>" 
               class="py-2 px-3 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700">
               Previous
            </a>
        </li>
    <?php else: ?>
        <li><span class="py-2 px-3 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg cursor-not-allowed">Previous</span></li>
    <?php endif; ?>

    <!-- Nút Next -->
    <?php if ($is_next): ?>
        <li>
            <a href="<?= $next_page_url; ?>" 
               class="py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 border-l-0 rounded-r-lg hover:bg-gray-100 hover:text-gray-700">
               Next
            </a>
        </li>
    <?php else: ?>
        <li><span class="py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 border-l-0 rounded-r-lg cursor-not-allowed">Next</span></li>
    <?php endif; ?>
</ul>
