'use strict'

angular.module('3rdAnniversaryApp').directive 'placeholder', () ->
  restrict: 'A'

  link: (scope, element, attrs) ->

    $(element).placeholder()