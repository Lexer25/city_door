<?php
/**
 * Вспомогательные функции для шаблонов
 */

function statusBadge($active, $trueText = 'Активно', $falseText = 'Неактивно')
{
    $class = $active ? 'success' : 'danger';
    $text = $active ? $trueText : $falseText;
    return '<span class="label label-' . $class . '">' . $text . '</span>';
}

function formatDate($date, $default = '—')
{
    return $date ? date("d.m.Y H:i:s", strtotime($date)) : $default;
}

function loadResultLabel($result)
{
    if ($result === null) {
        return '<span class="label label-default">' . __('Ожидание') . '</span>';
    } elseif ($result == 0) {
        return '<span class="label label-success">' . __('Успешно') . '</span>';
    } else {
        return '<span class="label label-danger">' . __('Ошибка') . ' (' . $result . ')</span>';
    }
}
?>