<?php
include("src/FullNameParser.php");
$parser = new FullNameParser();

//创建服务端的socket套接流,net协议为IPv4，protocol协议为TCP
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
/*绑定接收的套接流主机和端口,与客户端相对应*/
if (socket_bind($socket, '127.0.0.1', 38081) == false) {
    echo 'server bind fail:' . socket_strerror(socket_last_error());
    /*这里的127.0.0.1是在本地主机测试，你如果有多台电脑，可以写IP地址*/
}
//监听套接流
if (socket_listen($socket, 4) == false) {
    echo 'server listen fail:' . socket_strerror(socket_last_error());
}
function endsWith($haystack, $needle)
{
    return substr_compare($haystack, $needle, -strlen($needle)) === 0;
}

//让服务器无限获取客户端传过来的信息
do {
    /*接收客户端传过来的信息*/
    $accept_resource = socket_accept($socket);
    /*socket_accept的作用就是接受socket_bind()所绑定的主机发过来的套接流*/
    if ($accept_resource !== false) {
        /*读取客户端传过来的资源，并转化为字符串*/

        $received_str = array();
        while (true) {
            $line = socket_read($accept_resource, 2048);
//            echo $line;
            $received_str[] = $line;
//            if (substr($line, -1) == ' ') {
            if (substr($line, -1) == '}') {
                break;
            }
        }
        echo count($received_str);
        echo "\n";
        $jsonstr = implode('', $received_str);
//        $jsonstr = socket_read($accept_resource, 133693415);

//        echo $jsonstr;
        if ($jsonstr != false) {
            $jsonstr = mb_convert_encoding($jsonstr, 'UTF-8', 'UTF-8');
//            echo $jsonstr;
            $obj = json_decode($jsonstr);
//            echo $obj;
            $papers = $obj->names;
            $num_paper = count($papers);
            $paper_authors = array();
            for ($x = 0; $x < $num_paper; $x++) {
                $process_authors = array();
                $authors = $papers[$x];
                $num_author = count($authors);
                for ($y = 0; $y < $num_author; $y++) {
                    $name_parts = $parser->parse_name($authors[$y]);
                    $process_authors[] = array($name_parts['fname'], $name_parts['initials'], $name_parts['lname']);
                }
                $paper_authors[] = $process_authors;
            }
//            for ($x = 0; $x < $arrlength; $x++) {
//                echo $arr[$x];
//            }
            $return_client = json_encode($paper_authors, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
//            echo $return_client;

            socket_write($accept_resource, $return_client, strlen($return_client));
            /*socket_write的作用是向socket_create的套接流写入信息，或者向socket_accept的套接流写入信息*/
        } else {
            echo 'socket_read is fail';
        }
        /*socket_close的作用是关闭socket_create()或者socket_accept()所建立的套接流*/
        socket_close($accept_resource);
    }
} while (true);
socket_close($socket);

