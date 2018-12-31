// Used for add to cart and checkout
function pulse($element) {

  anime({
    targets: $element.toArray(),
    scale: 1.09,
    duration: 300,
    elasticity: 0,
    complete: function() {

      anime({
        targets: $element.toArray(),
        scale: 1,
        duration: 800,
        elasticity: 800
      });

    }
  });

}

// Used for add to cart and checkout
function pulseSoft($element) {

  anime({
    targets: $element.toArray(),
    scale: 1.03,
    duration: 280,
    elasticity: 0,
    complete: function() {

      anime({
        targets: $element.toArray(),
        scale: 1,
        duration: 800,
        elasticity: 800
      });

    }
  });

}

// Used for cart counter
function bounceIn($element) {

  return anime({
    targets: $element.toArray(),
    translateY: 100
  });

}

// Used for notices
function slideInDown($element) {

  return anime({
    targets: $element.toArray(),
    translateY: [-40, 0],
  });

}

function slideInTop($element) {

  return anime({
    targets: $element.toArray(),
    opacity: 1,
    elasticity: 0
  });

}


// Used for notices
function slideInLeft($element) {

  return anime({
    targets: $element.toArray(),
    translateX: ['108%', '0%'],
    duration: 380,
    delay: 0,
    elasticity: 0,
    easing: [0, -0.54, .29, 1.16],
    begin: function() {

      $element.addClass('wps-cart-is-open');

      anime({
        targets: jQuery('.wps-cart-item-container .wps-cart-item').toArray(),
        translateY: '50px',
        delay: 600,
        opacity: 1,
        elasticity: 0,
        duration: 500,
        delay: function(el, i, l) {
          return i * 80;
        }

      });

    }

  });

}


// Used for notices
function slideInRight($element) {

  return anime({
    targets: $element.toArray(),
    translateX: ['110%', '0%'],
    duration: 350,
    elasticity: 200
  });

}


function slideOutRight($element) {

  return anime({
    targets: $element.toArray(),
    translateX: ['0%', '108%'],
    duration: 420,
    delay: 0,
    elasticity: 0,
    easing: [.91, -0.24, .29, 1.56],
    complete: function() {
      $element.removeClass('wps-cart-is-open');
    }
  });

}

export {
  pulse,
  bounceIn,
  slideInDown,
  slideInTop,
  slideInLeft,
  slideInRight,
  slideOutRight,
  pulseSoft
}
