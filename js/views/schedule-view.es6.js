class ScheduleView extends FancyView {

  constructor(el) {
    super(el);
    requestAnimationFrame(() => {
      this.isSchedule = _store.get(SimpleSite.CUR_PATH) == "/schedule"; // do slightly different things between events/schedule
      this.initScheduleForm();
    });
	}

  initScheduleForm() {
    this.scheduleTextForm = this.el.querySelector('form');
    if(this.scheduleTextForm) {
      this.scheduleHandler = this.scheduleFormSubmitted.bind(this);
      // this.scheduleTextForm.addEventListener('submit', this.scheduleHandler);
      this.scheduleTextForm.addEventListener('click', this.scheduleHandler);
    }
  }

  scheduleFormSubmitted(e) {
    if(e.target && e.target.nodeName.toLowerCase() == 'button') {
      if(e.target.hasAttribute('type') && e.target.getAttribute('type') == 'submit') {
        e.preventDefault();
        this.checkDeleteClick(e.target);
        this.submitScheduleForm();
      }
    }
  }

  checkDeleteClick(buttonEl) {
    if(buttonEl.hasAttribute('data-action') && buttonEl.getAttribute('data-action') == 'delete') {
      let parentRow = buttonEl.closest('.row');
      if(parentRow) {
        parentRow.remove();
        // parentRow.parentNode.removeChild(parentRow);
      }
    }
  }

  dateToComparableString(dateObj) {
    let monthInt = parseInt(dateObj.month);
    let dayInt = parseInt(dateObj.day);
    let hourInt = parseFloat(dateObj.timeStart);
    let paddedMonth = (monthInt < 10) ? "0" + monthInt : monthInt;
    let paddedDay = (dayInt < 10) ? "0" + dayInt : dayInt;
    let paddedHour = (hourInt < 10) ? "0" + hourInt : hourInt;
    let compareStr = paddedMonth + '-' + paddedDay + '-' + paddedHour;
    return compareStr;
  }

  submitScheduleForm() {
    document.body.classList.add('data-loading');

    // grad date/title rows
    let dateTitleEntries = this.scheduleTextForm.querySelectorAll('.row');
    let updateObj = {};
    updateObj['dates'] = [];
    dateTitleEntries.forEach((rowEl, i) => {
      let titleVal = rowEl.querySelector('[data-type=title]').value.trim();
      if(titleVal.length > 0 || this.isSchedule == true) {
        let dataRowObj = {};
        dataRowObj['title'] = titleVal.replace(/'/g, "’"); // replace apostrophe inline
        if(rowEl.querySelector('[data-type=month]')) dataRowObj['month'] = rowEl.querySelector('[data-type=month]').value;
        if(rowEl.querySelector('[data-type=day]')) dataRowObj['day'] = rowEl.querySelector('[data-type=day]').value;
        if(rowEl.querySelector('[data-type=timeStart]')) dataRowObj['timeStart'] = rowEl.querySelector('[data-type=timeStart]').value;
        if(rowEl.querySelector('[data-type=timeEnd]')) dataRowObj['timeEnd'] = rowEl.querySelector('[data-type=timeEnd]').value;
        updateObj['dates'].push(dataRowObj);
      }
    });

    // sort on date
    updateObj['dates'].sort((a, b) => {
      let x = this.dateToComparableString(a);
      let y = this.dateToComparableString(b);
      if (x < y) {return -1;}
      if (x > y) {return 1;}
      return 0;
    })

    // send to server
    fetch(this.scheduleTextForm.getAttribute('action'), {
      method: this.scheduleTextForm.getAttribute('method'),
      body: JSON.stringify(updateObj)
    })
      .then(function(response) {
        return response.text();
      }).then((data) => {
        data = JSON.parse(data);
        let success = data.success;
        if(this.isSchedule == false) _store.set(SimpleSite.SET_CUR_PATH, '/events'); // reload page on /events
        if(success) {
          // alert('success');
        } else {
          // this.loginForm.classList.add('error');
        }
        document.body.classList.remove('data-loading');
      }).catch(function(ex) {
        alert('Fetch failed ' + ex.message);
        document.body.classList.remove('data-loading');
      });
  }

  dispose() {
    super.dispose();
    // if(this.scheduleTextForm) this.scheduleTextForm.removeEventListener('submit', this.scheduleHandler);
    if(this.scheduleTextForm) this.scheduleTextForm.removeEventListener('click', this.scheduleHandler);
	}

}

window.ScheduleView = ScheduleView;
