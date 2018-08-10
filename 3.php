<?php
/**
 * Для решения этой задачи можно создать CLI-приложение, в
 * котором одной командой удалять все записи старше одного месяца,
 * опираясь на поле `created_at`(Unix time). Запускать можно по крону.
 */

function removeOldRows(PDO $pdo)
{
    $sql = "DELETE FROM `table_name` WHERE `created_at` < UNIX_TIMESTAMP() - :period";
    $st = $pdo->prepare($sql);
    $st->execute([
        ':period' => 60 * 60 * 24 * 30, // 1 month
    ]);
}