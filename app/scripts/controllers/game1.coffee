'use strict'

angular.module('3rdAnniversaryApp')
.controller 'game1Controller', ['$scope', '$location', '$cookieStore', 'userMGMT', ($scope, $location, $cookieStore, userMGMT) ->
  user = $cookieStore.get('user')

  if typeof user == "undefined"
    $location.path '/event1/register'

  ###$scope.showTab1 = true
  $scope.showTab2 = false

  $scope.reverseTabs = ($event, t1, t2) ->
    $event.preventDefault()
    $scope.showTab1 = t1
    $scope.showTab2 = t2###

  userMGMT.selectCredits(user)

  $scope.prizeToShow = -1
  $scope.credits = -1
  $scope.won = false
  $scope.running = false
  $scope.wait = false
  $scope.hiddenCoin = [true, true, true]

  $scope.hideCoin = (index) ->
    if !$scope.running
      $scope.hiddenCoin[index] = true

  $scope.prizeClose = () ->
    $scope.won = false

  $scope.play = ($event, index) ->
    $event.preventDefault()
    rightCoin = true
    if index>0 and !$scope.hiddenCoin[index-1]
      rightCoin = false
    if !$scope.running and $scope.credits>0 and rightCoin
      $scope.wait = true
      $scope.hideCoin(parseInt(index))
      userMGMT.doPlay(user)

  $scope.$on 'selectCredits', (event, response) ->
    $scope.credits = parseInt(response)
    toShow = $scope.credits
    for hidden, index in $scope.hiddenCoin
      if toShow > 0
        $scope.hiddenCoin[index] = false
      toShow--
]