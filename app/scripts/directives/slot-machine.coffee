'use strict'

angular.module('3rdAnniversaryApp').directive 'slotMachine', [() ->
  restrict: 'C'
  link: (scope, element, attrs) ->
    $col = $('.slot-column')
    $col1 = $($col[0])
    $col2 = $($col[1])
    $col3 = $($col[2])
    $row = $('.slot-row')
    $lever = $(element)
    $values = $('.slot-row img')

    slotHeight = $row.height()+parseInt($row.css('padding-top'))
    slots = -1
    $col1.find('.slot-row').each ->
      slots++

    resetSlot = ->
      $col1.css({top:0})
      $col2.css({bottom:0})
      $col3.css({top:0})

    bottomRand = (rand) ->
      if rand == slots
        return slots
      else
        return slots-rand

    stopSlot = ->
      $lever.removeClass('lever-anim')
      $col.stop(true, false).removeClass('fast')
      resetSlot()
      if scope.rand <= slots and scope.rand > 0
        $col1.animate({top:-scope.rand*slotHeight}, 1000, "swing")
        $col2.animate({bottom:-(bottomRand(scope.rand))*slotHeight}, 1500, "swing")
        $col3.animate({top:-scope.rand*slotHeight}, 2000, "swing", ->
          $values.addClass('win')
          scope.won = true
          scope.running = false
          scope.wait = false
          scope.$apply()
        )
      else
        rand1 = Math.floor(Math.random() * slots+1)
        rand2 = Math.floor(Math.random() * slots+1)
        rand3 = Math.floor(Math.random() * slots+1)
        while rand1 == rand3
          rand3 = Math.floor(Math.random() * slots+1)
        $col1.animate({top:-rand1*(slotHeight)}, 1000, "swing")
        $col2.animate({bottom:-(bottomRand(rand2))*(slotHeight)}, 1000, "swing")
        $col3.animate({top:-rand3*(slotHeight)}, 1000, "swing", () ->
          scope.running = false
          scope.wait = false
          scope.$apply()
        )

    runSlot = (times) ->
      if scope.running and times > 0
        if !$col.hasClass('fast')
          $col.addClass('fast')
        $col1.animate({top:-$col.height()+slotHeight}, 500, "linear")
        $col2.animate({bottom:-$col.height()+slotHeight}, 500, "linear")
        $col3.animate({top:-$col.height()+slotHeight}, 500, "linear", () ->
          resetSlot()
          runSlot(times-1)
        )
      else
        resetSlot()
        stopSlot()

    scope.$on "play", (event, response) ->
      scope.prizeToShow = parseInt(response)
      tmp = parseInt(response)
      if tmp >=4 and tmp <=6 then tmp = 5
      if tmp == 7 then tmp = 4
      scope.rand = tmp
      if !scope.running and !scope.won and scope.credits>0
        $values.removeClass('win')
        $col.finish()
        resetSlot()
        scope.running = true
        scope.won = false
        scope.credits--
        $lever.addClass('lever-anim')
        runSlot(6)
]


