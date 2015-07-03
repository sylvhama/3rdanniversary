'use strict'

angular.module('3rdAnniversaryApp')
.factory 'userMGMT', ['$rootScope', '$http', ($rootScope, $http) ->

    fact = {}

    fact.selectUser = (user) ->
      $http.post("./php/do.php?r=selectUser"
        data: {
          user: user,
          hash: 'ETzS7Juimc05VdUXB95fXV2aLYWDrAeW6PIOYRYB'
        }
      ).success((data, status) ->
        if !data.error
          $rootScope.$broadcast('selectUser', data)
        else
          console.log "[Error][userMGMT] " + data.error
          ga('send', 'event', 'userMGMT Error', 'selectUser SQL ', data.error)
          $rootScope.$broadcast('error',  data.error)
      ).error (data, status) ->
        console.log "[Error][userMGMT] " + status
        ga('send', 'event', 'userMGMT Error', 'selectUser AJAX ', status)

    fact.selectMyPrize = (user) ->
      $http.post("./php/do.php?r=selectMyPrize"
        data: {
          user: user,
          hash: 'ETzS7Juimc05VdUXB95fXV2aLYWDrAeW6PIOYRYB'
        }
      ).success((data, status) ->
        if !data.error
          $rootScope.$broadcast('selectMyPrize', data)
        else
          console.log "[Error][userMGMT] " + data.error
          ga('send', 'event', 'userMGMT Error', 'selectMyPrize SQL ', data.error)
          $rootScope.$broadcast('error',  data.error)
      ).error (data, status) ->
        console.log "[Error][userMGMT] " + status
        ga('send', 'event', 'userMGMT Error', 'selectMyPrize AJAX ', status)

    fact.selectLastShare = (user) ->
      $http.post("./php/do.php?r=selectLastShare"
        data: {
          user: user,
          hash: 'ETzS7Juimc05VdUXB95fXV2aLYWDrAeW6PIOYRYB'
        }
      ).success((data, status) ->
        if !data.error
          $rootScope.$broadcast('selectLastShare', data)
        else
          console.log "[Error][userMGMT] " + data.error
          ga('send', 'event', 'userMGMT Error', 'selectLastShare SQL ', data.error)
          $rootScope.$broadcast('error',  data.error)
      ).error (data, status) ->
        console.log "[Error][userMGMT] " + status
        ga('send', 'event', 'userMGMT Error', 'selectMLastShare AJAX ', status)

    fact.selectHotels = () ->
      $http.post("./php/do.php?r=selectHotels"
        data: {
          hash: 'ETzS7Juimc05VdUXB95fXV2aLYWDrAeW6PIOYRYB'
        }
      ).success((data, status) ->
        if !data.error
          $rootScope.$broadcast('selectHotels', data)
        else
          console.log "[Error][userMGMT] " + data.error
          ga('send', 'event', 'userMGMT Error', 'selectHotels SQL ', data.error)
          $rootScope.$broadcast('error',  data.error)
      ).error (data, status) ->
        console.log "[Error][userMGMT] " + status
        ga('send', 'event', 'userMGMT Error', 'selectHotels AJAX ', status)

    fact.selectComments = () ->
      $http.post("./php/do.php?r=selectComments"
        data: {
          hash: 'ETzS7Juimc05VdUXB95fXV2aLYWDrAeW6PIOYRYB'
        }
      ).success((data, status) ->
        if !data.error
          $rootScope.$broadcast('selectComments', data)
        else
          console.log "[Error][userMGMT] " + data.error
          ga('send', 'event', 'userMGMT Error', 'selectComments SQL ', data.error)
          $rootScope.$broadcast('error',  data.error)
      ).error (data, status) ->
        console.log "[Error][userMGMT] " + status
        ga('send', 'event', 'userMGMT Error', 'selectComments AJAX ', status)

    fact.selectCredits = (user) ->
      $http.post("./php/do.php?r=selectCredits"
        data: {
          user: user,
          hash: 'ETzS7Juimc05VdUXB95fXV2aLYWDrAeW6PIOYRYB'
        }
      ).success((data, status) ->
        if !data.error
          $rootScope.$broadcast('selectCredits', data)
        else
          console.log "[Error][userMGMT] " + data.error
          ga('send', 'event', 'userMGMT Error', 'selectCredits SQL ', data.error)
          $rootScope.$broadcast('error',  data.error)
      ).error (data, status) ->
        console.log "[Error][userMGMT] " + status
        ga('send', 'event', 'userMGMT Error', 'selectCredits AJAX ', status)

    fact.addComment = (comment) ->
      $http.post("./php/do.php?r=addComment"
        data: {
          comment: comment,
          hash: 'ETzS7Juimc05VdUXB95fXV2aLYWDrAeW6PIOYRYB'
        }
      ).success((data, status) ->
        if !data.error
          $rootScope.$broadcast('addComment', data)
        else
          console.log "[Error][userMGMT] " + data.error
          ga('send', 'event', 'userMGMT Error', 'addComment SQL ', data.error)
          $rootScope.$broadcast('error',  data.error)
      ).error (data, status) ->
        console.log "[Error][userMGMT] " + status
        ga('send', 'event', 'userMGMT Error', 'addComment AJAX ', status)

    fact.addUser = (user) ->
      $http.post("./php/do.php?r=addUser"
        data: {
          user: user,
          hash: 'ETzS7Juimc05VdUXB95fXV2aLYWDrAeW6PIOYRYB'
        }
      ).success((data, status) ->
        if !data.error
          $rootScope.$broadcast('addUser', data)
        else
          console.log "[Error][userMGMT] " + data.error
          ga('send', 'event', 'userMGMT Error', 'addUser SQL ', data.error)
          $rootScope.$broadcast('error',  data.error)
      ).error (data, status) ->
        console.log "[Error][userMGMT] " + status
        ga('send', 'event', 'userMGMT Error', 'addUser AJAX ', status)

    fact.doUpdateShareEvent1 = (user) ->
      $http.post("./php/do.php?r=updateShareEvent1"
        data: {
          user: user,
          hash: 'ETzS7Juimc05VdUXB95fXV2aLYWDrAeW6PIOYRYB'
        }
      ).success((data, status) ->
        if !data.error
          $rootScope.$broadcast("update_share", data)
        else
          console.log "[Error][userMGMT] " + data.error
          ga('send', 'event', 'userMGMT', 'updateShareEvent1 sql ', data.error)
      ).error (data, status) ->
        console.log "[Error][userMGMT] " + status
        ga('send', 'event', 'userMGMT', 'updateShareEvent1 ajax ', status)

    fact.doUpdateShareEvent2 = (user) ->
      $http.post("./php/do.php?r=updateShareEvent2"
        data: {
          user: user,
          hash: 'ETzS7Juimc05VdUXB95fXV2aLYWDrAeW6PIOYRYB'
        }
      ).success((data, status) ->
        if !data.error
          $rootScope.$broadcast("update_share", data)
        else
          console.log "[Error][userMGMT] " + data.error
          ga('send', 'event', 'userMGMT', 'updateShareEvent2 sql ', data.error)
      ).error (data, status) ->
        console.log "[Error][userMGMT] " + status
        ga('send', 'event', 'userMGMT', 'updateShareEvent2 ajax ', status)

    fact.doPlay = (user) ->
      $http.post("./php/do.php?r=play"
        data: {
          user: user,
          hash: 'ETzS7Juimc05VdUXB95fXV2aLYWDrAeW6PIOYRYB'
        }
      ).success((data, status) ->
        if !data.error
          $rootScope.$broadcast("play", data)
        else
          console.log "[Error][userMGMT] " + data.error
          ga('send', 'event', 'userMGMT', 'play sql ', data.error)
      ).error (data, status) ->
        console.log "[Error][userMGMT] " + status
        ga('send', 'event', 'userMGMT', 'play ajax ', status)

    return fact
  ]