'use strict'

angular.module('3rdAnniversaryApp').directive 'shareKakao', [ () ->
  restrict: 'C'
  link: (scope, element, attrs) ->

    Kakao.Link.createTalkLinkButton
      container: '#' + attrs.id
      label: attrs.shareKakaoMsg
      image:
        src: attrs.shareKakaoImg
        width: "1200"
        height: "630"

      webButton:
        text: attrs.shareKakaoBtn
        url: attrs.shareKakaoUrl

    $(element).on "click", (event) ->
      event.preventDefault()
      ga('send', 'event', 'share', 'kakao')
      scope.updateShare()
]

#<a id="" class="share-kakao" href="#" share-kakao-msg="" share-kakao-url="" share-kakao-btn="" share-kakao-img=""></a>