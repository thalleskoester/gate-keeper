<?php

namespace ThallesDella\GateKeeper\Modules;

use ThallesDella\SimpleSession\Session;

/**
 * Gate Keeper | Class Request [ MODULE ]
 *
 * @category GateKeeper\Modules
 * @package  ThallesDella\GateKeeper\Modules
 * @author   Thalles D. koester <thallesdella@gmail.com>
 * @license  https://choosealicense.com/licenses/mit/ MIT
 * @link     https://github.com/thallesdella/gate-keeper
 */
class Request
{
    /**
     * @param string $key     Form identifier
     * @param int    $limit   Request limit
     * @param int    $seconds Time for refresh limit
     *
     * @return bool
     */
    public function requestLimit(string $key, int $limit = 5, int $seconds = 60): bool
    {
        $session = new Session();
        if ($session->has($key)
            && $session->$key->time >= time()
            && $session->$key->requests < $limit
        ) {
            $session->set(
                $key,
                [
                    "time" => time() + $seconds,
                    "requests" => $session->$key->requests + 1
                ]
            );
            return false;
        }
        
        if ($session->has($key)
            && $session->$key->time >= time()
            && $session->$key->requests >= $limit
        ) {
            return true;
        }
        
        $session->set(
            $key,
            [
                "time" => time() + $seconds,
                "requests" => 1
            ]
        );
        
        return false;
    }
}