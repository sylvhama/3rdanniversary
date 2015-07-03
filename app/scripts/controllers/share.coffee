'use strict'

angular.module('3rdAnniversaryApp')
.controller 'shareController', ['$scope', '$location', '$cookieStore', 'userMGMT', ($scope, $location, $cookieStore, userMGMT) ->

  redirect = ->
    if $location.path() == '/event1/share'
      $location.path '/event1/register'
    else if $location.path() == '/event2/share'
      $location.path '/event2/register'
    else
      $location.path '/'

  $scope.prizeToShow = -1
  $scope.won = false
  $scope.replayUrl = '/'
  $scope.nextUrl = '/'
  $scope.nextEvent = 'Event 1'
  $scope.alreadyShared = 1
  $scope.shareEvent = false
  user = $cookieStore.get('user')
  if typeof user == "undefined"
    redirect()
  else
    if $location.path() == '/event1/share'
      userMGMT.selectMyPrize(user)
      userMGMT.selectLastShare(user)
      $scope.replayUrl = '/event1/game'
      $scope.nextUrl = '/event2'
      $scope.nextEvent = 'Event 2'
    else
      $scope.nextUrl = '/event1'
      $scope.replayUrl = '/event2'
      $scope.prizeToShow = 7
      $scope.won = true



  $scope.updateShare = () ->
    $scope.shareEvent = true
    if typeof user != "undefined"
      if $location.path() == '/event1/share'
        userMGMT.doUpdateShareEvent1(user)
      else if $location.path() == '/event2/share'
        userMGMT.doUpdateShareEvent2(user)

  $scope.$on 'selectMyPrize', (event, response) ->
    $scope.won = true
    $scope.prizeToShow = parseInt(response)

  $scope.$on 'selectLastShare', (event, response) ->
    $scope.alreadyShared = parseInt(response)
]