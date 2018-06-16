<?php
$_paginator = $_paginate['result'];
?>
<?php if ($_paginator->hasPages()) : ?>
    <ul class="pagination" role="navigation">
<!--         Previous Page Link -->
        <?php if ($_paginator->onFirstPage()) : ?>
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link"><?="&laquo; Previous"?></span>
            </li>
<?php else: ?>
            <li class="page-item">
                <a class="page-link" href="<?= $_paginator->previousPageUrl() ?>" rel="prev"><?="&laquo; Previous"?></a>
            </li>
<?php endif ?>

<!--        Next Page Link -->
        <?php if ($_paginator->hasMorePages()) : ?>
            <li class="page-item">
                <a class="page-link" href="<?= $_paginator->nextPageUrl() ?>" rel="next"><?="Next &raquo;"?></a>
            </li>
<?php else: ?>
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link"><?="Next &raquo;"?></span>
            </li>
 <?php endif ?>
    </ul>
<?php endif ; unset($_paginator)?>

