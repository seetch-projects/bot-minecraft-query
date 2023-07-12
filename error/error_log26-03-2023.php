<?php http_response_code(404);exit("404");?>
LOGS:

[Exception] 26.03.23 00:07:07
CODE: 5
MESSAGE: Array
(
    [error_code] => 5
    [error_msg] => User authorization failed: invalid access_token (4).
    [request_params] => Array
        (
            [fields] => 
            [name_case] => nom
            [v] => 5.120
        )

)


in: C:\Users\stherich\Documents\GitHub\query_minecraft\vendor\digitalstars\simplevk\src\SimpleVK.php:748
Stack trace:
#0 C:\Users\stherich\Documents\GitHub\query_minecraft\vendor\digitalstars\simplevk\src\SimpleVK.php(593): DigitalStars\SimpleVK\SimpleVK->request_core('users.get', Array)
#1 C:\Users\stherich\Documents\GitHub\query_minecraft\vendor\digitalstars\simplevk\src\SimpleVK.php(448): DigitalStars\SimpleVK\SimpleVK->request('users.get', Array)
#2 C:\Users\stherich\Documents\GitHub\query_minecraft\vendor\digitalstars\simplevk\src\LongPoll.php(22): DigitalStars\SimpleVK\SimpleVK->userInfo()
#3 C:\Users\stherich\Documents\GitHub\query_minecraft\vendor\digitalstars\simplevk\src\LongPoll.php(40): DigitalStars\SimpleVK\LongPoll->__construct('TOKEN', '5.120', NULL)
#4 C:\Users\stherich\Documents\GitHub\query_minecraft\run.php(8): DigitalStars\SimpleVK\LongPoll::create('TOKEN', '5.120')
#5 {main}

[Exception] 26.03.23 00:07:07
CODE: 5
MESSAGE: Array
(
    [error_code] => 5
    [error_msg] => User authorization failed: invalid access_token (4).
    [request_params] => Array
        (
            [v] => 5.120
        )

)


in: C:\Users\stherich\Documents\GitHub\query_minecraft\vendor\digitalstars\simplevk\src\SimpleVK.php:748
Stack trace:
#0 C:\Users\stherich\Documents\GitHub\query_minecraft\vendor\digitalstars\simplevk\src\SimpleVK.php(593): DigitalStars\SimpleVK\SimpleVK->request_core('groups.getById', Array)
#1 C:\Users\stherich\Documents\GitHub\query_minecraft\vendor\digitalstars\simplevk\src\LongPoll.php(27): DigitalStars\SimpleVK\SimpleVK->request('groups.getById')
#2 C:\Users\stherich\Documents\GitHub\query_minecraft\vendor\digitalstars\simplevk\src\LongPoll.php(40): DigitalStars\SimpleVK\LongPoll->__construct('TOKEN', '5.120', NULL)
#3 C:\Users\stherich\Documents\GitHub\query_minecraft\run.php(8): DigitalStars\SimpleVK\LongPoll::create('TOKEN', '5.120')
#4 {main}

