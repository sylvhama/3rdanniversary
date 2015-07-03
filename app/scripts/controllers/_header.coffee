'use strict'

angular.module('3rdAnniversaryApp')
.controller '_headerController', ['$scope', '$location', '$cookieStore', ($scope, $location, $cookieStore) ->
  user = $cookieStore.get('user')
  $scope.connected = false

  if typeof user != "undefined"
    $scope.connected = true

  $scope.disconnect = ($event) ->
    $event.preventDefault
    if $scope.connected
      $cookieStore.remove('user')
      $location.path('/')
]