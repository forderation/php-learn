<?php
include_once 'create.php';
include_once 'delete.php';
include_once 'read.php';
include_once 'update.php';

class Api
{
    use CreateApi;
    use DeleteApi;
    use ReadApi;
    use UpdateApi;
}
