<?php

  // Create The Object Array

  $irc = (object)array(

    "server" => "irc.freenode.net",

    "port" => 6667,

    "name" => "",

    "nick" => "",

    "password" => "",

    "channel" => "#"

  );

  // Create Array Of Server Variables

  $server = array();

  // Attempt To a Connect To IRC Server

  $server["socket"] = @fsockopen($irc->server, $irc->port, $errno, $errstr, 3);

  // Failed To Connect

  if(!($server["socket"])) {

    die("$errstr");

  } else {

  // Successfully Connected, Now Login

    SendCmd("PASS " . $irc->password . "\r\n");

    SendCmd("NICK " . $irc->nick . "\r\n");

    SendCmd("USER " . $irc->nick . " USING PHP IRCBOT\r\n");

    while(!(feof($server["socket"]))) {

      $server["buffer"] = fgets($server["socket"], 128);

      echo "[Received]: " . $server["buffer"] . "<Br>";

      if(strpos($server["buffer"], "422")) {

        SendCmd("JOIN " . $irc->channel . "\r\n");

      }

      if(substr($server["buffer"], 0, 6) === "PING :") {

        SendCmd("PONG :" . substr($server["buffer"], 6) . "\r\n");

      }

      flush();

    }

  }

  function SendCmd($cmd) {

    global $server;

    @fwrite($server["socket"], $cmd, strlen($cmd));

    echo "[Sent]: " . $cmd . "<Br>";

  }

?>
