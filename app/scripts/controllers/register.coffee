'use strict'

angular.module('3rdAnniversaryApp')
.controller 'registerController', ['$scope', '$location', '$cookieStore', 'userMGMT', ($scope, $location, $cookieStore, userMGMT) ->

  redirect = ->
    if $location.path() == '/event1/register'
      $location.path '/event1'
    else if $location.path() == '/event2/register'
      $location.path '/event2'
    else
      $location.path '/'

  user = $cookieStore.get('user')
  if typeof user != "undefined"
    redirect()

  $scope.checkRegister = false
  $scope.checkConnect = false
  $scope.error = ''
  name = ''

  $scope.showTab1 = true
  $scope.showTab2 = false

  $scope.reverseTabs = ($event, t1, t2) ->
    $event.preventDefault()
    $scope.showTab1 = t1
    $scope.showTab2 = t2

  $scope.connect = ($event) ->
    $event.preventDefault
    $scope.error = ''
    $scope.checkConnect = true
    if $scope.myFormConnect.$valid
      userMGMT.selectUser($scope.connectFields)

  $scope.register = ($event) ->
    $event.preventDefault
    $scope.error = ''
    $scope.checkRegister = true
    if $scope.myFormRegister.$valid and $scope.registerFields.password == $scope.registerFields.password2
      name = $scope.registerFields.name
      userMGMT.addUser($scope.registerFields)

  $scope.$on 'error', (event, response) ->
    $scope.error = response

  $scope.$on 'selectUser', (event, response) ->
    $cookieStore.put('user', response)
    redirect()

  $scope.$on 'addUser', (event, response) ->
    $cookieStore.put('user', {'id':response, 'name':name})
    redirect()
]