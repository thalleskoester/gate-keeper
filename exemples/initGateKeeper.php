<?php
return dirname(__DIR__) . '/src/GateKeeper.php';

use \ThallesDella\GateKeeper\GateKeeper;

/* Simple user model */
$user['first_name'] = 'John';
$user['last_name'] = 'Doe';
$user['email'] = 'johndoe@foo.com';
$user['role'] = 'member';

/*
 * INITIALIZATION
 * Order of roles influence permissions
 */
$gateKeeper = new GateKeeper($user['role']);

/* Defining groups */
$gateKeeper->roles()
    ->addGroup('block', 'http://exemple.com/block')
    ->addGroup('user', 'http://exemple.com/login')
    ->addGroup('admin', 'http://exemple.com/admin')
    ->addGroup('root', 'http://exemple.com/admin');

/* Defining roles */
$gateKeeper->roles()
    ->addRole('block', ['posts.view'], 'block')
    ->addRole('user', null, 'user')
    ->addRole('member', ['posts.premium', 'comment.create'], 'user')
    ->addRole('writer', ['posts.create'], 'admin')
    ->addRole('manager', ['posts.edit', 'post.delete', 'comments.admin'], 'admin')
    ->addRole('root', null, 'root');
