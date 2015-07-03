'use strict'

angular.module('3rdAnniversaryApp')
.controller 'event2Controller', ['$scope', '$location', '$cookieStore', 'userMGMT', ($scope, $location, $cookieStore, userMGMT) ->

  #select comments
  selectComments = ->
    userMGMT.selectComments()

  user = $cookieStore.get('user')
  $scope.e2Ready = false

  if typeof user == "undefined"
    $location.path '/event2/register'
  else
    $scope.e2Ready = true
    selectComments()

  $scope.comments = []
  $scope.selected = false
  $scope.currentHotel = -1
  $scope.hotelLocation = ''
  $scope.hotelUrl = ''
  $scope.hotelKr = ''
  $scope.hotelEn = ''

  $scope.selectHotel = ($event) ->
    $event.preventDefault()
    $scope.selected = !$scope.selected

  $scope.$on 'selectComments', (event, response) ->
    $scope.comments = response

  #add comment
  $scope.checkComment = false

  $scope.getCommentLength = () ->
    if $scope.myFormComment.inputComment.$error.required
      return 0
    else if $scope.myFormComment.inputComment.$error.maxlength
      return $scope.myFormComment.inputComment.$viewValue.length
    else
      if typeof $scope.fields != 'undefined'
        l = $scope.fields.comment.length
        if l == 0
          return 0
        else
          return l
      return 0

  $scope.comment = ($event) ->
    $event.preventDefault
    $scope.checkComment = true
    if $scope.myFormComment.$valid and $scope.currentHotel != -1
      comment = {}
      comment.userId = user.id
      comment.comment = $scope.fields.comment
      comment.hotelId = $scope.currentHotel
      userMGMT.addComment(comment)

  $scope.$on 'addComment', (event, response) ->
    $location.path('/event2/share')
]