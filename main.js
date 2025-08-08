// DevSpark interactions
(function () {
  // Year in footer
  var y = document.getElementById('year');
  if (y) y.textContent = new Date().getFullYear();

  // Smooth scroll for in-page anchors
  document.querySelectorAll('a[href^="#"]').forEach(function (a) {
    a.addEventListener('click', function (e) {
      var id = this.getAttribute('href');
      if (id.length > 1) {
        var el = document.querySelector(id);
        if (el) {
          e.preventDefault();
          el.scrollIntoView({ behavior: 'smooth', block: 'start' });
          // Collapse navbar on mobile
          var nav = document.getElementById('nav');
          if (nav && nav.classList.contains('show')) {
            var bsCollapse = bootstrap.Collapse.getInstance(nav) || new bootstrap.Collapse(nav, { toggle: false });
            bsCollapse.hide();
          }
        }
      }
    });
  });

  // Activate ScrollSpy if available
  if (typeof bootstrap !== 'undefined' && bootstrap.ScrollSpy) {
    new bootstrap.ScrollSpy(document.body, { target: '#nav', offset: 90 });
  }

  // Show alert from contact.php redirect
  try {
    var params = new URLSearchParams(window.location.search);
    var status = params.get('status');
    var msg = params.get('msg');
    var alertEl = document.getElementById('formAlert');
    if (alertEl && status) {
      var isOk = status === 'ok';
      var cls = isOk ? 'alert-success' : 'alert-danger';
      var text = '';
      switch (msg) {
        case 'mailed':
          text = 'Thank you! Your message was sent successfully.';
          break;
        case 'logged':
          text = 'Thank you! Your message was received (saved locally).';
          break;
        case 'missing_fields':
          text = 'Please fill in your name, a valid email, and your project details.';
          break;
        case 'invalid_email':
          text = 'Please provide a valid email address.';
          break;
        case 'too_many_requests':
          text = 'Please wait a few seconds before submitting again.';
          break;
        case 'invalid_request':
        default:
          text = isOk ? 'Request completed.' : 'Something went wrong. Please try again.';
      }
      alertEl.className = 'alert ' + cls; // reset classes
      alertEl.textContent = text;
      alertEl.classList.remove('d-none');
      // Remove query params from URL without reloading
      if (window.history && window.history.replaceState) {
        var cleanUrl = window.location.protocol + '//' + window.location.host + window.location.pathname;
        window.history.replaceState({}, document.title, cleanUrl);
      }
    }
  } catch (e) {
    // no-op
  }
})();
