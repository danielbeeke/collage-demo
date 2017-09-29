window.addEventListener('load', function() {
  document.onkeydown = function(evt) {
    if (evt.ctrlKey && (evt.keyCode === 59 || evt.keyCode === 186)) {
      var newValue = document.body.dataset.grid === 'visible' ? 'invisible' : 'visible';
      document.body.dataset.grid = newValue;
      localStorage.setItem('grid', newValue);
    }
  };

  document.body.dataset.grid = localStorage.getItem('grid') || false;
});