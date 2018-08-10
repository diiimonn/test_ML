<?php

function initTable()
{
    $pdo = getPDO();

    $sql = "CREATE TABLE IF NOT EXISTS `test_ml` (
  `id` char(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL PRIMARY KEY,
  `text` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    $pdo->query($sql);
}

function addStrings(array $strings)
{
    $pdo = getPDO();

    $exists = [];

    foreach($strings as $string) {
        $id = sha1($string);

        if (!isset($exists[$id])) {
            $exists[$id] = 1;

            $sql = "INSERT INTO `test_ml` (`id`, `text`) VALUE (:id, :text)";
            $st = $pdo->prepare($sql);
            $st->execute([
                ':id' => $id,
                ':text' => $string
            ]);
        }
    }
}

function isStringExist(string $string)
{
    $pdo = getPDO();
    $id = sha1($string);

    $sql = "SELECT COUNT(*) as `count` FROM `test_ml` WHERE `id` = :id;";
    $st = $pdo->prepare($sql);
    $st->execute([':id' => $id]);
    $result = $st->fetch();

    return $result['count'] > 0;
}


function getPDO()
{
    $dsn = 'mysql:host=localhost;dbname=***;charset=utf8';
    $user = '***';
    $password = '***';

    return new PDO($dsn, $user, $password);
}


initTable();

$t = microtime(true);
addStrings($docs);
echo "T = " . (microtime(true) - $t) . "\n";

$t = microtime(true);
$is_exist = isStringExist($string);
echo "T = " . (microtime(true) - $t) . "\n";
echo ($is_exist ? "exist" : "not exist") . "\n";