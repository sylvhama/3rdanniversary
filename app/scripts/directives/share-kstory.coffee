'use strict'

angular.module('3rdAnniversaryApp').directive 'shareKstory', [ () ->
  restrict: 'C'
  link: (scope, element, attrs) ->
    $(element).on "click", (event) ->
      event.preventDefault()
      scope.updateShare()
      ga('send', 'event', 'share', 'kstory')

      kakao.link("story").send({
        post: attrs.kstoryPost,
        appid: attrs.kstoryAppid,
        appver: "1.0",
        appname: attrs.kstoryAppname,
        urlinfo: JSON.stringify({
          title: attrs.kstoryTitle,
          desc : attrs.kstoryDesc,
          imageurl: [attrs.kstoryImg],
          type:"website"}),
      })
]

# <a href="#" class="share-kstory" kstory-post="" kstory-appid="" kstory-appname="" kstory-title="" kstory-desc="" kstory-img=""></a>
