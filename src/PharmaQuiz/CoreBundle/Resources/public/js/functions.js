(function ($) {
  $(document).on('focusin', '.field, textarea', function() {
    if(this.title==this.value) {
      this.value = '';
    }
  }).on('focusout', '.field, textarea', function(){
    if(this.value=='') {
      this.value = this.title;
    }
  });

  $('#navigation ul li:first-child').addClass('first');
  $('.footer-nav ul li:first-child').addClass('first');

  $('#navigation a.nav-btn').click(function(){
    $(this).closest('#navigation').find('ul').slideToggle()
    $(this).find('span').toggleClass('active')
    return false;
  })

  var $quizForm = $('.quiz-form');
  if ($quizForm.length) {
    var $first = $quizForm.find('div:first-child');
    var hasMore = $first.next('.question').length;

    if (hasMore) {
      $('#quiz_questions_send').hide();
      $quizForm.find('.question').hide();
      $quizForm.find('div:first-child, div:first-child .fake-button').show();
      $quizForm.find('div:first-child .fake-button').bind('click', function(event) {
        show_next($(this));
      });
    }
    else {
      $('#quiz_questions_send').show();
    }
  }

  $('form.quiz-form').submit(function(e) {
    var $this = $(this);
    var has_errors = false;
    $this.find('.question').each(function () {
      if (!validate_checkboxes($(this))) {
        $(this).find('.required-message').show();
        e.preventDefault();
        has_errors = true;
      }
    });
    if (!has_errors) {
      $this.find('*[type="submit"]').attr('disabled', 'disabled');
    }
  });

  var $results = $('#quiz-results');
  if ($results.length) {
    $results.highcharts({
      chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false
      },
      title: {
        text: $results.data('title'),
      },
      tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.0f}%</b>'
      },
      plotOptions: {
        pie: {
          allowPointSelect: true,
          cursor: 'pointer',
          dataLabels: {
            enabled: true,
            color: '#000000',
            connectorColor: '#000000',
            format: '<b>{point.name}</b>: {point.percentage:.0f} %'
          }
        }
        /* Show in legend
        pie: {
          allowPointSelect: true,
          cursor: 'pointer',
          dataLabels: {
            enabled: false
          },
          showInLegend: true
        }
        */
      },
      series: [{
        type: 'pie',
        name: $results.data('questions-label'),
        data: [
            [$results.data('correct-label'), $results.data('final-score')],
            [$results.data('wrong-label'), 100-$results.data('final-score')],
        ]
      }]
    });
  }

  $(window).load(function() {
    $('.flexslider').flexslider({
      animation: "slide",
      controlsContainer: ".slider-holder",
      slideshowSpeed: 10000,
      directionNav: false,
      controlNav: true,
      animationDuration: 2000,
      before:function( slider ){
        $('.img-holder').animate({'bottom' : '-30px'},300)
      },

      after:function( slider ){
        $('.img-holder').animate({'bottom' : '0px'},300)
      }
    });
  });

  $(window).on('resize', function() {
    if ($(window).width() > 767 && $('#navigation > ul').css('display') == 'none') {
      $('#navigation > ul').show();
    }
  });

  function show_next($element) {
    var $parent = $element.parent();
    if (!validate_checkboxes($parent)) {
      $parent.find('.required-message').show();
      return;
    }
    var $next = $parent.next('.question');
    if ($next.length) {
      var hasMore = $parent.next('.question').next('.question').length;
      $parent.hide();
      $next.show();
      if (hasMore) {
        $next.find('.fake-button').show().bind('click', function(event) {
          show_next($(this));
        });
      }
      else {
        $('#quiz_questions_send').show();
      }
    }
  }

  function validate_checkboxes($element) {
    var validated = false;
    $element.find('.answer input').each(function () {
      if ($(this).is(':checked')) {
        validated = true;
      }
    });

    return validated;
  }

})(jQuery);
