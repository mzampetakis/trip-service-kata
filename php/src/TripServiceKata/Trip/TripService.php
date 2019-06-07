<?php

namespace TripServiceKata\Trip;

use TripServiceKata\User\User;
use TripServiceKata\User\UserSession;
use TripServiceKata\Exception\UserNotLoggedInException;

class TripService
{
    public function getTripsOfLoggedUserFriend(User $user) {
        $loggedUser = $this->getTripLoggedUser();
        if ($loggedUser == null) {
            throw new UserNotLoggedInException();
        }

        return $user->isFriendWith($loggedUser) ? $this->findUserTrips($user) : $this->noTrips();
    }

    private function noTrips(){
        return array();
    }

    /**
     * @return User
     */
    public function getTripLoggedUser()
    {
        $loggedUser = UserSession::getInstance()->getLoggedUser();
        return $loggedUser;
    }

    /**
     * @param User $user
     */
    public function findUserTrips(User $user)
    {
        $tripList = TripDAO::findTripsByUser($user);
        return $tripList;
    }
}
