<div class="pagination-class-name" data-pagination-class-name='<?php echo json_encode($data['Plugin']['pagination']) ?>'></div>
<nav class="pagination-container">
    <ul class="pagination justify-content-end mt-2">
        <li class="page-item">
            <a class="page-link first-page disabled" href="javascript:void(0)" tabindex="-1" aria-label="First" data-page="1">
                <i class="fas fa-angle-double-left"></i>
            </a>
        </li>
        <li class="page-item">
            <a class="page-link prev-page disabled" href="javascript:void(0)" tabindex="-1" aria-label="Previous">
                <i class="fas fa-angle-left"></i>
            </a>
        </li>
        <div class="d-flex list-page"></div>
        <li class="page-item">
            <a class="page-link next-page disabled" href="javascript:void(0)" tabindex="-1" aria-label="Next">
                <i class="fas fa-angle-right"></i>
            </a>
        </li>
        <li class="page-item">
            <a class="page-link last-page disabled" href="javascript:void(0)" tabindex="-1" aria-label="Last">
                <i class="fas fa-angle-double-right"></i>
            </a>
        </li>
        <!-- <ul> -->
        <!-- <li class="page-item">
            <a class="page-link first-page disabled" href="javascript:void(0)" tabindex="-1" aria-label="First" data-page="1">
                <i class="fas fa-angle-double-left"></i>
            </a>
        </li>
        <li class="page-item">
            <a class="page-link prev-page disabled" href="javascript:void(0)" tabindex="-1" aria-label="Previous">
                <i class="fas fa-angle-left"></i>
            </a>
        </li>
            <li class="active"><a href="index.php?action=list_movie&amp;page=1">1</a></li>
            <li><a class="page-link first-page disabled" href="index.php?action=list_movie&amp;page=2">2</a></li>
            <li><a class="page-link first-page disabled" href="index.php?action=list_movie&amp;page=3">3</a></li>
            <li><a class="page-link first-page disabled"  href="index.php?action=list_movie&amp;page=4">4</a></li>
            <li><a class="page-link first-page disabled" href="index.php?action=list_movie&amp;page=5">5</a></li>
            <li><a class="page-link first-page disabled" href="index.php?action=list_movie&amp;page=6">6</a></li>
            <li><a class="page-link first-page disabled" href="index.php?action=list_movie&amp;page=2">TIẾP</a></li> -->
        <!-- </ul> -->
        <?php
        // $moviesList = isset($_POST['same_category']) && $_POST['same_category'] ? $list_same_category : $list_movie;
        // $totalPages = ceil(count($moviesList) / $moviesPerPage);
        // if ($currentPage > 1) {
        //     echo '<li><a href="index.php?action=list_movie&page=' . ($currentPage - 1) . '">QUAY LẠI</a></li>';
        // }
        // for ($i = 1; $i <= $totalPages; $i++) {
        //     echo '<li' . ($i === $currentPage ? ' class="active"' : '') . '><a href="index.php?action=list_movie&page=' . $i . '">' . $i . '</a></li>';
        // }
        // if ($currentPage < $totalPages) {
        //     echo '<li><a href="index.php?action=list_movie&page=' . ($currentPage + 1) . '">TIẾP</a></li>';
        // }
        ?>
    </ul>
</nav>