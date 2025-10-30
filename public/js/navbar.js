// navbar.js - lightweight toggle logic
(function(){
  const body = document.body;
  const SIDEBAR_TOGGLE_ID = 'sidebarToggle';
  const STORAGE_KEY = 'sb_sidenav_toggled';

  function applyToggled(toggled){
    if(toggled){
      body.classList.add('sb-sidenav-toggled');
    } else {
      body.classList.remove('sb-sidenav-toggled');
    }
  }

  // restore from storage
  const saved = localStorage.getItem(STORAGE_KEY);
  applyToggled(saved === 'true');

  // attach to any toggle buttons
  document.addEventListener('click', function(e){
    const t = e.target;
    if(t && (t.id === SIDEBAR_TOGGLE_ID || t.closest && t.closest('#'+SIDEBAR_TOGGLE_ID))){
      const now = !body.classList.contains('sb-sidenav-toggled');
      applyToggled(now);
      localStorage.setItem(STORAGE_KEY, String(now));
    }
  });

  // close sidebar on click outside for mobile
  document.addEventListener('click', function(e){
    if(window.innerWidth <= 991){
      if(body.classList.contains('sb-sidenav-toggled')){
        const nav = document.getElementById('layoutSidenav_nav');
        if(nav && !nav.contains(e.target) && !e.target.closest('#sidebarToggle')){
          applyToggled(false);
          localStorage.setItem(STORAGE_KEY, 'false');
        }
      }
    }
  });

  // basic accessibility: close sidebar on Esc
  document.addEventListener('keydown', function(e){
    if(e.key === 'Escape'){
      applyToggled(false);
      localStorage.setItem(STORAGE_KEY, 'false');
    }
  });
})();
