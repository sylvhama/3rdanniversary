'use strict'

angular.module('3rdAnniversaryApp').directive 'tracker', [() ->
  restrict: 'A'
  link: (scope, element, attrs) ->
    $(element).on 'click', (event) ->
      ga('send', 'event', attrs.trackerType, attrs.tracker)
]

#tracker="" tracker-type=""