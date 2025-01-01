<?php
function renderPagination($page, $totalPages, $order)
{
    $pagination = '<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">';

    if ($page > 1) {
        $pagination .= '<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="getPresupuestos(1, \'' . $order . '\')">Primera</a></li>';
        $pagination .= '<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="getPresupuestos(' . ($page - 1) . ', \'' . $order . '\')">Anterior</a></li>';
    }

    $startPage = max(1, $page - 2);
    $endPage = min($totalPages, $page + 2);

    if ($startPage > 1) {
        $pagination .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
    }

    for ($i = $startPage; $i <= $endPage; $i++) {
        $active = ($i == $page) ? 'active' : '';
        $pagination .= '<li class="page-item ' . $active . '"><a class="page-link" href="javascript:void(0);" onclick="getPresupuestos(' . $i . ', \'' . $order . '\')">' . $i . '</a></li>';
    }

    if ($endPage < $totalPages) {
        $pagination .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
    }

    if ($page < $totalPages) {
        $pagination .= '<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="getPresupuestos(' . ($page + 1) . ', \'' . $order . '\')">Siguiente</a></li>';
        $pagination .= '<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="getPresupuestos(' . $totalPages . ', \'' . $order . '\')">Última</a></li>';
    }

    $pagination .= '</ul>
</nav>';

    return $pagination;
}
