<?php
echo '<h3>Copy Database Persediaan BLU to localhost</h3>';
copy_database($mysql_2_4_local_persediaan, 'dbsedia10blu', $mysql, 'dbsedia10blu'.'_'.date('YmdHis'));