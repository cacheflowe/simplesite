class LoginView extends FancyView {

  constructor(el) {
    super(el);
    this.initLogin();
	}

  initLogin() {
    this.loginForm = this.el.querySelector('#login-form');
    if(this.loginForm) {
      this.loginHandler = this.formSubmitted.bind(this);
      this.loginForm.addEventListener('submit', this.loginHandler);
    }
  }

  formSubmitted(e) {
    e.preventDefault();

    this.loginForm.classList.remove('error');
    document.body.classList.add('data-loading');
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
            _store.set(SimpleSite.SET_CUR_PATH, curPath); // reload the protected page
          }
        } else {
          this.loginForm.classList.add('error');
        }
        document.body.classList.remove('data-loading');
      }).catch(function(ex) {
        alert('Fetch failed ' + ex.message);
        document.body.classList.remove('data-loading');
      });
  }

}

window.LoginView = LoginView;
