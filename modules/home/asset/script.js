$(function () {
  // Home Slider
  var $slider = $('.slider')
    , left = 0, width = 930
    , total = width * $('.slide').size()

  $slider.css({width: total, height: 300})

  setInterval(function () {
    if (left >= -(total - (width * 2))) {
      left = left - width
    } else {
      left = 0
    }

    $slider.css('margin-left', left)
  }, 5000)
})
