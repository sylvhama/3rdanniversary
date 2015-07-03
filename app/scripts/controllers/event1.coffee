'use strict'

angular.module('3rdAnniversaryApp')
.controller 'event1Controller', ['$scope', '$location', '$cookieStore', ($scope, $location, $cookieStore) ->

  user = $cookieStore.get('user')

  if typeof user == "undefined"
    $location.path '/event1/register'
]