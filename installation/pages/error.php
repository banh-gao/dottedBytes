<?php
echo "ERROR!\n";
echo @htmlentities($_REQUEST['cause'], ENT_QUOTES, 'UTF-8' );
