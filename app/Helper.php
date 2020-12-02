<?php
namespace App;
class Helper
{

  function getServerIP()
   {
     $ipv4 = [];
     if (strpos($_SERVER['HTTP_USER_AGENT'],'Windows NT') !== false) {
       $interfaceCommand = "ipconfig";
       $ipconfig = shell_exec($interfaceCommand);
       $listAdapter = explode("\n",trim($ipconfig));

       foreach ($listAdapter as $key => $adapter) {
         $cip = explode('IPv4 Address. . . . . . . . . . . : ',trim($adapter));
         if (count($cip) > 1) {
           $ipv4[$listAdapter[$key-4]] = $cip[1];
         }
       }

       return $ipv4;
     }else{
       $interfaceCommand = "/sbin/ifconfig | grep 'flags' | awk -F: '{print $1}'";
       $ifconfig1 = exec($interfaceCommand);
       $adapter = explode("\n",$ifconfig1);

       $interfaceCommand = "/sbin/ifconfig | grep 'inet ' | awk -F' ' '{print $2}'";
       $ifconfig2 = exec($interfaceCommand);
       $ipaddr = explode("\n",$ifconfig2);

       foreach ($adapter as $key => $adapt) {
         $ipv4[$adapt] = $ipaddr[$key];
       }

       return $ipv4;
     }
   }
}
