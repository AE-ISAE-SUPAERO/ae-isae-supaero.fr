var cookieMgr = {
    get: function(name) {
      var o = new RegExp(name + "=([^;]+)").exec(document.cookie);
      return o && unescape(o[1]);
    },
  
    set: function(name, value, days) {
      var expires = "";
      if (days) {
        expires = "; expires=" + new Date(new Date().getTime() + days*24*60*60*1000).toGMTString();
      }
  
      document.cookie = name + "=" + escape(value) + expires + "; path=/";
    },
  
    erase: function(name) {
      this.set(name, "", -1);
    }
  };
  
  function premeierAffichage() {
      if(cookieMgr.get('dejavu')==null) {
          cookieMgr.set('dejavu', 'oui', null);
          document.getElementById('premeierAffichage').style.display='block';
      }
  }
  
  window.onload = premeierAffichage;

  function close_overlay() {
    document.getElementById('premeierAffichage').style.display='none';
    
}

