'use strict'

angular.module('3rdAnniversaryApp')
.controller 'homeController', ['$scope', '$location', ($scope, $location) ->
  $scope.hello = 'hello world'
]