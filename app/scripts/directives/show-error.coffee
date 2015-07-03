'use strict'

angular.module('3rdAnniversaryApp').directive 'showError', [ () ->
  restrict: 'C'
  link: (scope, element, attrs) ->
    scope.$on 'error', (event, response) ->
      $('#myModal').foundation('reveal', 'open')
]