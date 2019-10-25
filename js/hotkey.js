(function() {
  document.onkeyup = function(e) {
    if (e.altKey && e.which == 71) {
      // Alt + G
      $("#submitButton").click();
    }
  };
})();
