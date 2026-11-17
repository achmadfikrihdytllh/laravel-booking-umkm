(function () {
  var form = document.getElementById('booking-form');
  if (!form) return;

  var $ = function (id) { return document.getElementById(id); };
  var service = $('f-service'), staff = $('f-staff'), date = $('f-date');
  var time = $('f-time'), box = $('slots'), msg = $('slots-msg');
  var staffMap = {};
  try { staffMap = JSON.parse(form.getAttribute('data-staff') || '{}'); } catch (e) {}
  var anyLabel = staff.options[0].textContent;
  var oldStaff = '';
  var seq = 0;

  function fillStaff() {
    var list = staffMap[service.value] || [];
    var keep = staff.value || oldStaff;
    staff.innerHTML = '';
    var any = document.createElement('option');
    any.value = ''; any.textContent = anyLabel;
    staff.appendChild(any);
    list.forEach(function (p) {
      var o = document.createElement('option');
      o.value = p.id; o.textContent = p.name;
      if (String(p.id) === String(keep)) o.selected = true;
      staff.appendChild(o);
    });
  }

  function setMsg(text) { msg.textContent = text; msg.hidden = !text; }

  function render(slots) {
    box.innerHTML = '';
    if (!slots.length) { setMsg(form.getAttribute('data-t-none')); return; }
    setMsg('');
    slots.forEach(function (s) {
      var b = document.createElement('button');
      b.type = 'button';
      b.className = 'slot' + (time.value === s.time ? ' on' : '');
      b.textContent = s.time;
      b.setAttribute('role', 'radio');
      b.setAttribute('aria-checked', time.value === s.time ? 'true' : 'false');
      b.addEventListener('click', function () {
        time.value = s.time;
        Array.prototype.forEach.call(box.children, function (c) {
          var on = c === b;
          c.classList.toggle('on', on);
          c.setAttribute('aria-checked', on ? 'true' : 'false');
        });
      });
      box.appendChild(b);
    });
  }

  function load() {
    box.innerHTML = '';
    if (!service.value || !date.value) { time.value = ''; setMsg(form.getAttribute('data-t-pick')); return; }
    setMsg(form.getAttribute('data-t-loading'));
    var mine = ++seq;
    var q = new URLSearchParams({ service_id: service.value, date: date.value });
    if (staff.value) q.set('staff_id', staff.value);
    fetch(form.getAttribute('data-slots-url') + '?' + q.toString(), { headers: { Accept: 'application/json' } })
      .then(function (r) { if (!r.ok) throw new Error(); return r.json(); })
      .then(function (d) {
        if (mine !== seq) return;
        var slots = d.slots || [];
        if (!slots.some(function (s) { return s.time === time.value; })) time.value = '';
        render(slots);
      })
      .catch(function () { if (mine === seq) setMsg(form.getAttribute('data-t-fail')); });
  }

  service.addEventListener('change', function () { oldStaff = ''; fillStaff(); time.value = ''; load(); });
  staff.addEventListener('change', function () { time.value = ''; load(); });
  date.addEventListener('change', function () { time.value = ''; load(); });

  form.addEventListener('submit', function (e) {
    if (!time.value) { e.preventDefault(); setMsg(form.getAttribute('data-t-pick')); box.scrollIntoView({ block: 'center' }); }
  });

  fillStaff();
  load();
})();
