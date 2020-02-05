<?php

namespace ThallesDella\GateKeeper\GateKeeper;

use stdClass;

/**
 * Gate Keeper | Class Actions [ GATE KEEPER ]
 *
 * @category GateKeeper
 * @package  ThallesDella\GateKeeper
 * @author   Thalles D. koester <thallesdella@gmail.com>
 * @license  https://choosealicense.com/licenses/mit/ MIT
 * @link     https://github.com/thallesdella/gate-keeper
 */
class Actions
{
    /** @const int Success Action */
    public const SUCCESS_ACTION = 0;
    
    /** @const int Failure Action */
    public const FAILURE_ACTION = 1;
    
    /**
     * @var stdClass
     */
    private $success;
    
    /**
     * @var stdClass
     */
    private $failure;
    
    /**
     * @var mixed
     */
    private $return;
    
    /**
     * Actions constructor.
     */
    public function __construct()
    {
        $buf = new stdClass();
        $buf->action = null;
        $buf->action_type = null;
    
        $this->failure = $buf;
        $this->success = $buf;
    }
    
    /**
     * @param string $func
     * @param int    $type
     *
     * @return void
     */
    public function registerFunction(string $func, int $type): void
    {
        if ($type == self::SUCCESS_ACTION) {
            $this->success->action = $func;
            $this->success->action_type = 'func';
            return;
        }
        $this->failure->action = $func;
        $this->failure->action_type = 'func';
    }
    
    /**
     * @param mixed  $class
     * @param string $method
     * @param int    $type
     *
     * @return void
     */
    public function registerClass($class, string $method, int $type): void
    {
        $arr = [$class, $method];
        if ($type == self::SUCCESS_ACTION) {
            $this->success->action = $arr;
            $this->success->action_type = 'class';
            return;
        }
        $this->failure->action = $arr;
        $this->failure->action_type = 'class';
    }
    
    /**
     * @return bool
     */
    public function callSuccess(): bool
    {
        $this->return = call_user_func($this->success->action);
        return $this->verifyResult();
    }
    
    /**
     * @return bool
     */
    public function callFailure(): bool
    {
        $this->return = call_user_func($this->failure->action);
        return $this->verifyResult();
    }
    
    /**
     * @return bool
     */
    private function verifyResult(): bool
    {
        return (!$this->return ? false : true);
    }
}