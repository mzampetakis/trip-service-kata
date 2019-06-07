<?php

namespace Test\TripServiceKata\Trip;


use \Mockery;
use PHPUnit\Framework\TestCase;
use TripServiceKata\Exception\UserNotLoggedInException;
use TripServiceKata\Trip\Trip;
use TripServiceKata\Trip\TripService;
use TripServiceKata\User\User;

require __DIR__ . '/../../../../vendor/autoload.php';

class TripServiceTest extends TestCase
{
    /**
     * @var TripService
     */
    private $tripService;

    protected function setUp()
    {
        $this->tripService = new TripService;
    }

    /** @test */
    public function on_trip_searching_returns_exception_when_user_not_logged_in() {

        $MockTripService = Mockery::mock(TripService::class)->makePartial();
        $MockTripService->shouldReceive("getTripLoggedUser")
            ->andReturn(null);

        $user = new User("Michalis");
        $this->expectException(UserNotLoggedInException::class);
        $MockTripService->getTripsOfLoggedUserFriend($user);

        Mockery::close();
    }

    /** @test */
    public function on_trip_searching_returns_empty_list_when_user_logged_in_without_friends() {
        $loggedUser = new User("LoggedIn");
        $lonelyUser = new User("Lonely");

        $MockTripService = Mockery::mock(TripService::class)->makePartial();
        $MockTripService->shouldReceive("getTripLoggedUser")
            ->andReturn($loggedUser);

       $userTrips =  $MockTripService->getTripsOfLoggedUserFriend($lonelyUser);
       $this->assertEquals([], $userTrips);
    }


    /** @test */
    public function on_trip_searching_returns_empty_list_when_user_logged_in_with_logged_out_friends() {
        $loggedUser = new User("LoggedIn");
        $friendlyUserWithOfflineFriends = new User("OfflineFriendsUser");
        $friendlyUserWithOfflineFriends->addFriend(new User("someFriend"));

        $MockTripService = Mockery::mock(TripService::class)->makePartial();
        $MockTripService->shouldReceive("getTripLoggedUser")
            ->andReturn($loggedUser);

        $userTrips =  $MockTripService->getTripsOfLoggedUserFriend($friendlyUserWithOfflineFriends);
        $this->assertEquals([], $userTrips);
    }


    /** @test */
    public function on_trip_searching_returns_empty_list_when_user_logged_in_with_loggedin_friends() {
        $loggedUser = new User("LoggedIn");
        $friendlyUserWithOfflineFriends = new User("OfflineFriendsUser");
        $friendlyUserWithOfflineFriends->addFriend($loggedUser);

        $MockTripService = Mockery::mock(TripService::class)->makePartial();
        $MockTripService->shouldReceive("getTripLoggedUser")
            ->andReturn($loggedUser);
        
        $MockTripService->shouldReceive("findUserTrips")
                ->andReturn(array(new Trip()));

        $userTrips =  $MockTripService->getTripsOfLoggedUserFriend($friendlyUserWithOfflineFriends);
        $this->assertEquals(array(new Trip()), $userTrips);
    }



}
