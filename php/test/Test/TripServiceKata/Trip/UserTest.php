<?php

namespace Test\TripServiceKata\Trip;


use \Mockery;
use PHPUnit\Framework\TestCase;
use TripServiceKata\Exception\UserNotLoggedInException;
use TripServiceKata\User\User;

require __DIR__ . '/../../../../vendor/autoload.php';

class UserTest extends TestCase
{


    /** @test */
    public function user_with_no_friends_is_not_friend_with_some_user() {
        $lonelyUser = new User("Lonely");
        $this->assertFalse($lonelyUser->isFriendWith(new User("SomeUser")));
    }


    /** @test */
    public function user_with_a_friend_is_not_friend_with_some_user() {
        $lonelyUser = new User("Lonely");
        $lonelyUser->addFriend(new User("aFriend"));
        $this->assertFalse($lonelyUser->isFriendWith(new User("SomeUser")));
    }
    /** @test */
    public function user_with_a_friend_is_friend_with_that_user() {
        $friendlyUser = new User("Friendly");
        $friendOfUser = new User("Friend");
        $friendlyUser->addFriend($friendOfUser);
        $this->assertTrue($friendlyUser->isFriendWith($friendOfUser) );
    }

}
