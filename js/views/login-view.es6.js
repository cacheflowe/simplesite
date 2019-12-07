class LoginView extends FancyView {

  constructor(el) {
    super(el);
    this.loginForm = this.el.querySelector('#login-form');
    if(this.loginForm) this.initLogin();
    document.body.classList.add('logged-out');
	}

  initLogin() {
     {
      this.loginHandler = this.formSubmitted.bind(this);
      this.loginForm.addEventListener('submit', this.loginHandler);
    }
  }

  formSubmitted(e) {
    e.preventDefault();

    this.loginForm.classList.remove('error');
    _store.set(SimpleSite.LOADER_SHOW, true);
    let userPasswordAttempt = this.loginForm.querySelector('#password').value;

    fetch(this.loginForm.getAttribute('action'), {
      method: this.loginForm.getAttribute('method'),
      body: userPasswordAttempt
    })
      .then(function(response) {
        return response.text();
      }).then((data) => {
        data = JSON.parse(data);
        let success = data.success;
        if(success) {
          let curPath = _store.get(SimpleSite.CUR_PATH);
          if(curPath == '/login') {
            _store.set(SimpleSite.SET_CUR_PATH, '/');     // go home from generic /login
          } else {
            _store.set(SimpleSite.RELOAD_VIEW, true);     // reload the protected page
          }
          document.body.classList.remove('logged-out');
        } else {
          this.loginForm.classList.add('error');
        }
        _store.set(SimpleSite.LOADER_SHOW, false);
      }).catch(function(ex) {
        alert('Fetch failed ' + ex.message);
        _store.set(SimpleSite.LOADER_SHOW, false);
      });
  }

}

window.LoginView = LoginView;
