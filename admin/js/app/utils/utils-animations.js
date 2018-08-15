function slideInDown($element) {

  return anime({
    targets: $element.toArray(),
    translateY: [-10, 0],
    duration: 800
  });

}

function checkMark($element) {

  return anime({
    targets: $element.toArray(),
    translateX: ['-200%', '0%'],
    opacity: 1,
    duration: 260,
    delay: 0,
    elasticity: 40,
    easing: 'easeInOutCubic',
    complete: function() {

      anime({
        targets: $element.toArray(),
        scale: 1.6,
        duration: 200,
        elasticity: 0,
        easing: 'easeInOutCubic',
        complete: function() {

          anime({
            targets: $element.toArray(),
            scale: 1,
            duration: 800,
            elasticity: 0,
            easing: 'easeOutExpo'
          });

        }
      });

    }
  });

}


export {
  checkMark,
  slideInDown
}
