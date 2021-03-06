'use strict'

((a) ->
  (jQuery.browser = jQuery.browser or {}).mobile = /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|pad|pod|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a) or /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))
) navigator.userAgent or navigator.vendor or window.opera

lang = window.navigator.userLanguage || window.navigator.language

angular
  .module('3rdAnniversaryApp', [
    'ngRoute',
    'ngCookies',
    'ngAnimate',
    'ngTouch'
  ])
  .config ['$routeProvider', '$locationProvider', ($routeProvider) ->
    $routeProvider
    .when '/',
        templateUrl: 'views/home.html'
        controller: 'homeController'
        page: 'home'
    .when '/event1/register',
        templateUrl: 'views/register.html'
        controller: 'registerController'
        page: 'event1'
    .when '/event2/register',
        templateUrl: 'views/register.html'
        controller: 'registerController'
        page: 'event2'
    .when '/event1/share',
        templateUrl: 'views/share.html'
        controller: 'shareController'
        page: 'event1'
    .when '/event2/share',
        templateUrl: 'views/share.html'
        controller: 'shareController'
        page: 'event2'
    .when '/event1',
        templateUrl: 'views/event1.html'
        controller: 'event1Controller'
        page: 'event1'
    .when '/event1/game',
        templateUrl: 'views/game1.html'
        controller: 'game1Controller'
        page: 'event1'
        mustLogin: true
    .when '/event2',
        templateUrl: 'views/event2.html'
        controller: 'event2Controller'
        page: 'event2'
    .when '/404',
        templateUrl: '404.html'
    .when '/terms',
        templateUrl: 'views/terms.html'
    .otherwise
        redirectTo: '/'
  ]

  .run ['$rootScope', '$location', '$route', '$anchorScroll', '$cookieStore', ($rootScope, $location, $route, $anchorScroll, $cookieStore) ->

    $rootScope.shareInfos = {}
    $rootScope.shareInfos.title = '바종 3주년 이벤트, 당신이 꿈꾸던 여행!'
    $rootScope.shareInfos.urlfb = 'http://event.evasion.co.kr/'
    $rootScope.shareInfos.urltwitter = encodeURIComponent('http://event.evasion.co.kr/')
    $rootScope.shareInfos.urlkakao = 'http://event.evasion.co.kr/'
    $rootScope.shareInfos.urlkstory = 'http://event.evasion.co.kr/'
    $rootScope.shareInfos.img = 'http://event.evasion.co.kr/images/share.jpg'
    $rootScope.shareInfos.twitter = encodeURIComponent('에바종이 3주년을 맞아 당신이 꿈꾸던 최고의 여행을 보내드립니다! 프랑스 항공권 & 4박 숙박권, 그랜드 하얏트 홍콩 숙박권 등 다양한 선물! 지금 참여하세요!')
    $rootScope.shareInfos.kakao = '럭셔리 트래블 클럽 에바종이 3주년을 맞아 당신이 꿈꾸던 최고의 여행을 보내드립니다! 프랑스 왕복 항공권 & 4박 숙박권, 그랜드 하얏트 홍콩 숙박권 등 다양한 선물과 응모만 해도 주어지는 즉시 사용 가능 할인 쿠폰까지!  지금 참여하세요!'
    $rootScope.shareInfos.kstory = {}
    $rootScope.shareInfos.kstory.post = '럭셔리 트래블 클럽 에바종이 3주년을 맞아 당신이 꿈꾸던 최고의 여행을 보내드립니다! 프랑스 왕복 항공권 & 4박 숙박권, 그랜드 하얏트 홍콩 숙박권 등 다양한 선물과 응모만 해도 주어지는 즉시 사용 가능 할인 쿠폰까지!  지금 참여하세요!' + $rootScope.shareInfos.urlkstory
    $rootScope.shareInfos.kstory.appname = '에바종 3주년 이벤트, 당신이 꿈꾸던 여행!'

    $rootScope.$on '$routeChangeSuccess', (event, next, current) ->
      $anchorScroll()
      if typeof $route.current.$$route != 'undefined'
        $rootScope.page = $route.current.$$route.page

    if jQuery.browser.mobile is true
      $rootScope.media = 'mobile'
    else
      $rootScope.media = 'desktop'

    $rootScope.lang = 'en'
    if lang == 'ko' or lang == 'ko-KR' or lang == 'ko-kr' or lang == 'KO'
      $rootScope.lang = 'ko'

    ga('send', 'pageview', '/' + $rootScope.media + $location.path())
  ]


