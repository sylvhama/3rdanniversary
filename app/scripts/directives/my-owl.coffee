'use strict'

angular.module('3rdAnniversaryApp').directive 'myOwl', ['$rootScope', 'userMGMT', ($rootScope, userMGMT) ->
  restrict: 'C'
  link: (scope, element, attrs) ->

    myowl = $(".my-owl")
    #owlgoto = $('.owl-goto')
    hotels = []
    scope.currentOwl = -1
    rewind = true
    if $rootScope.media == 'mobile'
      rewind = false

    scope.$on 'selectHotels', (event, response) ->
      hotels = response
      for hotel in hotels
        if $rootScope.media != 'mobile'
          myowl.append('<div class="item" hotel-id="'+hotel.hotel_id+'"><img src="/images/hotel/'+hotel.img_mob+'" alt="'+hotel.name_en+'"></div>')
        else
          myowl.append('<div class="item" hotel-id="'+hotel.hotel_id+'"><img src="/images/hotel/mobile/'+hotel.img_mob+'" alt="'+hotel.name_en+'"></div>')
      myowl.owlCarousel({
        items : 1,
        addClassActive: true,
        itemsDesktop : [1199,1],
        itemsDesktopSmall :	[979,1],
        itemsTablet :	[768,1],
        itemsMobile :	[479,1],
        rewindNav: rewind,
        pagination: true,
        mouseDrag: false,
        afterInit : ->
          scope.currentOwl = this.owl.currentItem
          scope.currentHotel = $('.owl-item.active').find('.item').attr('hotel-id')
          scope.hotelKr = hotels[scope.currentOwl].name_kr
          scope.hotelEn = hotels[scope.currentOwl].name_en
          scope.hotelLocation = hotels[scope.currentOwl].location
          scope.hotelUrl = hotels[scope.currentOwl].url
        afterMove : ->
          ###owlgoto.removeClass('owl-goto-active')
          $(owlgoto[this.owl.currentItem]).addClass('owl-goto-active')###
          scope.owlTouched = true
          scope.currentOwl = this.owl.currentItem
          scope.currentHotel = $('.owl-item.active').find('.item').attr('hotel-id')
          scope.hotelKr = hotels[scope.currentOwl].name_kr
          scope.hotelEn = hotels[scope.currentOwl].name_en
          scope.hotelLocation = hotels[scope.currentOwl].location
          scope.hotelUrl = hotels[scope.currentOwl].url
          if $rootScope.media == 'mobile'
            if scope.$root.$$phase != '$apply' and scope.$root.$$phase != '$digest'
              scope.$apply()
      })

      scope.swipeOwlLeft = () ->
        owlData.next()

      scope.swipeOwlRight = () ->
        owlData.prev()

      owlData = myowl.data('owlCarousel')

      $('.owl-next').on 'click', (event) ->
        event.preventDefault()
        owlData.next()
        scope.$apply()

      $('.owl-prev').on 'click', (event) ->
        event.preventDefault()
        owlData.prev()
        scope.$apply()

      $('.select-it').on 'click', (event) ->
        event.preventDefault()
        $('.owl-pagination').fadeToggle()


    if myowl[0]
      userMGMT.selectHotels()

    ###owlgoto.each ->
      $(this).on 'click', (event) ->
        event.preventDefault()
        owlData.goTo(parseInt($(this).attr("owl-goto")))###

    scope.$on "$destroy", (event) ->
      if myowl[0]
        myowl.stop()
]