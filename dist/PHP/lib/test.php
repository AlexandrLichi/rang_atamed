<?php
$date = '2024-09-13 14:07:10';
if (strtotime($date) < mktime(0, 0, 0, date('m') - 1, date('d'), date("Y"))) {
                echo 'ok';
}else{
    echo 'нет';

}
?>