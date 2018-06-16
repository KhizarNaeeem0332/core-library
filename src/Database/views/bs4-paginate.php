<?php

$_paginator = $_paginate['result'];
$_elements = $_paginate['elements'];
if ($_paginator->hasPages()) : ?>
<ul class="pagination" role="navigation">
<!--    Previous Page Link -->
    <?php if ($_paginator->onFirstPage()) : ?>
    <li class="page-item disabled" aria-disabled="true" aria-label="">
        <span class="page-link" aria-hidden="true">&lsaquo;</span>
    </li>
    <?php else: ?>
    <li class="page-item">
        <a class="page-link" href="<?= $_paginator->previousPageUrl() ?>" rel="prev" aria-label="">&lsaquo;</a>
    </li>
    <?php endif; ?>

<!--     Pagination Elements-->
    <?php foreach ($_elements as $element):?>
<!--     "Three Dots" Separator -->
    <?php if (is_string($element)): ?>
        <li class="page-item disabled" aria-disabled="true"><span class="page-link"><?= $element ?></span></li>
    <?php endif ; ?>

<!--    Array Of Links Links-->
    <?php if (is_array($element)) : ?>
        <?php foreach ($element as $page => $url) : ?>
            <?php if ($page == $_paginator->currentPage()) : ?>
                <li class="page-item active" aria-current="page"><span class="page-link"><?= $page ?></span></li>
            <?php else : ?>
                <li class="page-item"><a class="page-link" href="<?= $url ?>"><?= $page ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php endforeach; ?>

<!--     Next Page Link -->
    <?php if ($_paginator->hasMorePages()): ?>
    <li class="page-item">
        <a class="page-link" href="<?= $_paginator->nextPageUrl() ?>" rel="next" aria-label="">&rsaquo;</a>
    </li>
    <?php else: ?>
    <li class="page-item disabled" aria-disabled="true" aria-label="">
        <span class="page-link" aria-hidden="true">&rsaquo;</span>
    </li>
    <?php endif; ?>
</ul>
<?php endif; unset($_paginator) ; unset($_elements);?>
