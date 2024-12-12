<?php
return [
    $FAMILY . " " . $NAME,
    $FAMILY . " " .  mb_substr($NAME, 0, 1),
    $FAMILY . " " .  mb_substr($NAME, 0, 1) . " " .  mb_substr($NAME, 0, 1),
    $FAMILY . " " .  mb_substr($NAME, 0, 1) . "" . mb_substr($SURNAME, 0, 1),
   // $NAME . " " . $SURNAME
];
